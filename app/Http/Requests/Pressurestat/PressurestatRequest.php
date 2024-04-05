<?php

namespace App\Http\Requests\Pressurestat;

use App\Models\Pressurestat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PressurestatRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:250',
            'type_sensor' => [
                'required',
                'string',
                Rule::in([Pressurestat::TYPE_BMX280, Pressurestat::TYPE_PTSENSOR]),
            ],
            'mode' => 'required|integer|in:0,1',
            'room' => 'nullable|integer',
            'object' => 'nullable|integer|exists:App\Models\HomeObject,id',
            'method_on' => 'nullable|integer|exists:App\Models\Method,id',
            'method_off' => 'nullable|integer|exists:App\Models\Method,id',
            'usensor_id' => 'required|integer|exists:App\Models\Usensor,id_object',
        ];

        switch ($this->request->get('type_sensor')) {
            case Pressurestat::TYPE_BMX280:
                $rules['optimal'] = 'required|integer|min:0|max:760';
                $rules['gisteresis'] = 'required|integer|min:0|max:5';
                $rules['min_alarm'] = 'required|integer|min:0|max:820';
                $rules['max_alarm'] = 'required|integer|min:0|max:820';
                break;
            case Pressurestat::TYPE_PTSENSOR:
                $rules['optimal'] = 'required|integer|min:0|max:2000';
                $rules['gisteresis'] = 'required|integer|min:0|max:100';
                $rules['min_alarm'] = 'required|integer|min:0|max:10000';
                $rules['max_alarm'] = 'required|integer|min:0|max:10000';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 250 символов',
            'name.unique' => 'Датчик давления с таким названием уже существует. Укажите другое название',
            'optimal.required' => 'Не указано оптимальное давление',
            'gisteresis.required' => 'Не указан гистерезис',
            'mode.required' => 'Не указан режим',
            'object.required' => 'Не указан объект влияния',
        ];
    }
}
