<?php

namespace App\Http\Requests\Motionsensor;

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
        return [
            'name' => 'required|string|max:255',
            'id_object' => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Не указано название',
            'id_object.required' => 'Не указан объект',
        ];
    }
}
