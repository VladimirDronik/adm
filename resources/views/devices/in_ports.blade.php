<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="no-border-top">
                <th>#</th>
                <th>Тип</th>
                <th>Описание</th>
                <th>Один. нажатие</th>
                <th>Двойн. нажатие</th>
                <th class="text-left">Длит. нажатие</th>
            </tr>
        </thead>
        <tbody>
        @php $port_count = 0; @endphp
        @foreach($device->ports as $port)
            @if($port->status === 'in')
            @php $port_count++; @endphp
            <tr>
                <th scope="row"> {{ $port->num_port }}</th>
                <td><span class="badge badge-success">{{ $port->status }}</span></td>
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
                <td class="text-left">
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
                <td class="text-left">
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
                <td class="text-left">
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
                    <th>Один. нажатие</th>
                    <th>Двойн. нажатие</th>
                    <th class="text-left">Длит. нажатие</th>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
<p class="text-right">Всего портов IN: {{ $port_count }}</p>