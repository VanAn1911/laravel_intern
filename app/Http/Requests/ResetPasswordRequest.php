<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token'    => 'required',
            'email'    => 'required|email',
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
            'token.exists'      => 'Mã xác thực đặt lại mật khẩu không hợp lệ.',
            'token.show'       => 'Mã xác thực đặt lại mật khẩu không hợp lệ.',
            'token.required'    => 'Thiếu mã xác thực đặt lại mật khẩu.',
            'token.valid'       => 'Mã xác thực đặt lại mật khẩu không hợp lệ.',
            'email.required'    => 'Vui lòng nhập email.',
            'email.email'       => 'Email không đúng định dạng.',
            'email.exists'      => 'Email không tồn tại trong hệ thống.',
            'email.valid'       => 'Email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.confirmed'=> 'Xác nhận mật khẩu không khớp.',
            'password.min'      => 'Mật khẩu phải có ít nhất :min ký tự.',
        ];
    }
}
