<?php

namespace App\Http\Requests\Network;

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
            'main_ip' => 'required|string|ip|max:15',
            'main_mask' => 'required|string|ip|max:15',
            'main_gateway' => 'required|string|ip|max:15',
            'ip' => 'required|string|ip|max:15',
            'mask' => 'required|string|ip|max:15',
            'vpn_address' => 'required|string|ip|max:15',
            'vpn_login' => 'required|string|min:2',
            'vpn_password' => 'required|string|min:6|max:150',
        ];
    }

    public function messages()
    {
        return [
            'main_ip.required' => 'Не указан ip основного сервера',
            'main_ip.max' => 'IP основного сервера содержит более 15 символов',
            'main_ip.ip' => 'Недопустимый ip для основного сервера',
            'main_mask.required' => 'Не указана маска основного сервера',
            'main_mask.max' => 'Маска основного сервера содержит более 15 символов',
            'main_mask.ip' => 'Недопустимая маска для основного сервера',
            'main_gateway.required' => 'Не указан шлюз основного сервера',
            'main_gateway.max' => 'Шлюз основного сервера содержит более 15 символов',
            'main_gateway.ip' => 'Недопустимый шлюз для основного сервера',
            'ip.required' => 'Не указан ip для подсети',
            'mask.required' => 'Не указана маска для подсети',
            'mask.max' => 'Маска для подсети содержит более 15 символов',
            'mask.ip' => 'Недопустимая маска для подсети',
            'vpn_address.required' => 'Не указан адрес сервера vpn',
            'vpn_address.max' => 'Адрес сервера vpn содержит более 15 символов',
            'vpn_address.ip' => 'Недопустимый адрес сервера vpn',
            'vpn_login.required' => 'Не указан логин vpn',
            'vpn_password.required' => 'Не указан пароль vpn',
            'vpn_password.min' => 'Пароль должен содержать не менее 6 символов',
            'ip.max' => 'IP для подсети содержит более 15 символов',
            'ip.ip' => 'Недопустимый ip для подсети',
        ];
    }
}
