<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:30'],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                'unique:users,email',
                // Có thể thêm rule kiểm tra domain nếu muốn
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // ký tự thường
                'regex:/[A-Z]/',      // ký tự hoa
                'regex:/[0-9]/',      // số
                'regex:/[@$!%*#?&]/', // ký tự đặc biệt
                'confirmed'
            ],
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Mật khẩu phải có ký tự hoa, thường, số và ký tự đặc biệt.',
        ];
    }
}