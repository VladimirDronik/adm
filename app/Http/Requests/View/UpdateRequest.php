<?php

namespace App\Http\Requests\View;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'required|string|max:8',
            'description' => 'nullable|string|max:255',
            'room' => 'required|integer|min:0',
            'scene' => 'nullable|integer|min:0',
            'position_left' => 'nullable|integer|min:0',
            'position_top' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Не указан тип элемента',
            'room.required' => 'Не указано помещение',
            'description.max' => 'Описание содержит более 255 символов'
        ];
    }
}
