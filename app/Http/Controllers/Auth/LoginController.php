<?php
// app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = [
                'login_id' => $request->user_name,
                'password' => $request->password
            ];

            // Attempt login
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Update last login time
                $user->last_login = now();
                $user->save();

                // Regenerate session
                $request->session()->regenerate();

                // Store additional session data
                session([
                    'dept' => $user->dept,
                    'user_name' => $user->Name,
                    'position' => $user->position,
                    'login_id' => $user->login_id,
                    'login_waqat' => time()
                ]);

                // Redirect based on role
                return $this->redirectBasedOnRole($user->position);
            }

            // Login failed - THIS WILL SHOW ERROR NOW
            return back()->withErrors([
                'login_error' => 'Invalid username or password.'
            ])->withInput($request->except('password'));

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Login error: ' . $e->getMessage());

            return back()->withErrors([
                'login_error' => 'Login failed. Please try again.'
            ])->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->last_logout = now();
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectBasedOnRole($position)
    {
        $redirects = [
            'SerAdvisor' => '/service/jobcard',
            'SManager' => '/service/sm',
            'IMCc' => '/service/sm',
            'PManager' => '/parts/entry',
            'DataOperator' => '/parts/entry',
            'body_PaintJC' => '/service/bp-jc',
            'JobController' => '/service/jc',
            'Cashier' => '/finance/cashier',
            'FManager' => '/finance/accounts',
            'Accountant' => '/finance/accounts',
            'RecoveryExec' => '/finance/recovery',
            'CRO' => '/cr/cro',
            'CRManager' => '/cr/cro',
            'Tsure' => '/t-sure/admin',
            'IT Manager' => '/it-manager',
        ];

        if (isset($redirects[$position])) {
            return redirect($redirects[$position]);
        }

        return back()->with('error', 'Department is not available for logged-in user.');
    }
}
