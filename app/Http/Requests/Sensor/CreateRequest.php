<?php

namespace App\Http\Requests\Sensor;

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
            'name' => 'required|string',
            'room' => 'nullable|integer|exists:App\Models\Room,id',
            'type' => 'required|string',
            'source' => 'nullable|string',
            'input_source' => 'nullable|string',
            'source_id' => 'nullable|integer',
            'connection' => 'nullable|string',
            'input_connection' => 'nullable|string',
            'port' => 'nullable|integer|exists:App\Models\Port,id',
            'sda' => 'nullable|integer|exists:App\Models\Port,id',
            'scl' => 'nullable|integer|exists:App\Models\Port,id',
            'address' => 'nullable|integer',
        ];
    }
}
