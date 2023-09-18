<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTimeZoneRequest extends FormRequest
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
            'name' => 'required|string|max:20',
            'value' => 'required|string|timezone|max:20',
            'comment' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 20 символов',
            'value.required' => 'Не указано значение',
            'value.max' => 'Значение должно содержать не более 20 символов',
            'comment.required' => 'Не указано описание',
            'comment.max' => 'Описание должно содержать не более 255 символов',
        ];
    }
}
