<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubTaskRequest extends FormRequest
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
            'task_id' => 'required|exists:tasks,id', // Taskni tanlash shart, va u mavjud bo'lishi kerak
            'title' => 'required|string|max:255', // Title majburiy va maksimal uzunligi 255 bo'lishi kerak
            'category' => 'nullable', // Category majburiy va maksimal uzunligi 255 bo'lishi kerak
            'min' => 'required|numeric', // Min qiymati son bo'lishi kerak va 0 yoki undan katta bo'lishi kerak
            'max' => 'required|numeric|gte:min', // Max qiymati son bo'lishi kerak, 0 yoki undan katta bo'lishi kerak, min'dan katta yoki teng bo'lishi kerak
        ];
    }
}
