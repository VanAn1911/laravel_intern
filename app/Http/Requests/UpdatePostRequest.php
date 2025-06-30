<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'required|max:100',
            'description' => 'nullable|max:200',
            'content' => 'required',
            'publish_date' => 'nullable|date',
            // Không cho user đổi status
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề không được để trống',
            'title.max' => 'Tiêu đề không được quá 100 ký tự',
            'description.max' => 'Mô tả không được quá 200 ký tự',
            'content.required' => 'Nội dung không được để trống',
            'publish_date.date' => 'Ngày đăng phải là một ngày hợp lệ',
        ];
    }
}
