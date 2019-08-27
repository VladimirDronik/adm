@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Устройства'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('devices.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить устройство</a>
                        <a href="{{ route('devices.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <h4>Доступные устройства</h4>
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
                                <th>ip адрес</th>
                                <th>Статус</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($devices as $device)
                                <tr id="tr{{$device->id}}">
                                    <th scope="row">{{ $device->id }}</th>
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
                                    <td>
                                        <a href="{{ route('devices.edit',[$device->id]) }}" type="button" class="btn btn-info btn-sm btn-rounded m-b-10 m-l-5">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td align="center" class="text-center">
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
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
                                    <th>ip адрес</th>
                                    <th>Статус</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
                {{ $devices->appends(request()->input())->links() }}
                <p class="text-right">Найдено: {{ $devices->total() }}</p>
                @else
                    <p>Устройства не найдены</p>
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
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить устройство № '+$(this).attr('data-id')+' «'+$(this).attr('data-name')+'»?');
                $('#del_modal').modal('show');
            });

            $('#del_modal_btn').click(function(){
                $('#del_modal').modal('hide');
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.devices.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении устройства');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection

