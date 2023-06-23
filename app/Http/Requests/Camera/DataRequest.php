<?php

namespace App\Http\Requests\Camera;

use Illuminate\Foundation\Http\FormRequest;

class DataRequest extends FormRequest
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
            'image' => 'required|mimes:jpeg,jpg,png,gif,bmp,webp,svg',
            'link' => 'required|string|max:255',
            'room_id' => 'required|integer',
            'sort' => 'required|integer',
            'active' => 'nullable|boolean',
        ];
    }
}
