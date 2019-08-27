<?php

namespace App\Http\Requests\Device;

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
            'type' => 'required|integer|min:0',
            'description' => 'required|string|max:255',
            'ip_address' => 'required|string|ip|max:15',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Не указан тип устройства',
            'description.required' => 'Не указано название',
            'description.max' => 'Название содержит более 255 символов',
            'ip_address.required' => 'Не указан ip адрес',
            'ip_address.max' => 'IP адрес содержит более 15 символов',
            'ip_address.ip' => 'Недопустимый ip адрес'
        ];
    }
}
