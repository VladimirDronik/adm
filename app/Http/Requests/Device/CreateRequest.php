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
            'type' => 'required',
            'description' => 'required|string|max:255|unique:devices,description',
            'password' => 'required|string|max:100',
            'ip_address' => 'required|string|ip|max:15',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Не указан тип контроллера',
            'description.required' => 'Не указано название',
            'description.max' => 'Название содержит более 255 символов',
            'description.unique' => 'Контроллер с таким названием уже существует. Необходимо изменить название',
            'ip_address.ip' => 'Недопустимый ip адрес',
            'ip_address.max' => 'IP адрес содержит более 15 символов',
            'ip_address.required' => 'Не указан ip адрес',
        ];
    }
}
