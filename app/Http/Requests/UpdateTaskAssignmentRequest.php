<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subtask_id' => 'required|exists:sub_tasks,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|numeric',
            'comment' => 'nullable|string',
            'addDate' => 'required|date',
        ];
    }
}
