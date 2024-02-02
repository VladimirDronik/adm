@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Освещение'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-success m-b-10 m-l-5" id="addIlluminationBtn">Добавить устройство</button>
                        <a href="{{ route('illumination.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Устройства освещения</h4></div>
            <div class="card-body">
                @if(count($illuminations))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Тип</th>
                                    <th>Название</th>
                                    <th>Подключение</th>
                                    <th>Размещение</th>
                                    <th>Свойства</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($illuminations as $illumination)
                                    <tr id="tr{{ $illumination->id_object }}">
                                        <td scope="row">
                                            {{ $illumination->id_object }}
                                        </td>
                                        <td>
                                            {{ $illumination->object ? $illumination->object->type : '' }}
                                        </td>
                                        <td>
                                            {{ $illumination->name }}
                                        </td>
                                        @if($illumination instanceof \App\Models\Lamp)
                                            <td>
                                                {{ $illumination->gateway_name }}
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                                Статус: {{ $illumination->object->status }}
                                                @if($illumination->type == 'dimmer' && $illumination->oldvalue)
                                                    <br>
                                                    Яркость: {{ $illumination->oldvalue }}
                                                @endif
                                            </td>
                                            <td align="center" class="text-center">
                                                <a href="{{ route('lamps.edit', [$illumination->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                    <i class="fa fa-cog fa-lg"></i>
                                                </a>
                                            </td>
                                        @elseif($illumination instanceof \App\Models\LedTape)
                                            <td>
                                                {{ $illumination->related_device ? $illumination->related_device->description : '' }}
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                                Статус: {{ $illumination->object->status }}
                                                <br>
                                                @if($illumination->type != 'W')
                                                    <div style="height: 30px; display: inline-flex;">Цвет: &nbsp;<div style="width: 30px; height: 30px; background-color: hsl({{ $illumination->h }}, {{ $illumination->hsvToHsl()['s'] }}%, {{ $illumination->hsvToHsl()['l'] }}%)"></div></div>
                                                @else
                                                    Белый: {{ $illumination->w }}
                                                @endif
                                            </td>
                                            <td align="center" class="text-center">
                                                <a href="{{ route('led_tapes.edit', [$illumination->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                    <i class="fa fa-cog fa-lg"></i>
                                                </a>
                                            </td>
                                        @elseif($illumination instanceof \App\Models\DaliDevice)
                                            <td>
                                                {{ $illumination->modbusSlaver->name . ' (' . $illumination->address . ')' }}
                                            </td>
                                            <td>
                                                {{ $illumination->relatedRoom ? $illumination->relatedRoom->name : '' }}
                                            </td>
                                            <td>
                                                Неисправность: {{ $illumination->failure ? 'Да' : 'Нет' }}
                                                <br>
                                                Статус: {{ $illumination->object ? $illumination->object->status : '' }}
                                                <br>
                                                Яркость: {{ $illumination->brightness }}
                                                @if($illumination->is_cct)
                                                    <br>
                                                    Цветовая температура: {{ $illumination->cct }}
                                                @endif
                                            </td>
                                            <td align="center" class="text-center">
                                                <a href="{{ route('mod_bus.dali_devices.edit', [$illumination->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                    <i class="fa fa-cog fa-lg"></i>
                                                </a>
                                            </td>
                                        @endif
                                        @if(!($illumination instanceof \App\Models\DaliDevice))
                                            <td align="center" class="text-center">
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id="{{ $illumination->id }}" data-id_object="{{ $illumination->id_object }}" data-name="{{ $illumination->name }}" data-type="{{ $illumination->object->type }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            </td>
                                        @else
                                            <td align="center" class="text-center">
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Тип</th>
                                    <th>Название</th>
                                    <th>Подключение</th>
                                    <th>Размещение</th>
                                    <th>Свойства</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $illuminations->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $illuminations->total() }}</p>
                @else
                    <p>Устройства освещения не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
    @include('illumination.create_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('illumination.index') }}';

        $(document).ready(function(){
            let del_id;
            let del_url;
            let type;
            let del_id_object;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                type = $(this).data('type')
                del_id_object = $(this).data('id_object');

                $('#del_modal_body').text('Удалить устройство освещения «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();

                if (type == 'lamp' || type == 'dimmer') {
                    del_url = '{{ route('ajax.lamps.delete') }}'
                } else if (type == 'tape') {
                    del_url = '{{ route('ajax.led_tapes.delete') }}'
                }
            });

            $('#del_modal_btn').click(function() {
                if (del_id) {
                    console.log($(this).data('id_object'));
                    $.ajax({
                        url: del_url,
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr' + del_id_object).hide();
                            } else {
                                showErrorModal('Ошибка при удалении устройства освещения');
                            }
                        }
                    });
                }
            });

            $('#addIlluminationBtn').click(function() {
                $('#modal_add_illumination_init_btn').click();
            });
        });
    </script>
@endsection
