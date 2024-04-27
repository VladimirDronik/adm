<?php

namespace App\Http\Requests\Usensor;

use Illuminate\Foundation\Http\FormRequest;

class UsensorRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:250',
            'room' => 'required|integer|exists:App\Models\Room,id',
            'type' => 'nullable|string',
            'device_id' => 'required|integer|exists:App\Models\Device,id',
            'port_SCL' => 'required|integer|exists:App\Models\Port,id',
            'port_SDA' => 'required|integer|exists:App\Models\Port,id',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 250 символов',
            'device_id.required' => 'Не указано устройство, к которому подключен датчик',
            'port_SCL.required' => 'Не указан порт SCL для датчика',
            'port_SDA.required' => 'Не указан порт SDA для датчика',
        ];
    }
}
