<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order' => 'required|array',
            'order.*' => 'required|min:1|distinct',
        ];
    }

    public function messages()
    {
        return [
            'order.*.min' => 'The priority must be at least 1.',
            'order.*.integer' => 'The priority must be an integer.',
            'order.*.distinct' => 'The priorities must be unique.',
        ];
    }
}
