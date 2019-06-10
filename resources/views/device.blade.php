

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
                            Название: <input name="description" value="{{ $device->description}}" size="15">
                            ip адрес: <input name="ip_address" value="{{ $device->ip_address }}" size="15">
                            <button type="button" class="btn btn-success m-b-10 m-l-5">Сохранить настройки</button>
                            <button type="button" class="btn btn-danger m-b-10 m-l-5">Удалить устройство</button>
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
                                    @elseif ($port->type=='sw')
                                        <span class="badge badge-success">{{ $port->type }}</span>

                                    @endif

                                </td>
                                <td><a href="#">{{ $port->comment }}</a></td>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Действие при активации порта</h4>
            </div>
            <div class="modal-body">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
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

@endsection

@section('scripts')
<script>








    //Вызов модального окна с объектами
    $('button[type=button][name=object]').click(function () {



        var object_val = this.value;
        var port_id = this.id;
        var object_arr = object_val.split(',');


        var dataarr = {};
        dataarr['object'] = object_val;

        $.ajax({
                type:'POST',
                url:'/getobject',
                data: dataarr,
                success:function(data){

                    $("#objectframe").html(data.html);
                }
            });


        if(object_arr[0]!='empty') {
            $('#selected_object').html('Выбран объект: '+ object_arr[1] +
                '   <button type="button" class="btn btn-danger  m-b-10 btn-xs" data-dismiss="modal" ' +
                'id = "reset_object"  value="'+ port_id + '" onclick="reset_object(\''+port_id+'\',\''+object_arr[2]+'\');">убрать</button>');
        }
        else {
            $('#selected_object').html('Объект не выбран');
        }

    })






    function reset_object(id,port) {

        //Внесение изменений в БД
        select_object(null, null);

        $('#'+id).html('Отсутсвует');
        $('#'+id).attr({"class": "btn btn-default  m-b-10 btn-sm"});
        $( '#'+port).val('empty,empty,' + id);


    }


    // Модальное окно с действиями - выбор действия
    $('input[type=radio][name=actions]').change(function(){


        select_method(this.value,$('#id_port').val(),$('#value').val());

    });





    function click_port_method(mode, port_id, value) {

        $('#cur_method').val(mode);
        select_method(mode, port_id, value)
    }





    function select_method(mode, port_id, value) {

        $('#easy_button').attr({"class": "btn btn-success"});
        $('#script_button').attr({"class": "btn btn-success"});
        $('#method_button').attr({"class": "btn btn-success"});
        $('#none_button').attr({"class": "btn btn-success"});

        $('#'+mode+'_button').attr({"class": "btn btn-success active"});

        $('#id_port').val(port_id);
        $('#value').val(value);


        var dataarr = {};
        dataarr['methodmode'] = mode;
        dataarr['port_id'] = port_id;
        dataarr['value'] = value;
        dataarr['cur_method'] = $('#cur_method').val();


        $.ajax({
            type:'POST',
            url:'/getmethod',
            data: dataarr,
            success:function(data){

                $('#mode').html(data.html);
            }
        });
    }


    //Сохранение выбранного метода для порта
    function save_method() {

        var action = '';
        var dataarr = {};

        action = $('#action_text').val();

        dataarr['methodmode'] = action;
        dataarr['id_port'] = $('#id_port').val();

        if (action == 'easy') {



            var devicearr = ($('#dev_select_button').html()).split(': ');
            var portarr = ($('#port_btn').html()).split(': ');
            var actarr = ($('#action_btn').html()).split(': ');
            dataarr['device'] = devicearr[1];
            dataarr['port'] = portarr[1];
            dataarr['act'] = actarr[1];



            $('#method_btn_' + $('#port_id').val()).attr({"class": "btn btn-success  m-b-10 btn-sm"});
            $('#method_btn_' + $('#port_id').val()).html('Простое: ' + dataarr['device'] + ';' + dataarr['port'] + ':' + dataarr['act']);
        }

        if (action == 'method') {

            dataarr['id_object'] = $('#id_object').val();

            $('#method_btn_' + $('#port_id').val()).attr({"class": "btn btn-warning  m-b-10 btn-sm"});
            $('#method_btn_' + $('#port_id').val()).html('<b><< Выполнять действие объекта</b>');
        }

        if (action == 'script') {

            var script = ($('#script_btn').html()).split(': ');
            dataarr['script_name'] = script[1];
            dataarr['id_script'] = $('#id_script').val();

            $('#method_btn_' + $('#port_id').val()).attr({"class": "btn btn-info  m-b-10 btn-sm"});
            $('#method_btn_' + $('#port_id').val()).html('<b>'+script[1]+'</b>');

        }

        if (action == 'none') {
            $('#method_btn_' + $('#port_id').val()).attr({"class": "btn btn-default  m-b-10 btn-sm"});
            $('#method_btn_' + $('#port_id').val()).html('Отсутствует');
        }

        $.ajax({
            type:'POST',
            url:'/savemethod',
            data: dataarr,
            success:function(data){

                $('#').html(data.html);
            }
        });


    }


</script>
@endsection