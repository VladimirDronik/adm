<?php

namespace App\Http\Requests\Setting;

use Gate;
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
        return Gate::allows('settings.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:20|unique:settings,name',
            'value' => 'required|string|max:20',
            'comment' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'name.max' => 'Название содержит более 20 символов',
            'name.unique' => 'Параметр с таким названием уже существует. Укажите другое название',
            'value.required' => 'Не указано значение',
            'value.max' => 'Значение должно содержать не более 20 символов',
            'comment.required' => 'Не указано описание',
            'comment.max' => 'Описание должно содержать не более 255 символов',
        ];
    }
}
