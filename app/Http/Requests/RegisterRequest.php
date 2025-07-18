<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

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
                
            ],
            'password' => [
            'required',
            'string',
            'confirmed',
            Password::min(8)
                ->mixedCase() // cả hoa và thường
                ->letters()   // có chữ cái
                ->numbers()   // có số
                ->symbols(),  // có ký tự đặc biệt
             ],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Vui lòng nhập họ.',
            'last_name.required' => 'Vui lòng nhập tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'email.email' => 'Vui lòng nhập địa chỉ email hợp lệ.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.*' => 'Mật khẩu phải có ký tự hoa, thường, số và ký tự đặc biệt.',
            'password.mixedCase' => 'Mật khẩu phải có cả chữ hoa và chữ thường.',
            'password.letters' => 'Mật khẩu phải có ít nhất một ký tự chữ cái.',
            'password.numbers' => 'Mật khẩu phải có ít nhất một số.',
            'password.symbols' => 'Mật khẩu phải có ít nhất một ký tự đặc biệt.',
     ];
     }
}