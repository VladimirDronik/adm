@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Устройства: виртуальное</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item active">Виртуальное</li>
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
                        @if(user()->is_admin)
                        <a href="{{ route('virtuals.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить устройство</a>
                        @endif
                        <a href="{{ route('virtuals.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Виртуальное устройство</h4></div>
            <div class="card-body">
                @if(count($virtuals))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    @can('devices.show-object')
                                        <th>Объект</th>
                                    @endcan
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($virtuals as $virtual)
                                    <tr id="tr{{$virtual->id}}">
                                        <td scope="row">{{ $virtual->object['id'] }}</td>
                                        <td><a href="{{ route('virtuals.edit', [$virtual->id]) }}">{{ $virtual->name }}</a></td>
                                        @can('devices.show-object')
                                            <td>@if($virtual->object)
                                                    <a href="{{ route('objects.edit', [$virtual->id_object]) }}">{{ optional($virtual->object)->name }}</a>
                                                @else
                                                    Не указан
                                                @endif
                                            </td>
                                        @endcan
                                        <td align="center" class="text-center">
                                            <a href="{{ route('virtuals.edit', [$virtual->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        @if(user()->is_admin)
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $virtual->id }}" data-name="{{ $virtual->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    @can('devices.show-object')
                                        <th>Объект</th>
                                    @endcan
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $virtuals->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $virtuals->total() }}</p>
                @else
                    <p>Реле не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('virtuals.index') }}';

        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить виртуальное устройство № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.virtuals.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении виртуального устройства');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
