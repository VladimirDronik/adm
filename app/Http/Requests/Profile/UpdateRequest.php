<?php

namespace App\Http\Requests\Profile;

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
            'login' => 'required|string|min:2|unique:users,login,'.id(),
            'password' => 'nullable|string|min:6|max:60|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Не указан логин',
            'login.min' => 'Логин должен содержать не менее 2 символов',
            'login.unique' => 'Логин занят другим пользователем',
            'password.min' => 'Пароль должен содержать не менее 6 символов',
            'password.confirmed' => 'Пароль и повтор пароля не совпадают',
            'password.max' => 'Пароль должен содержать не более 60 символов',
        ];
    }
}
