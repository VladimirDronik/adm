<?php

namespace App\Http\Requests\Element;

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
        $rules = [
            'type' => 'required',
            'name' => 'required|string|max:250',
            'position' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'type.required' => 'Не выбран тип',
            'position.required' => 'Не указана позиция',
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 250 символов',
        ];
    }
}
