<?php

namespace App\Http\Requests\Recorder;

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
            'ip_address' => 'required|string|ip|max:15',
            'login' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'login.required' => 'Не указан логин',
            'ip_address.required' => 'Не указан ip адрес',
            'ip_address.max' => 'IP адрес содержит более 15 символов',
            'ip_address.ip' => 'Недопустимый ip адрес',
        ];
    }
}
