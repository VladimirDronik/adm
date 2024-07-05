@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Регистры'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.registers.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить регистр</a>
                        <a href="{{ route('mod_bus.registers.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        <div class="pull-right">
                            <form class="form-inline" method="get">
                                Устройство:&nbsp;&nbsp;
                                <select class="form-control form-control-lg" autocomplete="off" name="slaver" style="font-size: 1rem;">
                                    <option value="" @if(!$filterSlaver) selected @endif>Не выбрано</option>
                                    @foreach($slavers as $id => $name)
                                        <option value="{{ $id }}" @if($filterSlaver == $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <button class="form-control btn btn-primary m-l-4 p-l-11 p-r-11 my-2 my-sm-0" type="submit">Применить</button>
                                <a href="{{ route('mod_bus.registers.index') }}" class="form-control btn btn-default m-l-6 my-2 my-sm-0">Сбросить</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Регистры</h4></div>
            <div class="tab-content">
                @if($registers->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Устройство</th>
                                    <th>Название</th>
                                    <th>Значение</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registers as $register)
                                <tr id="tr{{$register->id}}">
                                    <td scope="row">{{ $register->id }}</td>
                                    <td>
                                        {{ $register->slaver->name }}
                                    </td>
                                    <td>
                                        {{ $register->name }}
                                    </td>
                                    <td>
                                        {{ $register->last_value ? $register->last_value . ' ' . $register->units : '' }}
                                    </td>
                                    <td>
                                        @if($register->comment)
                                            <img src="{{ asset('ela/images/info.png') }}" width="23" height="23" title="{{ $register->comment }}"></img>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('mod_bus.registers.edit', [$register->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id="{{ $register->id }}" data-name="{{ $register->name }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if(count($registers) > 10)
                            <tfoot>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Устройство</th>
                                    <th>Название</th>
                                    <th>Значение</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                    <br>
                    {{ $registers->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $registers->total() }}</p>
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
        $(document).ready(function () {
            $('.del_btn').click(function () {
                del_id = $(this).attr('data-id');
                del_name = $(this).attr('data-name');
                $('#del_modal_title').text('Удаление регистра');
                $('#del_modal_body').text('Вы точно хотите удалить регистр '+ del_name +' ?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function () {
                if (del_id) {
                    $.ajax({
                    url: '{{ route('ajax.mod_bus.registers.delete') }}',
                    data: {'_token': _token, 'id': del_id},
                        success: function (data) {
                            if (data.result) {
                                $('#tr' + del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
