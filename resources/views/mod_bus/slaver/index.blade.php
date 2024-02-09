@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Устройства'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.slavers.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить устройство</a>
                        <a href="{{ route('mod_bus.slavers.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        <div class="pull-right">
                            <form class="form-inline my-2 my-lg-0" method="get">
                                <label class="control-label text-right col-md-2 label-fix" for="bus">Шина:</label>
                                <select class="form-control form-control-lg" autocomplete="off" name="bus" style="font-size: 1rem;">
                                    <option value="" @if(!$filterBus) selected @endif>Не выбрано</option>
                                    @foreach($buses as $id => $bus)
                                        <option value="{{ $id }}" @if($filterBus == $id) selected @endif>{{ $bus }}</option>
                                    @endforeach
                                </select>
                                <button class="form-control btn btn-primary m-l-4 p-l-23 p-r-23 my-2 my-sm-0" type="submit">Найти</button>
                                <a href="{{ route('mod_bus.slavers.index') }}" class="form-control btn btn-default m-l-6 my-2 my-sm-0">Сбросить</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Устройства</h4></div>
            <div class="tab-content">
                @if($slavers->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Адрес</th>
                                    <th>Шина</th>
                                    <th>Статус</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($slavers as $slaver)
                                <tr id="tr{{$slaver->id}}">
                                    <td scope="row">{{ $slaver->id }}</td>
                                    <td>
                                        {{ $slaver->name }}
                                    </td>
                                    <td>
                                        {{ $slaver->relatedType->name }}
                                    </td>
                                    <td>
                                        {{ $slaver->address }}
                                    </td>
                                    <td>
                                        {{ $slaver->relatedBus->device }}
                                    </td>
                                    <td>
                                        {{ $slaver->active ? 'Доступно' : 'Недоступно' }}
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('mod_bus.slavers.edit', [$slaver->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id="{{ $slaver->id }}" data-name="{{ $slaver->name }}" data-type="{{ $slaver->relatedType->type }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @if(count($slavers) > 10)
                            <tfoot>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Адрес</th>
                                    <th>Шина</th>
                                    <th>Статус</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                    <br>
                    {{ $slavers->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $slavers->total() }}</p>
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

                $('#del_modal_title').text('Вы точно хотите удалить устройство '+ del_name +' ?');
                if ($(this).attr('data-type') == 'ecodim-dali-gw2') {
                    $('#del_modal_body').text('При удалении устройства будут удалены все его регистры и связанные объекты !');
                } else {
                    $('#del_modal_body').text('При удалении устройства, удалятся все связанные регистры !');
                }
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function () {
                if (del_id) {
                    $.ajax({
                    url: '{{ route('ajax.mod_bus.slavers.delete') }}',
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
