@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Настройки'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @can('settings.create')
                            <button type="button" class="btn btn-success m-b-10 m-l-5" id="addPageBtn">Добавить параметр
                            </button>
                        @endcan
                        <a href="{{ route('settings.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Параметры</h4></div>
            <div class="card-body">
                @if(count($settings))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Значение</th>
                                    <th>Описание</th>
                                    <th style="width: 60px;"></th>
                                    @can('settings.delete')
                                        <th style="width: 60px;"></th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($settings as $setting)
                                    <tr id="tr{{$setting->id}}">
                                        <td><a href="{{ route($setting->name == 'time_zone' ? 'time_zone.edit' : 'settings.edit', [$setting->id]) }}">{{ $setting->name }}</a></td>
                                        <td>{{ $setting->value }}</td>
                                        <td>{{ $setting->comment }}</td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route($setting->name == 'time_zone' ? 'time_zone.edit' : 'settings.edit', [$setting->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        @can('settings.delete')
                                            <td align="center" class="text-center">
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                        data-id="{{ $setting->id }}" data-name="{{ $setting->name }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Название</th>
                                    <th>Значение</th>
                                    <th>Описание</th>
                                    <th style="width: 60px;"></th>
                                    @can('settings.delete')
                                        <th style="width: 60px;"></th>
                                    @endcan
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $settings->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $settings->total() }}</p>
                @else
                    <p>Настройки не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
    @include('settings.create_modal')
@endsection

@section('scripts')
    @can('settings.delete')
        <script>
            let url = '{{ route('settings.index') }}';

            $(document).ready(function(){
                let del_id;

                $('.del_btn').click(function() {
                    del_id = $(this).data('id');
                    $('#del_modal_body').text('Удалить параметр «'+$(this).data('name')+'»?');
                    $('#del_init_btn').click();
                });

                $('#del_modal_btn').click(function(){
                    if (del_id) {
                        $.ajax({
                            url: '{{ route('ajax.settings.delete') }}',
                            data: { '_token': _token, 'id': del_id },
                            success: function (data) {
                                if (data.result) {
                                    $('#tr'+del_id).hide();
                                } else {
                                    showErrorModal('Ошибка при удалении параметра');
                                }
                            }
                        });
                    }
                });

                $('#addPageBtn').click(function() {
                    $('#modalPage #modal_groups_div').show();
                    $('#modalPage #namePage').val('');
                    $('#modal_page_init_btn').click();
                });
            });
        </script>
    @endcan
@endsection
