@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Устройства: выключатели</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item active">Выключатели</li>
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
                        <a href="{{ route('switches.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить выключатель</a>
                        <a href="{{ route('switches.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Выключатели</h4></div>
            <div class="card-body">
                @if(count($switches))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Тип</th>
                                    <th>Название</th>
                                    <th style="width: 200px;">Одиночное нажатие</th>
                                    <th style="width: 200px;">Двойное нажатие</th>
                                    <th style="width: 200px;">Длительное нажатие</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($switches as $switch)
                                    <tr id="tr{{$switch->id}}">
                                        <td scope="row">{{ $switch->object['id'] }}</td>
                                        <td>
                                            {{ $switch->rus_type }}
                                        </td>
                                        <td><a href="{{ route('switches.edit', [$switch->id]) }}">{{ $switch->name }}</a></td>
                                        <td>
                                            @if($switch->port && $switch->port->emethod)
                                                Объект:&nbsp;{{ $switch->port->emethod->eobject->name }}
                                                <br>
                                                Метод:&nbsp;{{ $switch->port->emethod->name }}
                                            @else
                                                Метод не указан
                                            @endif
                                        </td>
                                        @if($switch->type == 'button')
                                            <td>
                                                @if($switch->port && $switch->port->dcmethod)
                                                    Объект:&nbsp;{{ $switch->port->dcmethod->eobject->name }}
                                                    <br>
                                                    Метод:&nbsp;{{ $switch->port->dcmethod->name }}
                                                @else
                                                    Метод не указан
                                                @endif
                                            </td>
                                            <td>
                                                @if($switch->port && $switch->port->lcmethod)
                                                    Объект:&nbsp;{{ $switch->port->lcmethod->eobject->name }}
                                                    <br>
                                                    Метод:&nbsp;{{ $switch->port->lcmethod->name }}
                                                @else
                                                    Метод не указан
                                                @endif
                                            </td>
                                        @else
                                            <td></td>
                                            <td></td>
                                        @endif
                                        <td align="center" class="text-center">
                                            <a href="{{ route('switches.edit', [$switch->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $switch->id }}" data-name="{{ $switch->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Тип</th>
                                    <th>Название</th>
                                    <th style="width: 200px;">Одиночное нажатие</th>
                                    <th style="width: 200px;">Двойное нажатие</th>
                                    <th style="width: 200px;">Длительное нажатие</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $switches->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $switches->total() }}</p>
                @else
                    <p>Выключатели не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('switches.index') }}';

        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить выключатель № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.switches.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении выключателя');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
