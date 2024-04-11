<?php

namespace App\Http\Requests\Carbdioxide;

use App\Models\Usensor;
use Illuminate\Foundation\Http\FormRequest;

class CarbdioxideRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:250',
            'mode' => 'required|integer|in:0,1',
            'room' => 'nullable|integer',
            'object' => 'nullable|integer|exists:App\Models\HomeObject,id',
            'method_on' => 'nullable|integer|exists:App\Models\Method,id',
            'method_off' => 'nullable|integer|exists:App\Models\Method,id',
            'usensor_id' => 'required|integer|exists:App\Models\Usensor,id_object',
            'gisteresis' => 'required|integer|min:0|max:100',
            'min_alarm' => 'required|integer|min:0|max:1000',
        ];

        $usensor = Usensor::where('id_object', $this->request->get('usensor_id'))->first();

        if ($usensor) {
            switch ($usensor->type) {
                case Usensor::TYPE_SCD40:
                    $rules['optimal'] = 'required|integer|min:400|max:2000';
                    $rules['max_alarm'] = 'required|integer|min:1000|max:2000';
                    break;
                case Usensor::TYPE_SCD41:
                    $rules['optimal'] = 'required|integer|min:400|max:5000';
                    $rules['max_alarm'] = 'required|integer|min:1000|max:5000';
                    break;
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 250 символов',
            'optimal.required' => 'Не указано оптимальное давление',
            'gisteresis.required' => 'Не указан гистерезис',
            'mode.required' => 'Не указан режим',
            'object.required' => 'Не указан объект влияния',
        ];
    }
}
