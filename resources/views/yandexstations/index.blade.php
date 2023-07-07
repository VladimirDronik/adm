@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Устройства: ЯндекСтанции</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item active">ЯндекСтанции</li>
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
                        <a href="{{ route('yandexstations.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить устройство</a>
                        <a href="{{ route('yandexstations.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        <a href="{{ route('yandexstations.editcookies') }}" class="btn btn-success m-b-10 m-l-5">Редактировать файл Cookies</a>
                        <a href="{{ route('yandexstations.yandex_auth') }}" class="btn btn-success m-b-10 m-l-5">Авторизация</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>ЯндексСтанции</h4></div>
            <div class="card-body">
                @if(count($yandexstations))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Помещение</th>
                                    <th>ID станции</th>
                                    <th>Громкость</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($yandexstations as $station)
                                    <tr id="tr{{$station->id}}">
                                        <td scope="row">{{ $station->id }}</td>
                                        <td><a href="{{ route('yandexstations.edit', [$station->id]) }}">{{ $station->name }}</a></td>
                                        <td>{{ optional($station->iroom)->name }}</td>
                                        <th>{{ $station->speaker_id }}</th>
                                        <th>{{ $station->volume }} %</th>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('yandexstations.edit', [$station->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $station->id }}" data-name="{{ $station->name }}">
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
                                    <th>Помещение</th>
                                    <th>ID станции</th>
                                    <th>Громкость</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $yandexstations->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $yandexstations->total() }}</p>
                @else
                    <p>Станции не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('yandexstations.index') }}';

        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить станцию № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.yandexstations.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении станции');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
