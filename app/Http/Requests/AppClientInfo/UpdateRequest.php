<?php

namespace App\Http\Requests\AppClientInfo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'address' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано имя клиента',
            'name.max' => 'Имя клиента содержит более 150 символов',
            'address.required' => 'Не указан адрес'
        ];
    }
}
