<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PostStatus;
use Illuminate\Validation\Rules\Enum;


class AdminUpdatePostRequest extends FormRequest
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
            'title' => 'required|max:100',
            'description' => 'required|max:200',
            'content' => 'required',
            'publish_date' => 'required|date',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'     => ['required', new Enum(PostStatus::class)],
        ];


        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề không được để trống',
            'title.max' => 'Tiêu đề không được quá 100 ký tự',
            'description.max' => 'Mô tả không được quá 200 ký tự',
            'description.required' => 'Mô tả không được để trống',
            'content.required' => 'Nội dung không được để trống',
            'publish_date.date' => 'Ngày đăng phải là một ngày hợp lệ',
            'publish_date.required' => 'Ngày đăng không được để trống',
            'thumbnail.image' => 'Ảnh đại diện phải là một hình ảnh',
            'thumbnail.mimes' => 'Ảnh đại diện phải có định dạng jpg, jpeg, png, webp',
            'thumbnail.max' => 'Ảnh đại diện không được quá 2MB',
            'status.required' => 'Trạng thái không được để trống',
        ];
    }
}
