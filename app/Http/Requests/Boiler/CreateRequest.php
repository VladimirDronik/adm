<?php

namespace App\Http\Requests\Boiler;

use App\Models\HomeObject;
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
        $rules = [
            'name' => 'required|string|max:100',
            'gateway_type' => 'required|string',
            'type_boiler' => 'required|string',
            'id_outside_thermostat' => 'nullable|integer|exists:App\Models\Termostat,id_object',
        ];

        switch ($this->request->get('gateway_type')) {
            case HomeObject::GATEWAY_HTTP:
                $rules['http_gateway_id'] = 'required|integer|exists:App\Models\Device,id';
                break;
            case HomeObject::GATEWAY_MODBUS:
                $rules['modbus_gateway_id'] = 'required|integer|exists:App\Models\ModbusSlaver,id';
                break;
        }

        return $rules;
    }
}
