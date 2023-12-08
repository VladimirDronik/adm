<?php

namespace App\Http\Requests\Camera;

use App\Models\Camera;
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
            'name' => 'required|string|max:255',
            'active' => 'nullable|boolean',
            'vendor' => 'required|string|max:255',
        ];

        switch ($this->request->get('vendor')) {
            case Camera::VENDOR_IVIDEON:
                $rules['link'] = 'required|string|max:255';
                break;
            case Camera::VENDOR_HIKVISION_HIWATCH:
                $rules['ip_address'] = 'required|string|ip|max:15';
                $rules['login'] = 'required|string|max:255';
                $rules['password'] = 'required|string|max:255';
                break;
            case Camera::VENDOR_OTHER:
                $rules['ip_address'] = 'required|string|ip|max:15';
                $rules['login'] = 'required|string|max:255';
                $rules['password'] = 'required|string|max:255';
                $rules['link_rtsp'] = 'required|string|max:255';
                break;
        }

        return $rules;
    }
}
