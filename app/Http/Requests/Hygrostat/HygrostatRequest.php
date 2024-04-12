<?php

namespace App\Http\Requests\Hygrostat;

use Illuminate\Foundation\Http\FormRequest;

class HygrostatRequest extends FormRequest
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
            'name' => 'required|string|max:250',
            'type' => 'required|integer|in:0,1',
            'room' => 'nullable|integer',
            'object' => 'nullable|integer|exists:App\Models\HomeObject,id',
            'method_on' => 'nullable|integer|exists:App\Models\Method,id',
            'method_off' => 'nullable|integer|exists:App\Models\Method,id',
            'usensor_id' => 'required|integer|exists:App\Models\Usensor,id_object',
            'gisteresis' => 'required|integer|min:0|max:10',
            'optimal' => 'required|integer|min:0',
            'min_alarm' => 'required|integer|min:0|max:100',
            'max_alarm' => 'required|integer|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 250 символов',
            'optimal.required' => 'Не указано оптимальное влажность',
            'gisteresis.required' => 'Не указан гистерезис',
            'type.required' => 'Не указан режим',
            'object.required' => 'Не указан объект влияния',
        ];
    }
}
