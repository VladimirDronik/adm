@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Устройства: контроллеры</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item active">Контроллеры</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('devices.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить контроллер</a>
                        <a href="{{ route('devices.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        @if(auth()->user()->getIsSuperAdminAttribute())
                            <!-- <a href="{{ route('devices.sendallconfigs') }}" class="btn btn-success m-b-10 m-l-5"   title="Отправить изменения на все контролеры">
                                <i class="fa fa-upload"></i> Отправить изменения</a> -->
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <h4>{{$errors->first()}}</h4>
            </div>
        @endif

        <div class="card">
            <div class="card-title">
                <h4>Доступные контроллеры</h4>
            </div>
            <div class="card-body">
                @if(count($devices))
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>IP адрес</th>
                                <th class="text-center">Статус</th>
                                <th></th>
                                <th style="width: 60px;"></th>
                                <th style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                                <tr id="tr{{$device->id}}">
                                    <td>{{ $device->id }}</td>
                                    <td>{{ $device->description }}</td>
                                    <td>{{ optional($device->devtype)->name }}</td>
                                    <td>{{ $device->ip_address }}</td>
                                    <td class="text-center">
                                        @if( $device->active  === 1)
                                            <span class="badge badge-success">Активно</span>
                                        @else
                                            <span class="badge badge-danger">Недоступно</span>
                                        @endif
                                    </td>
                                    <td> @if($device->changed == 1 && auth()->user()->getIsSuperAdminAttribute())
                                            <!-- <a href="{{ route('devices.sendconfig', [$device->id]) }}" class="btn btn-success btn-sm btn-rounded" title="Отправить конфиг на устройство">
                                                <i class="fa fa-upload"></i></a> -->
                                        @endif

                                    </td>
                                    <td>
                                        <a href="{{ route('devices.edit', [$device->id]) }}" class="btn btn-info btn-sm btn-rounded"  title="Редактировать">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td align="center" class="text-center">
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"   title="Удалить"
                                                data-id="{{ $device->id }}" data-name="{{ $device->description }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        @if(count($devices) > 10)
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>IP адрес</th>
                                    <th class="text-center">Статус</th>
                                    <th></th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
                {{ $devices->appends(request()->input())->links() }}
                <p class="text-right">Найдено: {{ $devices->total() }}</p>
                @else
                    <p>Контроллеры не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить контроллер «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.devices.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении контроллера');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection

