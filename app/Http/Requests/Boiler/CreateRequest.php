<?php

namespace App\Http\Requests\Boiler;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
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
            'name' => 'required|string|max:100|',
            'ip_address_boiler' => 'required|string|ip|max:15',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'description.max' => 'Название содержит более 100 символов',
            'ip_address_boiler.required' => 'Не указан ip адрес',
            'ip_address_boiler.max' => 'IP адрес содержит более 15 символов',
            'ip_address_boiler.ip' => 'Недопустимый ip адрес',
        ];
    }
}
