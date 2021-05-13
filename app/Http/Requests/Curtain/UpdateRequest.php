<?php

namespace App\Http\Requests\Curtain;

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
            'name' => 'required|string|max:255',
            'time' => 'required|integer|min:1'
        ];

        //Если выбран обычный контроллер, то проверяем порты. если хитпро, то проверяем устройства хитпро
        if ($this->request->get('place') == 'port') {
            $rules['port_id_open'] = 'required|integer';
            $rules['port_id_close'] = 'required|integer';
        } else {
            $rules['hitepro_device_open'] = 'required|integer';
            $rules['hitepro_device_close'] = 'required|integer';
        }


        return $rules;

    }

    public function messages()
    {
        $messages =  [
            'name.required' => 'Не указано название',
            'time.required' => 'Не указано время для закрытия или отрытия шторы'
        ];


        //Если выбран обычный контроллер, то проверяем порты. если хитпро, то проверяем устройства хитпро
        if ($this->request->get('place') == 'port') {
            $rules['port_id_open.required'] = 'Не указан порт для отрытия шторы';
            $rules['port_id_close.required'] = 'Не указан порт для закрытия шторы';
        } else {
            $rules['hitepro_device_open.required'] = 'Не указано устройство Hite-pro для отрытия шторы';
            $rules['hitepro_device_close.required'] = 'Не указано устройство Hite-pro для закрытия шторы';
        }

        return $messages;

    }
}
