<?php

namespace App\Http\Requests\Hygrostat;

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
            'name' => 'required|string|max:250|unique:hygrostats,name',
            'id_termometr' => 'nullable|string|max:12',
            'optimal' => 'required|integer|min:0',
            'gisteresis' => 'required|integer|min:0|max:10',
            'type' => 'required|integer|min:0|max:1',
//            'min_threshold' => 'required|integer',    // Пока убрали на странице ввод этих значений
//            'max_threshold' => 'required|integer|max:100', // Пока убрали на странице ввод этих значений
            'min_alarm' => 'required|integer',
            'max_alarm' => 'required|integer|max:100',
            'room' => 'nullable|integer|min:0',
            'id_object' => 'nullable|integer|min:1'
        ];

        $ids = ['object', 'method_on', 'method_off'];
        foreach ($ids as $id) {
            $rules[$id] = 'nullable|integer|min:0';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 250 символов',
            'name.unique' => 'Гигростат с таким названием уже существует. Укажите другое название',
            'optimal.required' => 'Не указана оптимальная температура на вкладке свойств',
            'gisteresis.required' => 'Не указан гистерезис на вкладке свойств',
            'type.required' => 'Не указан режим на вкладке свойств',
            'id_object.required' => 'Не указан объект термостата',
            'object.required' => 'Не указан объект влияния на вкладке методов',
        ];
    }
}
