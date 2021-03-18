<?php

namespace App\Http\Requests\Manometr;

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
        $rules = [
            'name' => 'required|string|max:250',
            'calibration' => 'required|numeric|between:0,4.99',
            'low_value' => 'nullable|integer|min:0|max:5000',
            'high_value' => 'nullable|integer|min:0|max:10000',
            'room' => 'nullable|integer|min:0',
        ];

        $ids = ['low_object', 'low_method', 'high_object', 'high_method'];
        foreach ($ids as $id) {
            $rules[$id] = 'nullable|integer|min:0';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 250 символов',
            'calibration.required' => 'Не указана калибровка датчика',
        ];
    }
}
