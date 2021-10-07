@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Датчики: гигростаты</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Датчики</li>
                <li class="breadcrumb-item active">Гигростаты</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @include('detectors.tab_header', ['active' => 'hygrostats'])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('hygrostats.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить гигростат</a>
                        <a href="{{ route('hygrostats.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Гигростаты</h4></div>
            <div class="card-body">
                @if(count($hygrostats))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Текущая влажн.</th>
                                    <th>Оптим. влажн.</th>
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
                                @foreach($hygrostats as $hygrostat)
                                    <tr id="tr{{$hygrostat->id}}">
                                        <td scope="row">{{ $hygrostat->iobject['id'] }}</td>
                                        <td><a href="{{ route('hygrostats.edit',[$hygrostat->id]) }}">
                                                {{ $hygrostat->name }}</a></td>
                                        <td>{{ $hygrostat->current }} %</td>
                                        <td>{{ $hygrostat->optimal }} %</td>
                                        <td>{{ $hygrostat->gisteresis }}</td>
                                        <td>{{ $hygrostat->rus_hygrostat }}</td>
                                        @can('devices.show-object')
                                            <td>
                                                @if($hygrostat->object)
                                                    <a href="{{ route('objects.edit',[$hygrostat->object]) }}" target="_blank">{{ optional($hygrostat->eobject)->name }}</a>
                                                @endif
                                            </td>
                                        @endcan
                                        <td align="center" class="text-center">
                                            <a href="{{ route('hygrostats.edit',[$hygrostat->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $hygrostat->id }}" data-name="{{ $hygrostat->name }}">
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
                                    <th>Текущая влажн.</th>
                                    <th>Оптим. влажн.</th>
                                    <th>Гистерезис</th>
                                    <th>Режим</th>
                                    @can('devices.show-object')
                                        <th>Объект влияния</th>
                                    @endcan
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $hygrostats->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $hygrostats->total() }}</p>
                @else
                    <p>Гигростаты не найдены</p>
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
                $('#del_modal_body').text('Удалить термостат № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.hygrostats.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении термостата');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
