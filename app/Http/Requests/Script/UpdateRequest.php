<?php

namespace App\Http\Requests\Script;

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
            'name' => 'required|string|max:100|unique:scripts,name,'.$this->script,
            'code' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 100 символов',
            'name.unique' => 'Скрипт с таким названием уже существует. Укажите другое название',
            'code.required' => 'Не указан код скрипта',
        ];
    }
}
