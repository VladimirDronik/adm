<?php

namespace App\Http\Requests\Lock;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $rules = [
            'name' => 'required|string|max:255',
        ];

        //Если выбран обычный контроллер, то проверяем порты. если хитпро, то проверяем устройства хитпро
        if ($this->request->get('place') == 'port') {
            $rules['port_id_open'] = 'required|integer';
            if ($this->request->get('type') == 'Electromechanical') {
                $rules['port_id_close'] = 'required|integer';
            }
        } else {
            $rules['hitepro_device_open'] = 'required|integer';
            if ($this->request->get('type') == 'Electromechanical') {
                $rules['hitepro_device_close'] = 'required|integer';
            }
        }

        return $rules;

    }

    public function messages()
    {
        $messages = [
            'name.required' => 'Не указано название',
        ];

        //Если выбран обычный контроллер, то проверяем порты. если хитпро, то проверяем устройства хитпро
        if ($this->request->get('place') == 'port') {
            $rules['port_id_open.required'] = 'Не указан порт для отрытия замка';
            if ($this->request->get('type') == 'Electromechanical') {
                $rules['port_id_close.required'] = 'Не указан порт для закрытия замка';
            }
        } else {
            $rules['hitepro_device_open.required'] = 'Не указано устройство Hite-pro для отрытия замка';
            if ($this->request->get('type') == 'Electromechanical') {
                $rules['hitepro_device_close.required'] = 'Не указано устройство Hite-pro для закрытия замка';
            }
        }

        return $messages;

    }
}
