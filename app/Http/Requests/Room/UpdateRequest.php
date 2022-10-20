<?php

namespace App\Http\Requests\Room;

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
            'temperature_normal' => 'required|integer|min:10|max:30',
            'temperature_night' => 'required|integer|min:10|max:30',
            'temperature_eco' => 'required|integer|min:10|max:30',
        ];
    }

    public function messages()
    {
        return [
        ];
    }
}
