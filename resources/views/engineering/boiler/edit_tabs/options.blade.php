{{ Form::bs_checkbox('ch_current_temp', 'Текущая температура ЦО:', $boiler->boilersParamsFlag->ch_current_temp) }}

{{ Form::bs_checkbox('ch_setpoint_temp', 'Уставка ЦО:', $boiler->boilersParamsFlag->ch_setpoint_temp) }}

{{ Form::bs_checkbox('dhw_current_temp', 'Текущая температура ГВС:', $boiler->boilersParamsFlag->dhw_current_temp) }}

{{ Form::bs_checkbox('dhw_setpoint_temp', 'Уставка ГВС:', $boiler->boilersParamsFlag->dhw_setpoint_temp) }}

{{ Form::bs_checkbox('return_temp', 'Температура обратки:', $boiler->boilersParamsFlag->return_temp) }}

{{ Form::bs_checkbox('modulation', 'Модуляция пламени:', $boiler->boilersParamsFlag->modulation) }}

{{ Form::bs_checkbox('pressure', 'Давление:', $boiler->boilersParamsFlag->pressure) }}

{{ Form::bs_checkbox('error_code', 'Наличие ошибки:', $boiler->boilersParamsFlag->error_code) }}
