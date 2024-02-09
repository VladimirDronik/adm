<?php

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'type' => 'required',
            'description' => 'required|string|max:255|unique:devices,description',
            'password' => 'nullable|string|max:100',
            'port' => 'nullable|integer|min:0|max:65535',
        ];

        if ($this->request->get('type') == 'WB-LED') {
            $rules['ip_address'] = 'required|string|max:3';
            $rules['wb_led_port'] = 'required|integer';
        } else {
            $rules['ip_address'] = 'required|string|ip|max:15';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'type.required' => 'Не указан тип контроллера',
            'description.required' => 'Не указано название',
            'description.max' => 'Название содержит более 255 символов',
            'description.unique' => 'Контроллер с таким названием уже существует. Необходимо изменить название',
        ];

        if ($this->request->get('type') == 'WB-LED') {
            $messages['ip_address.max'] = 'Адрес содержит более 3 символов';
            $messages['ip_address.required'] = 'Не указан адрес';
            $messages['wb_led_port.required'] = 'Не указан порт JetHome';
        } else {
            $messages['ip_address.ip'] = 'Недопустимый ip адрес';
            $messages['ip_address.max'] = 'IP адрес содержит более 15 символов';
            $messages['ip_address.required'] = 'Не указан ip адрес';
        }

        return $messages;
    }
}
