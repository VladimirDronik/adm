<?php

namespace App\Http\Requests\Lamp;

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
            'name' => 'required|string|max:255',
            'value' => 'nullable|integer|max:127',
            'speed' => 'nullable|integer|min:0|max:127',
        ];
    }
}
