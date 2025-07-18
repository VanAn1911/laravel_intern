<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; 
use App\Enums\UserStatus;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Log;

class UpdateProfileRequest extends FormRequest
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
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'address'    => ['nullable', 'string', 'max:255'],
            'status'     => ['required', new Enum(UserStatus::class)],
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Tên không được để trống',
            'first_name.string' => 'Tên phải là một chuỗi ký tự',
            'first_name.max' => 'Tên không được quá 255 ký tự',
            'last_name.required' => 'Họ không được để trống',
            'last_name.string' => 'Họ phải là một chuỗi ký tự',
            'last_name.max' => 'Họ không được quá 255 ký tự',
            'address.string' => 'Địa chỉ phải là một chuỗi ký tự',
            'address.max' => 'Địa chỉ không được quá 255 ký tự',
        ];
    }
}
