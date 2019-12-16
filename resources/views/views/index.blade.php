@extends('layouts._layout')

@section('breadcrumbs')
    @if ($filter_room == '')
        @include('components.breadcrumbs', ['title' => 'Отображения'])
    @else
        @includeIf('components.breadcrumbs',
           ['title' => 'Отображения',
            'links' => [ route('views.index') => 'Отображения'],
            'last_link' => $filter_room_name])
    @endif
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('views.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить
                            отображение</a>
                        <a href="{{ route('views.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="pull-right">
                            <div class="dropdown room-filter" id="room-filter">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    @if($filter_room == '')
                                        Фильтр по помещению
                                    @else
                                        Фильтр по помещению: {{$filter_room_name ?? ''}}
                                        @php($filter_room = 'для помещения: '.($filter_room_name ?? ''))
                                    @endif
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('views.index') }}">Все помещения</a></li>
                                    <hr>
                                    <li>
                                        <a href="{{ route('views.index',['room' => 0]) }}">{{ \App\Models\Room::COMMON_NAME }}</a>
                                    </li>
                                    <hr>
                                    @foreach($rooms as $room)
                                        <li>
                                            <a href="{{ route('views.index',['room' => $room->id]) }}">
                                                <label style="background-color:{{ $room->style }}">&nbsp;&nbsp;&nbsp;</label>&nbsp;&nbsp;{{ $room->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <a href="{{ route('views.index') }}" class="btn btn-success m-b-10 m-l-5">Сбросить</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <h4>Элементы отображения {{$filter_room}}</h4>
            </div>
            <div class="card-body">
                @if(count($views))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    @if($filter_room != '')
                                        <th>Сорт</th>
                                    @endif
                                    <th>Тип</th>
                                    <th>Статус</th>
                                    <th>Изображение</th>
                                    <th>Надпись</th>
                                    <th>Объект</th>
                                    <th>Метод</th>
                                    <th>Помещение</th>
                                    <th>Сцена</th>
                                    <th>Активно</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($views as $view)
                                <tr id="tr{{$view->id}}">
                                    @if($filter_room != '')
                                        <td scope="row">{{ $view->sort }}</td>
                                    @endif
                                    <td><a href="{{ route('views.edit',[$view->id]) }}"
                                           title="{{ $view->description }}">{{ $view->rus_type }}</a></td>
                                    <td>
                                        @if(strtolower($view->status) === 'on')
                                            <span class="badge badge-success">{{ $view->status }}</span>
                                        @else
                                            <span class="badge badge-primary">{{ $view->status }}</span>
                                        @endif
                                    </td>
                                    <td scope="row">
                                        @if(!empty($view->icon))
                                            <img src="{{ asset($view->icon_path) }}" width="25" height="25"
                                                 style="fill: green; background-color: #e8e8e8;">
                                        @endif
                                    </td>
                                    <td scope="row">{{ $view->short_title }}</td>
                                    <td>
                                        @if($view->eobject)
                                            <button type="button" class="btn btn-warning m-b-10 btn-sm"
                                                    name="object" id="viewobj_{{ $view->id }}"
                                                    data-toggle="modal" data-target="#objectsModal"
                                                    value="{{ $view->id_object}},{{optional($view->eobject)->name}},viewobj_{{ $view->id }}">
                                                <b>{{ optional($view->eobject)->name }}</b>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-default m-b-10 btn-sm"
                                                    name="object" id="viewobjempty_{{ $view->id }}"
                                                    data-toggle="modal" data-target="#objectsModal"
                                                    value="empty,empty,viewobjempty_{{ $view->id }}">
                                                Отсутствует
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        @if($view->eobject && $view->emethod)
                                            <button type="button" id="viewmethod_{{ $view->id }}"
                                                    name="method" class="btn btn-warning m-b-10 btn-sm"
                                                    data-toggle="modal"
                                                    value="{{ $view->id_method}},{{optional($view->emethod)->name}},viewmethod_{{ $view->id }}"
                                                    data-target="#methodsModal"
                                                    onclick="updateRedirectToCreateMethodBtn(this)">
                                                <b>{{ optional($view->emethod)->name }}</b>
                                            </button>
                                        @else
                                            <button type="button" id="viewmethodempty_{{ $view->id }}"
                                                    name="method"
                                                    class="btn @if($view->eobject) btn-warning @else btn-default @endif m-b-10 btn-sm"
                                                    data-toggle="modal"
                                                    value="empty,empty,viewmethodempty_{{ $view->id }}"
                                                    data-target="#methodsModal"
                                                    onclick="updateRedirectToCreateMethodBtn(this)">
                                                @if($view->eobject) <b class="text-danger">Метод не выбран</b> @else
                                                    Отсутствует @endif</button>
                                        @endif
                                    </td>
                                    <td scope="row">{{ $view->room_name }}</td>
                                    <td scope="row">
                                        @if($view->scene)
                                            <a href="{{ route('scenes.edit',$view->scene) }}">{{ optional($view->escene)->label }}</a>
                                        @endif
                                    </td>
                                    <td scope="row" align="center">
                                        <input type="checkbox" class="active_checkbox" style="cursor: pointer;"
                                               data-id="{{$view->id}}" value="1" @if($view->active) checked @endif>
                                    </td>
                                    <td align="center">
                                        <a href="{{ route('views.edit',[$view->id]) }}"
                                           class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                data-id="{{ $view->id }}" data-name="{{ $view->rus_type_name }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            @if(count($views) > 10)
                                <tfoot>
                                    <tr>
                                        @if($filter_room != '')
                                            <th>Сорт</th>
                                        @endif
                                        <th>Тип</th>
                                        <th>Статус</th>
                                        <th>Изображение</th>
                                        <th>Надпись</th>
                                        <th>Объект</th>
                                        <th>Метод</th>
                                        <th>Помещение</th>
                                        <th>Сцена</th>
                                        <th>Активно</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $views->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $views->total() }}</p>
                @else
                    <p>Отображения не найдены</p>
                @endif
            </div>
        </div>
    </div>

    <div id="objectsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Выбор объекта</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <label id="selected_object"></label><br>
                    </div>
                    <div>
                        <input type="text" name="modal_objects_filter" class="form-control"
                               placeholder="Поиск по названию...">
                    </div>
                    <div id="objectframe"></div>
                </div>
                <div class="modal-footer space-between">
                    <button type="button" onclick="redirectToCreateObject()" class="btn btn-outline-info btn-outline">
                        Создать объект
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="objectsModalCloseBtn">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div id="methodsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Выбор метода</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <label id="selected_method"></label><br>
                    </div>
                    <div id="methodframe"></div>
                </div>
                <div class="modal-footer space-between" id="methodModalFooter">
                    <button type="button" onclick="redirectToCreateMethod(this)" data-object-id="" id="methodRedirectBtn" class="btn btn-outline-info btn-outline">
                        Создать метод
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="methodsModalCloseBtn">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        const createObjectUrl = '{{ route('objects.create') }}';
        const createMethodInitUrl = '{{ route('objects.index') }}';

        function redirectToCreateObject() {
            $('#objectsModalCloseBtn').click();
            window.open(createObjectUrl, '_blank');
        }

        function redirectToCreateMethod(element) {
            $('#methodsModalCloseBtn').click();
            window.open(createMethodInitUrl + '/' + $(element).data('object-id') + '/edit', '_blank');
        }

        function updateRedirectToCreateMethodBtn(element) {
            const viewId = $(element).attr('id').split('_')[1];
            let objectBtn = $('#viewobj_'+viewId);
            if (objectBtn.length && objectBtn.text !== 'Отсутствует') {
                const objectId = objectBtn.attr('value').split(',')[0];
                if (objectId !== 'empty') {
                    $('#methodModalFooter').addClass('space-between');
                    $('#methodRedirectBtn').data('object-id', objectId).show();
                } else {
                    $('#methodRedirectBtn').hide();
                    $('#methodModalFooter').removeClass('space-between');
                }
            } else {
                objectBtn = $('#viewobjempty_'+viewId);
                if (objectBtn.length && objectBtn.text !== 'Отсутствует') {
                    const objectId = objectBtn.attr('value').split(',')[0];
                    if (objectId !== 'empty') {
                        $('#methodModalFooter').addClass('space-between');
                        $('#methodRedirectBtn').data('object-id', objectId).show();
                    } else {
                        $('#methodRedirectBtn').hide();
                        $('#methodModalFooter').removeClass('space-between');
                    }
                } else {
                    $('#methodRedirectBtn').hide();
                    $('#methodModalFooter').removeClass('space-between');
                }
            }
        }

        function resetObject(id, view) {
            //Внесение изменений в БД
            selectObject(null, null);

            $('#' + id).attr({"class": "btn btn-default  m-b-10 btn-sm"});
            $('#' + view).val('empty,empty,' + id);
            let mid = view.split('_')[1];
            $('#viewmethod_' + mid).html('<b>Метод не выбран</b>');
            $('#viewmethod_' + mid).val("empty,empty,viewmethod_" + mid);
            $('#viewmethodempty_' + mid).html('<b>Метод не выбран</b>');
            $('#viewmethodempty_' + mid).val("empty,empty,viewmethodempty_" + mid);
        }

        function resetMethod(id, view) {
            //Внесение изменений в БД
            selectMethod(null, null);

            $('#' + id).attr({"class": "btn btn-default  m-b-10 btn-sm"});
            $('#' + view).val('empty,empty,' + id);
        }

        $(document).ready(function () {
            let objects_url = '{{ route('ajax.view_objects.view.all') }}';
            let methods_url = '{{ route('ajax.view_objects.method.all') }}';
            let del_id;

            //Вызов модального окна с методами
            $('button[type=button][name=method]').click(function () {

                let method_val = this.value;
                let view_id = this.id;
                let method_arr = method_val.split(',');

                let data = {};
                data['method'] = method_val;
                data['id'] = view_id.split('_')[1];

                ajax_html(data, methods_url, '#methodframe');

                if (method_arr[0] != 'empty') {
                    $('#selected_method').html('Выбран метод: ' + method_arr[1] +
                        '   <button type="button" class="btn btn-danger m-b-2 btn-xs" data-dismiss="modal" ' +
                        'id = "reset_method"  value="' + view_id + '" onclick="resetMethod(\'' + view_id + '\',\'' + method_arr[2] + '\');">Убрать</button>');
                } else {
                    $('#selected_method').html('Метод не выбран');
                }
            });

            //

            //Вызов модального окна с объектами
            $('button[type=button][name=object]').click(function () {
                $('[name=modal_objects_filter]').val('');

                let object_val = this.value;
                let view_id = this.id;
                let object_arr = object_val.split(',');

                let data = {};
                data['object'] = object_val;

                ajax_html(data, objects_url, '#objectframe');

                if (object_arr[0] != 'empty') {
                    $('#selected_object').html('Выбран объект: ' + object_arr[1] +
                        '   <button type="button" class="btn btn-danger m-b-2 btn-xs" data-dismiss="modal" ' +
                        'id = "reset_object"  value="' + view_id + '" onclick="resetObject(\'' + view_id + '\',\'' + object_arr[2] + '\');">Убрать</button>');
                } else {
                    $('#selected_object').html('Объект не выбран');
                }
            });

            //

            $('.del_btn').click(function () {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить отображение «' + $(this).attr('data-name') + '»?');
                $('#del_modal').modal('show');
            });

            $('#del_modal_btn').click(function () {
                $('#del_modal').modal('hide');
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.views.delete') }}',
                        data: {'_token': _token, 'id': del_id},
                        success: function (data) {
                            if (data.result) {
                                $('#tr' + del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении отображения');
                            }
                        }
                    });
                }
            });

            //

            $('.active_checkbox').change(function () {
                let active = this.checked ? 1 : 0;
                let view_id = $(this).attr('data-id');

                $.ajax({
                    url: '{{ route('ajax.views.active') }}',
                    data: {'_token': _token, 'id': view_id, 'active': active},
                    success: function (data) {
                        if (data.result) {
                            showSuccessModal('Активность успешно изменена');
                        } else {
                            showErrorModal('Ошибка при изменении активности');
                        }
                    },
                });
            });

            $('[name=modal_objects_filter]').on('input', function () {
                const search = $(this).val().trim().toLowerCase();
                $(".modal_object_tr").show();
                if (search !== "") {
                    $(".modal_object_tr:not([data-name*='" + search + "'])").hide();
                }
            });
        });
    </script>
@endsection
