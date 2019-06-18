

@extends('layouts._layout')

@section('breadcrumbs')
        <!-- Bread crumb -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-primary">Устройства</h3> </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item"><a href="/devices">Устройства</a></li>
                    <li class="breadcrumb-item active">Выбранное устройство</li>
                </ol>
            </div>
        </div>
        <!-- End Bread crumb -->
@endsection

@section('content')

    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->

                    <div class="card">
                        <div class="card-body">
                            Название: <input name="description" id="descr_device" value="{{ $device->description}}" size="15">
                            ip адрес: <input name="ip_address" id="ip_device" value="{{ $device->ip_address }}" size="15">
                            <input type="hidden" id="id_device" value="{{ $device->id }}">

                            <button type="button" class="btn btn-success m-b-10 m-l-5" data-toggle="modal" data-target="#device-settings-Modal">Сохранить настройки</button>
                            <button type="button" class="btn btn-danger m-b-10 m-l-5"  data-toggle="modal" data-target="#delete-device-Modal">Удалить устройство</button>
                        </div>
                    </div>

        <div class="card">
            <div class="card-title">
                <h4>Порты устройства </h4>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Тип</th>
                            <th>Описание</th>
                            <th>Связанный объект</th>
                            <th>Действие</th>
                            <th align="center">Длит</th>
                            <th align="center">Двойн</th>
                            <th>Настройка</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($ports as $port)
                            <tr>
                                <th scope="row"> {{ $port->num_port }}</th>
                                <td>
                                    @if ($port->type=='out')
                                        <span class="badge badge-primary">{{ $port->type }}</span>
                                    @elseif ($port->type=='in')
                                        <span class="badge badge-success">{{ $port->type }}</span>

                                    @endif

                                </td>
                                <td><a href="#" data-toggle="modal" data-target="#name_modal" id="name_port_{{ $port->id }}" onclick="get_name_port('{{ $port->id }}', '{{$port->comment}}'); ">
                                        @if ($port->comment != '')
                                        {{ $port->comment }}
                                           @else
                                            Без названия
                                        @endif
                                    </a>
                                </td>
                                <td >
                                    @if ($port->nameobj)
                                        <button type="button" class="btn btn-warning  m-b-10 btn-sm" name="object" id="portobj_{{ $port->id }}"  data-toggle="modal" data-target="#objectsModal"  value="{{ $port->object}},{{$port->nameobj}},portobj_{{ $port->id }}"> <b>{{ $port->nameobj }}</b></button>
                                    @else
                                        <button type="button" class="btn btn-default  m-b-10 btn-sm" name="object" id="portobjempty_{{ $port->id }}"   data-toggle="modal" data-target="#objectsModal" value="empty,empty,portobjempty_{{ $port->id }}">Отсутствует</button>
                                    @endif
                                </td>
                                <td>
                                    @if ($port->easy)
                                        <button type="button"  id="method_btn_{{ $port->id }}" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('easy', {{ $port->id }}, '{{ $port->easy }}');"><b>Простое: {{ $port->easy }}</b></button>
                                    @elseif ($port->script)
                                        <button type="button"  id="method_btn_{{ $port->id }}" class="btn btn-info  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('script', {{ $port->id }}, '{{ $port->namescript }}');"><b>{{ $port->namescript }}</b></button>
                                    @elseif ($port->nameobj!='' && $port->type!='out')
                                        <button type="button" id="method_btn_{{ $port->id }}" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('method', {{ $port->id }}, '{{ $port->nameobj }}');"><b><< Выполнять действие объекта</b></button>
                                    @elseif ($port->type!='out')
                                        <button type="button" id="method_btn_{{ $port->id }}" class="btn btn-default  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('none', {{ $port->id }}, 'none');">Отсутсвует</button>
                                    @endif
                                </td>
                                @if ($port->type!='out')
                                    <td align="center"><input type="checkbox" value="$port->longclick"></td>
                                    <td align="center"><input type="checkbox" value="$port->doubleclick"></td>
                                @else
                                    <td></td>
                                    <td></td>
                                @endif

                                <td align="center"><button type="button" class="btn btn-info  m-b-10 btn-sm btn-rounded"><b>Настройка</b></button></td>
                            </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>





        <!-- End PAge Content -->
    </div>
    <!-- End Container fluid  -->




    <!-- HTML-код модального окна выбор объекта -->
    <div id="objectsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Выбор привязанного объекта</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <label id="selected_object"></label>
                        <br>
                    </div>
                    <div id="objectframe">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>


<!-- модальное окно выбора действия -->
<div id="actionModal" class="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Действие при активации порта</h4>
            </div>
            <div class="modal-body">
                <div class="btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-success" id="easy_button" >
                        <input type="radio" name="actions"  autocomplete="off" value="easy"> Простое действие
                    </label>
                    <label class="btn btn-success" id="method_button">
                        <input type="radio" name="actions"  autocomplete="off" value="method"> Метод объекта
                    </label>
                    <label class="btn btn-success" id="script_button">
                        <input type="radio" name="actions"  autocomplete="off" value="script"> Скрипт
                    </label>
                    <label class="btn btn-success" id="none_button">
                        <input type="radio" name="actions"  autocomplete="off" value="none"> Отсутствует
                    </label>
                </div>
                <br>
                <br>
                <br>
                <div id="mode">

                </div>
                <div id="object" class="d-none">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal"  onclick="save_method();">Сохранить изменения</button>
                <input type="hidden" value="" id="id_port">
                <input type="hidden" value="" id="value">
                <input type="hidden" value="" id="cur_method">
            </div>
        </div>
    </div>
</div>




<!-- модальное окно выбора действия для порта -->
<div id="methodsModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title" id="title_action"></h4>
            </div>

            <div class="modal-body" id="method_data">



            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button"   class="btn btn-primary" >Сохранить изменения</button>
            </div>
        </div>
    </div>
</div>




    <!-- модальное окно изменения имени у порта-->
    <div id="name_modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title"> Описание порта</h4>
                </div>

                    <div class="modal-body" >

                        <input type="text" class="form-control input-default " id="name_modal_data" placeholder="Input Default">
                        <button type="button" class="btn btn-default" onclick="no_name();">Убрать</button>

                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="save_name_port();" >Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>




    <!-- модальное окно сохранения настроек устройства -->
    <div id="device-settings-Modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title"> Сохранить настройки устройства ?</h4>
                </div>



                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="save_device_settings();" >Сохранить</button>
                </div>
            </div>
        </div>
    </div>



    <!-- модальное окно удаления устройства -->
    <div id="delete-device-Modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title"> Удалить устройство ?</h4>
                </div>



                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                    <button type="button"   class="btn btn-primary" data-dismiss="modal" onclick="delete_device();" >Удалить</button>
                </div>
            </div>
        </div>
    </div>





@endsection

@section('scripts')

    <script src="/js/pagescripts/device.js"></script>

@endsection