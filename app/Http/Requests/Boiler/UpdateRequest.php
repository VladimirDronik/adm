<?php

namespace App\Http\Requests\Boiler;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:100',
            'id_outside_thermostat' => 'nullable|integer|exists:App\Models\Termostat,id_object',
        ];
    }
}
