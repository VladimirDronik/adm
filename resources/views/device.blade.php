

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
                            <th align="center">Длит наж</th>
                            <th align="center">Двойн наж</th>
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
                                        <button type="button" class="btn btn-warning  m-b-10 btn-sm" name="object" id="portobj_{{ $port->num_port }}"  data-toggle="modal" data-target="#objectsModal"  value="{{ $port->object}},{{$port->nameobj}},portobj_{{ $port->num_port }}"> <b>{{ $port->nameobj }}</b></button>
                                    @else
                                        <button type="button" class="btn btn-default  m-b-10 btn-sm" name="object" id="portobjempty_{{ $port->num_port }}"   data-toggle="modal" data-target="#objectsModal" value="empty,empty,portobjempty_{{ $port->num_port }}">Отсутствует</button>
                                    @endif
                                </td>
                                <td>
                                    @if ($port->easy)
                                        <button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal"><b>Простое: {{ $port->easy }}</b></button>
                                    @elseif ($port->script)
                                        <button type="button" class="btn btn-info  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal"><b>{{ $port->namescript }}</b></button>
                                    @elseif ($port->nameobj!='' && $port->type!='out')
                                        <button type="button" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal"><b><- Выполнять действие объекта</b></button>
                                    @elseif ($port->type!='out')
                                        <button type="button" class="btn btn-default  m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal">Отсутсвует</button>
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


<!-- HTML-код модального окна -->
<div id="actionModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Действие по нажатию на физическую кнопку</h4>
            </div>
            <div class="modal-body">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-success active">
                        <input type="radio" name="actions" id="easy_button" autocomplete="off" value="easy" checked> Простое действие
                    </label>
                    <label class="btn btn-success">
                        <input type="radio" name="actions" id="option2" autocomplete="off" value="method"> Метод объекта
                    </label>
                    <label class="btn btn-success">
                        <input type="radio" name="actions" id="option3" autocomplete="off" value="script"> Скрипт
                    </label>
                    <label class="btn btn-success">
                        <input type="radio" name="actions" id="option3" autocomplete="off" value="none"> Отсутствует
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
                <button type="button" class="btn btn-primary" >Сохранить изменения</button>
            </div>
        </div>
    </div>
</div>


<!-- HTML-код модального окна -->
<div id="methodsModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Выбор метода объекта</h4>
            </div>

            <div class="modal-body">




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button"   class="btn btn-primary">Сохранить изменения</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>








    //Вызов модального окна с объектами
    $('button[type=button][name=object]').click(function () {
        //alert(this.value);
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

        $('#'+id).html('Отсутсвует');
        $('#'+id).attr({"class": "btn btn-default  m-b-10 btn-sm"});
        $( '#'+port).val('empty,empty,' + id);


    }



    $('input[type=radio][name=actions]').change(function(){

        if (this.value == 'easy'){
            $('#mode').html('<button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Устройство:</button>&nbsp;' +
                '<button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Порт:</button>&nbsp;' +
                '<button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Действие:</button>'+
                '<br><br><div class="alert alert-info">В этом режиме при срабатывании входного порта будет выполняться ' +
            'действие с другим портом этого же или другого устройства. Для этого необхоидмо добавить команду ' +
            'в формате "Устройство; Порт: Действие"</div>');

        }
        else if (this.value == 'method'){
            $('#mode').html('<button type="button" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Объект:</button>&nbsp;'+
            '<button type="button" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Метод:</button>'+
            '<div class="alert alert-info">В этом режиме при срабатывании входного порта будет выполняться ' +
            ' метод выбранного здесь объекта</div>');
        }
        else if (this.value == 'script'){
            $('#mode').html('<button type="button" class="btn btn-info  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Скрипт</button>');
        }

        else if (this.value == 'none'){
            $('#mode').html('<div class="alert alert-info">Действие при срабатывании порта не выбрано</div>');
            //$('#object').removeClass("d-none");
            //$('#object').addClass("d-none");
        }


    });
</script>
@endsection