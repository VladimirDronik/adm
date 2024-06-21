<?php

namespace App\Http\Requests\Conditioner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'room_id' => 'nullable|integer|exists:App\Models\Room,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
        ];
    }
}
