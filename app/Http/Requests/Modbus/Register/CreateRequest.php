<?php

namespace App\Http\Requests\Modbus\Register;

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
            'name' => 'required|string|max:100',
            'slaver_id' => 'required|integer|exists:App\Models\ModbusSlaver,id',
            'register_type' => 'required|string',
            'starting_register' => 'required|integer|min:0|max:65535',
            'registers_quantity' => 'nullable|integer|min:1|max:125',
            'data_format' => 'required|string',
            'units' => 'nullable|string',
            'scale_unit' => 'nullable|numeric',
            'access' => 'required|string',
            'alias' => 'nullable|string|max:50',
        ];
    }
}
