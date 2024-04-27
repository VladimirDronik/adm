@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">I2C датчики</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item active">I2C датчики</li>
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
                        <a href="{{ route('usensors.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить I2C датчик</a>
                        <a href="{{ route('usensors.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>I2C датчики</h4></div>
            <div class="card-body">
                @if(count($usensors))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Тип датчика</th>
                                    <th>Контроллер</th>
                                    <th>SDA</th>
                                    <th>SCL</th>
                                    <th>Помещение</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usensors as $usensor)
                                    <tr id="tr{{$usensor->id}}">
                                        <td scope="row">{{ $usensor->id_object }}</td>
                                        <td>
                                            <a href="{{ route('usensors.edit', [$usensor->id]) }}">{{ $usensor->name }}</a>
                                        </td>
                                        <td>{{ $usensor->type_name }}</td>
                                        <td>{{ $usensor->device?->description }}</td>
                                        <td>{{ $usensor->port_SDA }}</td>
                                        <td>{{ $usensor->port_SCL }}</td>
                                        <td>{{ $usensor->relatedRoom?->name }}</td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('usensors.edit',[$usensor->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $usensor->id }}" data-name="{{ $usensor->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if(count($usensors) > 10)
                                <tfoot>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Название</th>
                                        <th>Тип датчика</th>
                                        <th>Контроллер</th>
                                        <th>SDA</th>
                                        <th>SCL</th>
                                        <th>Помещение</th>
                                        <th style="width: 60px;"></th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $usensors->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $usensors->total() }}</p>
                @else
                    <p>I2C датчики не найдены</p>
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
                $('#del_modal_body').text('Удалить I2C датчик № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function() {
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.usensors.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении унивестального датчика');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
