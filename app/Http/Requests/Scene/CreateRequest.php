<?php

namespace App\Http\Requests\Scene;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'label' => 'required|string|max:150',
            'background_color' => 'nullable|string|max:7'
        ];
    }

    public function messages()
    {
        return [
            'label.required' => 'Не указано название',
            'label.max' => 'Название содержит более 150 символов',
            'background_color.max' => 'Недопустимый цвет фона',
        ];
    }
}
