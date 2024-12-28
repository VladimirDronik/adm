<?php

namespace App\Http\Requests\Sensor;

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
            'name' => 'required|string',
            'room' => 'nullable|integer|exists:App\Models\Room,id',
            'source_id' => 'nullable|integer',
            'port' => 'nullable|integer|exists:App\Models\Port,id',
            'sda' => 'nullable|integer|exists:App\Models\Port,id',
            'scl' => 'nullable|integer|exists:App\Models\Port,id',
        ];
    }
}
