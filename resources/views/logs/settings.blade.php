@extends('layouts._layout')

@section('breadcrumbs')
    @include('components.breadcrumbs', ['title' => 'Логирование/Настройки'])
@endsection

@section('css')
    <link href="{{ asset('ela/css/lib/datepicker/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('logs.index') }}" class="btn btn-success m-b-10 m-l-5">Назад к логам</a>
                        <a href="{{ route('logs.settings') }}" class="btn btn-success m-b-10 m-l-5">Настройки</a>
                        &nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-left" style="width: 20px;">Включено</th>
                                    <th style="width: 180px;">Название</th>
                                    <th class="text-left">Описание</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($settings as $setting)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="active_checkbox" style="cursor: pointer;"
                                                   data-id="{{$setting->id}}" value="1" @if($setting->value) checked @endif>
                                        </td>
                                        <td class="text-left">{{$setting->point}}</td>
                                        <td class="text-left">{{$setting->description}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>

    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('ela/js/lib/datepicker/datepicker-ru.min.js') }}"></script>
    <script src="{{ asset('ela/js/lib/moment/moment.js') }}"></script>


    <script>
        $('.active_checkbox').change(function(){
            let active = this.checked ? 1 : 0;
            let view_id = $(this).attr('data-id');

            $.ajax({
                url: '{{ route('ajax.logs.active') }}',
                data: { '_token': _token, 'id': view_id, 'active': active},
                success: function (data) {
                    if (data.result) {
                        showSuccessModal('Активность успешно изменена');
                    } else {
                        showErrorModal('Ошибка при изменении активности');
                    }
                },
            });
        });
    </script>
@endsection
