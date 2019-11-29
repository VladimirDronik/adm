<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="no-border-top">
                <th>#</th>
                <th>Тип</th>
                <th>Описание</th>
                <th>Связанный объект</th>
                <th>Действие</th>
                <th class="text-center">Длит. нажатие</th>
                <th class="text-center">Двойн. нажатие</th>
            </tr>
        </thead>
        <tbody>
        @php $port_count = 0; @endphp
        @foreach($device->ports as $port)
            @if($port->status === 'in')
            @php $port_count++; @endphp
            <tr>
                <th scope="row"> {{ $port->num_port }}</th>
                <td>
                    <span class="badge badge-success">{{ $port->status }}</span>
                </td>
                <td>
                    <a href="#" data-toggle="modal" data-target="#name_modal"
                       id="name_port_{{ $port->id }}" onclick="getPortComment('{{ $port->id }}');">
                        @if($port->is_empty_comment)
                            <i>{{ $port->comment != '' ? $port->comment : 'Отсутствует'}}</i>
                        @else
                            <span style="color: #455a64;">{{ $port->comment }}</span>
                        @endif
                    </a>
                </td>
                <td>
                    @if($port->eobject)
                        <button type="button" class="btn btn-warning m-b-10 btn-sm"
                                name="object" id="portobj_{{ $port->id }}"
                                data-toggle="modal" data-target="#objectsModal"
                                value="{{ $port->object}},{{$port->eobject->name}},portobj_{{ $port->id }}">
                            <b>{{ optional($port->eobject)->name }}</b>
                        </button>
                    @else
                        <button type="button" class="btn btn-default m-b-10 btn-sm"
                                name="object" id="portobjempty_{{ $port->id }}"
                                data-toggle="modal" data-target="#objectsModal"
                                value="empty,empty,portobjempty_{{ $port->id }}">
                            Отсутствует
                        </button>
                    @endif
                </td>
                <td>
                    {{--                                    @if($port->eobject && $port->status !== 'out')--}}
                    {{--                                        <button type="button" id="method_btn_{{ $port->id }}" class="btn btn-warning m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('method', {{ $port->id }}, '{{ optional($port->eobject)->name }}');">--}}
                    {{--                                            @if($port->method) <b>Метод: {{ optional($port->emethod)->name }}</b> @else <b class="text-danger">Метод не выбран</b> @endif--}}
                    {{--                                        </button>--}}
                    {{--                                    @elseif($port->status !== 'out')--}}
                    {{--                                        <button type="button" id="method_btn_{{ $port->id }}" class="btn btn-default m-b-10 btn-sm" data-toggle="modal" data-target="#actionModal" onclick="click_port_method('none', {{ $port->id }}, 'none');">Отсутствует</button>--}}
                    {{--                                    @endif--}}
                    @if($port->eobject)
                        <button type="button" id="viewmethod_{{ $port->id }}"
                                name="method" class="btn btn-warning m-b-10 btn-sm" data-toggle="modal"
                                value="{{ $port->method}},{{optional($port->emethod)->name}},viewmethod_{{ $port->id }}"
                                data-target="#methodsModal">
                            @if($port->method)<b>Метод: {{ optional($port->emethod)->name }}</b>@else <b class="text-danger">Метод не выбран</b> @endif
                        </button>
                    @else
                        <button type="button" id="viewmethodempty_{{ $port->id }}"
                                name="method" class="btn btn-default m-b-10 btn-sm" data-toggle="modal"
                                value="empty,empty,viewmethodempty_{{ $port->id }}"
                                data-target="#methodsModal">
                            Отсутствует</button>
                    @endif
                </td>
                <td class="text-center">
                    <input type="checkbox" class="long_checkbox" data-id="{{ $port->id }}" style="cursor: pointer;" autocomplete="off" value="1" @if($port->longclick) checked @endif></td>
                <td class="text-center">
                    <input type="checkbox" class="double_checkbox" data-id="{{ $port->id }}" style="cursor: pointer;" autocomplete="off" value="1" @if($port->doubleclick) checked @endif> </td>
            </tr>
            @endif
        @endforeach
        </tbody>
        @if($port_count > 10)
            <tfoot>
            <tr>
                <th>#</th>
                <th>Тип</th>
                <th>Описание</th>
                <th>Связанный объект</th>
                <th>Действие</th>
                <th class="text-center">Длит. нажатие</th>
                <th class="text-center">Двойн. нажатие</th>
            </tr>
            </tfoot>
        @endif
    </table>
</div>
<p class="text-right">Всего портов IN: {{ $port_count }}</p>