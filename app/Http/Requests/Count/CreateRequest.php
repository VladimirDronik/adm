<?php

namespace App\Http\Requests\Count;

use App\Models\HomeObject;
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
            'name' => 'required|string|max:255',
            'gateway_type' => 'required|string',
            'type' => 'required|string|max:10',
            'impulse' => 'required|numeric|min:0',
            'unit' => 'required|string|max:6',
        ];

        switch ($this->request->get('gateway_type')) {
            case HomeObject::GATEWAY_HTTP:
                $rules['http_gateway_id'] = 'required|integer|exists:App\Models\Device,id';
                $rules['port_id'] = 'required|integer|exists:App\Models\Port,id';
                break;
            case HomeObject::GATEWAY_MODBUS:
                $rules['modbus_gateway_id'] = 'required|integer|exists:App\Models\ModbusSlaver,id';
                $rules['register_id'] = 'required|integer|exists:App\Models\ModbusRegister,id';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'type.required' => 'Не указан тип счетчика',
            'impulse.required' => 'Не указано значение за один импульс',
            'unit.required' => 'Не указана единица измерения',
        ];
    }
}
