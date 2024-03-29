<?php

namespace App\Http\Requests\Curtain;

use App\Models\Curtain;
use Illuminate\Foundation\Http\FormRequest;

class CurtainFormRequest extends FormRequest
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
        ];

        $place = $this->request->get('place');

        if ($place == Curtain::PLACE_PORT || $place == Curtain::PLACE_PHASE) {
            $rules['port_id_open'] = 'required|integer';
            $rules['port_id_close'] = 'required|integer';
            $rules['device_id'] = 'required|integer';
            if ($place == Curtain::PLACE_PHASE) {
                $rules['time'] = 'required|integer';
            }
        } else {
            $rules['bus_id'] = 'required|integer';
            $rules['address'] = 'required|integer|between:0,255';
            $rules['group'] = 'required|integer|between:0,255';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => 'Не указано название',
        ];

        $place = $this->request->get('place');

        if ($place == Curtain::PLACE_PORT || $place == Curtain::PLACE_PHASE) {
            $messages['port_id_open.required'] = 'Не указан порт для отрытия шторы';
            $messages['port_id_close.required'] = 'Не указан порт для закрытия шторы';
            $messages['device_id.required'] = 'Не выбран контроллер';
            if ($place == Curtain::PLACE_PHASE) {
                $messages['time.required'] = 'Не указано время открытия или закрытия';
            }
        } else {
            $messages['bus_id.required'] = 'Не выбрана шина';
            $messages['address.required'] = 'Не указан адрес';
            $messages['group.required'] = 'Не указана группа';
            $messages['address.integer'] = 'Поле адрес должно быть целым числом';
            $messages['group.integer'] = 'Поле группа должно быть целым числом';
            $messages['address.between'] = 'Значение адреса должно быть числом от :min до :max';
            $messages['group.between'] = 'Значение группы должно быть числом от :min до :max';
        }

        return $messages;
    }
}
