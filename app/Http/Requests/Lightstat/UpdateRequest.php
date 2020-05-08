<?php

namespace App\Http\Requests\Lightstat;

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
            'optimal' => 'required|integer|min:0|max:54612',
            'gisteresis' => 'required|integer|min:0|max:5000',
            'mode' => 'required|integer|min:0|max:1',
            'min_threshold' => 'required|integer',
            'max_threshold' => 'required|integer|max:54612',
            'min_alarm' => 'required|integer',
            'max_alarm' => 'required|integer|max:54612',
            'room' => 'nullable|integer|min:0',
            'id_object' => 'nullable|integer|min:1'
        ];

        $ids = ['object', 'method_on', 'method_off'];
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
            'name.unique' => 'Светостат с таким названием уже существует. Укажите другое название',
            'optimal.required' => 'Не указана оптимальная освещенность',
            'gisteresis.required' => 'Не указан гистерезис',
            'mode.required' => 'Не указан режим',
            'id_object.required' => 'Не указан объект светостата',
            'object.required' => 'Не указан объект влияния',
        ];
    }
}
