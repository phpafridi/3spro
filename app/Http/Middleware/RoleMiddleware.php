<?php
// app/Http/Middleware/RoleMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

        if ($user->position == 'IT Manager') {
            return $next($request);
        }
        if (in_array($user->position, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access. Your role does not have permission for this page.');
    }
}
