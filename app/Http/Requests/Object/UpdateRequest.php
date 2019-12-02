<?php

namespace App\Http\Requests\Object;

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
            'type' => 'required|string|max:10',
            'name' => 'required|string|max:100|unique:objects,name,'.$this->object,
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Не указан тип объекта',
            'type.max' => 'Недопустимый тип объекта',
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 100 символов',
            'name.unique' => 'Объект с таким названием уже существует. Укажите другое название'
        ];
    }
}
