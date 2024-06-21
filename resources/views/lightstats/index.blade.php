@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Датчики: датчик освещенности</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item breadcrumb-item-no-link"><a href="{{ route('lightstats.index') }}">Датчики</a></li>
                <li class="breadcrumb-item active">Датчики освещенности</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @include('detectors.tab_header', ['active' => 'lightstats'])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('lightstats.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить датчик освещенности</a>
                        <a href="{{ route('lightstats.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Датчики освещенности</h4></div>
            <div class="card-body">
                @if(count($lightstats))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Текущая осв.</th>
                                    <th>Оптим. осв.</th>
                                    <th>Гистерезис</th>
                                    <th>Режим</th>
                                    @can('devices.show-object')
                                        <th>Объект влияния</th>
                                    @endcan
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lightstats as $lightstat)
                                    <tr id="tr{{$lightstat->id}}">
                                        <td scope="row">{{ $lightstat->id_object }}</td>
                                        <td>
                                            <a href="{{ route('lightstats.edit',[$lightstat->id]) }}">{{ $lightstat->name }}</a>
                                        </td>
                                        <td>{{ $lightstat->current }}</td>
                                        <td>{{ $lightstat->optimal }}</td>
                                        <td>{{ $lightstat->gisteresis }}</td>
                                        <td>{{ $lightstat->rus_lightstat }}</td>
                                        @can('devices.show-object')
                                            <td>
                                                @if($lightstat->object)
                                                    {{ $lightstat->eobject?->name }}
                                                @endif
                                            </td>
                                        @endcan
                                        <td align="center" class="text-center">
                                            <a href="{{ route('lightstats.edit', [$lightstat->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            @if(!$lightstat->is_system)
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                        data-id="{{ $lightstat->id }}" data-name="{{ $lightstat->name }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if(count($lightstats) > 10)
                                <tfoot>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Название</th>
                                        <th>Текущая темп.</th>
                                        <th>Оптим. темп.</th>
                                        <th>Гистерезис</th>
                                        <th>Режим</th>
                                        @can('devices.show-object')
                                            <th>Объект влияния</th>
                                        @endcan
                                        <th style="width: 60px;"></th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $lightstats->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $lightstats->total() }}</p>
                @else
                    <p>Датчики освещенности не найдены</p>
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
                $('#del_modal_body').text('Удалить датчик освещенности № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.lightstats.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении датчика освещенности');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
