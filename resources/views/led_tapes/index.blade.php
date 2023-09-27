@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('led_tapes.breadcrumbs', ['title' => 'Led ленты'])
@endsection

@section('content')
    <div class="container-fluid">
        @include('led_tapes.header')
        <div class="card">
            <div class="card-title"><h4>Led ленты</h4></div>
            <div class="card-body">
                @if(count($ledTapes))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Состояние</th>
                                <th>Цвет</th>
                                <th>Яркость</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ledTapes as $ledTape)
                                <tr id="tr{{$ledTape->id}}">
                                    <td scope="row">{{ $ledTape->id }}</td>
                                    <td> {{ $ledTape->name }} </td>
                                    <td>{{ $ledTape->type }}</td>
                                    <td>
                                        @if( $ledTape->status  === 'on')
                                            <span class="badge badge-success">Вкл</span>
                                        @else
                                            <span class="badge badge-danger">Выкл</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if( $ledTape->type  != 'W')
                                            <div style="width: 50px; height: 25px; background-color: hsl({{ $ledTape->h }}, {{ $ledTape->hsvToHsl()['s'] }}%, {{ $ledTape->hsvToHsl()['l'] }}%)"></div>
                                        @endif
                                    </td>
                                    <td>{{ $ledTape->w ? $ledTape->w . ' %' : '' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('led_tapes.edit',[$ledTape->id]) }}"
                                            class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn"
                                                data-id="{{ $ledTape->id }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Состояние</th>
                                <th>Цвет</th>
                                <th>Яркость</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $ledTapes->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $ledTapes->total() }}</p>
                @else
                    <p>Данные не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
<script>
        const deleteUrl = '{{ route('ajax.led_tapes.delete') }}';

        $(document).ready(function(){
            let del_id;
            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить led ленту № ' + $(this).data('id') + ' ?');
                $('#del_init_btn').click();
            });
            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: deleteUrl,
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении led ленты');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
