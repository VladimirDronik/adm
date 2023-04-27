<?php

namespace App\Http\Requests\Conditioner;

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
            'vendor_id' => 'required|integer',
            'model_id' => 'required|integer',
            'id_object' => 'required|integer',
            'room_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'vendor_id.required' => 'Не указан производитель',
            'model_id.required' => 'Не указана модель',
            'id_object.required' => 'Не указан объект',
            'room_id.required' => 'Не указано помещение',
        ];
    }
}
