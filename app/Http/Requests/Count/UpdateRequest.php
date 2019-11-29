<?php

namespace App\Http\Requests\Count;

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
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:10',
            'id_object' => 'required|integer|min:1',
            'impulse' => 'required|integer|min:1',
            'unit' => 'required|string|max:4'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'type.required' => 'Не указан тип счетчика',
            'id_object.required' => 'Не указан объект',
            'impulse.requried' => 'Не указан',
            'unit.required' => 'Не указана ед.измерения'
        ];
    }
}
