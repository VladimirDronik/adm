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
                        <a href="{{ route('yandexstations.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        <button id="sync_devices" class="btn btn-success m-b-10 m-l-5">Синхронизировать устройства</button>
                        @if(file_exists(base_path(config('yandex.cookie_file'))) || file_exists(base_path(config('yandex.token_file'))))
                            <a href="{{ route('yandexstations.reset_user') }}" class="btn btn-danger m-b-10 m-l-5">Отвязать текущего пользователя</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-info alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                {{ session('success') }}
            </div>
        @endif
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
    @include('yandexstations.modals.auth_modal')
    @include('yandexstations.modals.auth_methods_modal')
    @include('yandexstations.modals.qr_auth_modal')
    @include('yandexstations.modals.repeat_qr_auth_modal')
    @include('components.load_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('yandexstations.index') }}';
        let yandex_auth_url = '{{ route('ajax.yandexstations.auth') }}';
        let yandex_sync_stations_url = '{{ route('ajax.yandexstations.sync_stations') }}';
        let yandex_get_qr_url = '{{ route('ajax.yandexstations.get_qr') }}';
        let yandex_login_qr_url = '{{ route('ajax.yandexstations.login_qr') }}';

        function syncYandexStations() {
            $('#load_init_btn').click();
            $('#content1_modal_body').text('Подождите, идет синхронизация...');
            $.ajax({
                url: yandex_sync_stations_url,
                data: { '_token': _token },
                success: function (data) {
                    $('#dismiss_load_modal').click();
                    if (data.code == 200) {
                        location.reload();
                    } else if (data.code == 401) {
                        $('#auth_methods_modal_init_btn').click();
                    } else {
                        showErrorModal('Ошибка синхронизации устройств. Повторите попытку или обратитесь к администратору');
                    }
                },
                error: function () {
                    $('#dismiss_load_modal').click();
                    showErrorModal('Сервер временно недоступен');
                }
            });
        }

        function loginYandexQr() {
            $('#load_init_btn').click();
            $('#content1_modal_body').text('Проверка авторизации QR-кода...');
            $.ajax({
                url: yandex_login_qr_url,
                data: { '_token': _token},
                success: function (data) {
                    $('#dismiss_load_modal').click();
                    if (data.code == 200) {
                        syncYandexStations()
                    } else if (data.code == 401) {
                        $('#repeat_qr_auth_modal_init_btn').click();
                    } else {
                        showErrorModal(data.message);
                    }
                },
                error: function () {
                    $('#dismiss_load_modal').click();
                    showErrorModal('Сервер временно недоступен');
                }
            });
        }

        function checkInput() {
            var inputLogin = $('#yaLogin').val();
            var inputPassword = $('#yaPassword').val();

            if (inputLogin === '' || inputPassword === '') {
                $('#send_yandex_auth').prop('disabled', true);
            } else {
                $('#send_yandex_auth').prop('disabled', false);
            }
        }

        $(document).ready(function(){
            checkInput()

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

            $('#sync_devices').click(function() {
                syncYandexStations()
            });

            $('#qr_yandex_auth').click(function() {
                $('#load_init_btn').click();
                $('#content1_modal_body').text('Генерируем QR-код...');
                $.ajax({
                    url: yandex_get_qr_url,
                    data: { '_token': _token},
                    success: function (data) {
                        $('#dismiss_load_modal').click();
                        if (data.code == 200) {
                            $("#qr_code_image").attr("src", data.qr_url);
                            $("#repeat_qr_code_image").attr("src", data.qr_url);
                            $('#qr_auth_modal_init_btn').click();
                        } else {
                            showErrorModal(data.message);
                        }
                    },
                    error: function () {
                        $('#dismiss_load_modal').click();
                        showErrorModal('Сервер временно недоступен');
                    }
                });
            });

            $('#send_yandex_qr_auth').click(function() {
                loginYandexQr()
            });

            $('#repeat_send_yandex_qr_auth').click(function() {
                loginYandexQr()
            });

            $('#password_yandex_auth').click(function() {
                $('#password_auth_modal_init_btn').click();
            });

            $('#yaLogin').on('input', function() {
                checkInput();
            });

            $('#yaPassword').on('input', function() {
                checkInput();
            });

            $('#send_yandex_auth').click(function() {
                $('#load_init_btn').click();
                $('#content1_modal_body').text('Авторизация...');
                let ya_login = $('#yaLogin').val();
                let ya_password = $('#yaPassword').val();
                $.ajax({
                    url: yandex_auth_url,
                    data: { '_token': _token, 'login': ya_login, 'password': ya_password },
                    success: function (data) {
                        $('#dismiss_load_modal').click();
                        if (data.code == 200) {
                            syncYandexStations()
                        } else {
                            showErrorModal(data.message);
                        }
                    },
                    error: function () {
                        $('#dismiss_load_modal').click();
                        showErrorModal('Сервер временно недоступен');
                    }
                });
            });
        });
    </script>
@endsection
