@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Настройки пользователей'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @can('settings.create')
                            <a href="{{ route('users.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить пользователя</a>
                        @endcan
                        <a href="{{ route('users.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Параметры</h4></div>
            <div class="card-body">
                @if(count($devusers))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th>Telegram</th>
                                    <th>PUSH</th>
                                    <th>Phone</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($devusers as $devuser)
                                    <tr id="tr{{$devuser->id}}">
                                        <td><a href="{{ route('users.edit', [$devuser->id]) }}">{{ $devuser->dev_id }}</a></td>
                                        <td>{{ $devuser->name }}</td>
                                        <td>@if($devuser->telegram_send == 1) <span class="badge badge-danger">важные</span> @elseif($devuser->telegram_send == 2) <span class="badge badge-info">обычные</span> @else <span class="badge badge-secondary">не назначено</span> @endif </td>
                                        <td>@if($devuser->push_send == 1) <span class="badge badge-danger">важные</span> @elseif($devuser->push_send == 2) <span class="badge badge-info">обычные</span> @else <span class="badge badge-secondary">не назначено</span> @endif </td>
                                        <td>@if($devuser->sms_send == 1) <span class="badge badge-danger">важные</span> @elseif($devuser->sms_send == 2) <span class="badge badge-info">обычные</span> @else <span class="badge badge-secondary">не назначено</span> @endif </td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('users.edit', [$devuser->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>

                                            <td align="center" class="text-center">
                                                <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                        data-id="{{ $devuser->id }}" data-name="{{ $devuser->dev_id }}">
                                                    <i class="fa fa-trash fa-lg"></i>
                                                </button>
                                            </td>

                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Telegram</th>
                                    <th>PUSH</th>
                                    <th>Phone</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>

                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $devusers->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $devusers->total() }}</p>
                @else
                    <p>Настройки не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
@endsection

@section('scripts')

        <script>
            let url = '{{ route('users.index') }}';

            $(document).ready(function(){
                let del_id;

                $('.del_btn').click(function() {
                    del_id = $(this).data('id');
                    $('#del_modal_body').text('Удалить пользователя «'+$(this).data('name')+'»?');
                    $('#del_init_btn').click();
                });

                $('#del_modal_btn').click(function(){
                    if (del_id) {
                        $.ajax({
                            url: '{{ route('ajax.users.delete') }}',
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
            });
        </script>
@endsection
