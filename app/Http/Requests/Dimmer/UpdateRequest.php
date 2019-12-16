<?php

namespace App\Http\Requests\Dimmer;

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
            'name' => 'required|string|max:100',
            'id_object' => 'required|integer|min:1',
            'value' => 'required|numeric',
            'speed' => 'required|numeric|min:0'
        ];

    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'id_object.required' => 'Не указан объект',
            'value.required' => 'Не указано значение',
            'speed.required' => 'Не указана скорость',
        ];
    }
}
