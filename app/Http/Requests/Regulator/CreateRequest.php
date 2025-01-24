<?php

namespace App\Http\Requests\Regulator;

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
            'name' => 'required|string',
            'room' => 'required|integer|exists:rooms,id',
            'min_setpoint' => 'required|numeric',
            'max_setpoint' => 'required|numeric',
        ];

        if (request()->has('independent_device')) {
            $rules['source'] = 'required|string|in:modbus,megad';

            switch (request()->input('source')) {
                case 'modbus':
                    $rules['modbus_slaver'] = 'required|integer|exists:modbus_slavers,id';
                    $rules['modbus_register'] = 'required|integer|exists:modbus_registers,id';
                    break;
                case 'megad':
                    $rules['device'] = 'required|integer|exists:devices,id';
                    $rules['port'] = 'required|integer|exists:ports,id';
                    break;
            }
        } else {
            $rules['sensor_param'] = 'required|integer|exists:sensors_params,id';
            $rules['setpoint'] = 'required|numeric';
            $rules['hysteresis'] = 'required|numeric';
            $rules['higher_method'] = 'required|integer|exists:methods,id';
            $rules['higher_method_params'] = 'nullable|string';
            $rules['lower_method'] = 'required|integer|exists:methods,id';
            $rules['lower_method_params'] = 'nullable|string';
            $rules['fallback_method'] = 'nullable|integer|exists:methods,id';
            $rules['fallback_method_params'] = 'nullable|string';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'room.required' => 'Не указано помещение',
        ];
    }
}
