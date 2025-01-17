<?php

namespace App\Http\Requests\Regulator;

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
            'room' => 'required|integer|exists:rooms,id',
            'min_setpoint' => 'required|numeric',
            'max_setpoint' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'room.required' => 'Не указано помещение',
        ];
    }
}
