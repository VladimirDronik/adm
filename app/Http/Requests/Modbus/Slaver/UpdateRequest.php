<?php

namespace App\Http\Requests\Modbus\Slaver;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'type' => 'required|integer|exists:App\Models\ModbusSlaversType,id',
            'bus' => 'required|integer|exists:App\Models\ModbusBus,id',
            'address' => 'required|integer|min:1|max:247',
        ];
    }
}
