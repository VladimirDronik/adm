<?php

namespace App\Http\Requests\Event;

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
            'name' => 'required|string|max:100|unique:scheduler_tasks,name',
            'object' => 'required|integer|min:0',
            'method' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 100 символов',
            'name.unique' => 'Событие с таким названием уже существует. Выберите другое название',
            'object.required' => 'Не указан объект',
            'method.required' => 'Не указан метод'
        ];
    }
}
