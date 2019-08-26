<?php

namespace App\Http\Requests\View;

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
            'type' => 'required',
            'room' => 'required|integer|min:0',
            'scene' => 'required|integer|min:0',
            'position_left' => 'required|integer|min:0',
            'position_top' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Не указан тип элемента',
            'room.required' => 'Не указано помещение',
            'scene.required' => 'Не указана сцена',
            'position_left.required' => 'Не указан левый отступ',
            'position_top.required' => 'Не указан верхний отступ',
        ];
    }
}
