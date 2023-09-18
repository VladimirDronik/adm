<?php

namespace App\Http\Requests\Usensor;

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
            'name' => 'required|string|max:250|unique:usensors,name',
            'room' => 'nullable|integer|min:0',
            'id_object' => 'nullable|integer|min:1',
            'device_id' => 'required|integer|min:1,device_id',
            'port_SCL' => 'required|integer|min:1,port_SCL',
            'port_SDA' => 'required|integer|min:1,port_SDA',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 250 символов',
            'name.unique' => 'Универсальный датчик с таким названием уже существует. Укажите другое название',
            'device_id.required' => 'Не указано устройство, к которому подключен датчик',
            'port_SCL.required' => 'Не указан порт SCL для датчика',
            'port_SDA.required' => 'Не указан порт SDA для датчика',

        ];
    }
}
