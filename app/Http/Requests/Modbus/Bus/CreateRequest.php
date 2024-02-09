<?php

namespace App\Http\Requests\Modbus\Bus;

use App\Models\ModbusBus;
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
            'type' => 'required|string',
        ];

        switch ($this->request->get('type')) {
            case ModbusBus::TYPE_RTU:
                $rules['device_select'] = 'required|string|max:100';
                $rules['baudrate'] = 'required|integer';
                $rules['length'] = 'required|integer';
                $rules['parity'] = 'required|string|max:10';
                $rules['stopbits'] = 'required|integer';
                break;
            case ModbusBus::TYPE_TCP:
                $rules['device_text'] = 'required|string|max:100';
                $rules['ip_address'] = 'required|string|ip|max:15';
                $rules['port'] = 'required|integer|min:0|max:65535';
                break;
        }

        return $rules;
    }
}
