<?php
// app/Http/Requests/LoginRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_name' => 'required|string',
            'password' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'user_name.required' => 'Username is required',
            'password.required' => 'Password is required'
        ];
    }
}
