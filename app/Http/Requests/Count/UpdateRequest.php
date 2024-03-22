<?php

namespace App\Http\Requests\Count;

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
            'impulse' => 'required|numeric|min:0',
            'today_value' => 'required|numeric|min:0',
            'total_value' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'impulse.required' => 'Не указано значение за один импульс',
            'today_value.required' => 'Не указано значение за сегодня',
            'total_value.required' => 'Не указано общее значение',
            'today_value.numeric' => 'Недопустимое значение за сегодня',
            'total_value.numeric' => 'Недопустимое общее значение',
            'today_value.min' => 'Недопустимое значение за сегодня',
            'total_value.min' => 'Недопустимое общее значение',
        ];
    }
}
