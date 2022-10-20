<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="no-border-top">
                <th>#</th>
                <th>Тип</th>
                <th>Описание</th>
                <th class="text-left">Связанный объект</th>
                <th>Один. нажатие</th>
                <th>Двойн. нажатие</th>
                <th class="text-left">Длит. нажатие</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @php $port_count = 0; @endphp
        @foreach($device->ports as $port)
            @if($port->type === 'in')
            @php $port_count++; @endphp
            @php  switch ($port->status) {

                        case 'NC': $badge = 'badge-secondary';
                        break;

                        case 'IN': $badge = 'badge-success';
                        break;

                        case 'OUT': $badge = 'badge-primary';
                        break;

                        case '1WIRE': $badge = 'badge-warning';
                        break;

                        case '1W-BUS': $badge = 'badge-warning';
                        break;

                        case 'I2C': $badge = 'badge-danger';
                        break;

                        default: $badge = 'badge-secondary';

                     }
            @endphp
            <tr>
                <th scope="row">{{ $port->num_port }}</th>
                <td><a href="{{ route('ports.edit', [$port->id,'tab=1']) }}"><span class="badge {{ $badge }}">{{ $port->status }}</span></a></td>
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
                        <button type="button" class="btn btn-warning m-b-10 btn-sm"
                                name="object" id="portobj_{{ $port->id }}"
                                data-toggle="modal" data-target="#objectsModal"
                                onclick="resetOutFilter()"
                                value="{{ $port->object}},{{$port->eobject->name}},portobj_{{ $port->id }}">
                            <b>{{ optional($port->eobject)->name }}</b>
                        </button>
                    @else
                        <button type="button" class="btn btn-default m-b-10 btn-sm"
                                name="object" id="portobjempty_{{ $port->id }}"
                                onclick="resetOutFilter()"
                                data-toggle="modal" data-target="#objectsModal"
                                value="empty,empty,portobjempty_{{ $port->id }}">
                            Отсутствует
                        </button>
                    @endif
                </td>
                <td class="text-left">
                    <span id="ordinary{{ $port->id }}" style="cursor: pointer;"
                            class="@if($port->method) btn-warning @else btn-default @endif
                                    m-b-10 btn-sm js-method-btn"
                            data-type="ordinary"
                            data-port-id="{{ $port->id }}"
                            data-method-id="{{ $port->method ? $port->method : '' }}"
                            data-object-id="@if($port->method) {{ optional($port->emethod)->id_object }} @endif">
                        @if($port->method) Объект: {{ optional($port->emethod)->eobject->name }}
                            <br>&nbsp;&nbsp;Метод: {{ optional($port->emethod)->name }}
                            @if(optional($port->emethod)->is_need_param) ({{ $port->method_params }}) @endif
                        @else <i class="f-s-14">Метод не указан</i> @endif
                    </span>
                </td>
                <td class="text-left">
                    <span id="double{{ $port->id }}" style="cursor: pointer;"
                            class="@if($port->dcmethod) btn-warning @else btn-default @endif
                                    m-b-10 btn-sm js-method-btn"
                            data-type="double"
                            data-port-id="{{ $port->id }}"
                            data-method-id="{{ $port->dcmethod ? $port->dcmethod : '' }}"
                            data-object-id="@if($port->dcmethod) {{ optional($port->dcmethod)->id_object }} @endif">
                        @if($port->dcmethod) Объект: {{ optional($port->dcmethod)->eobject->name }}
                            <br>&nbsp;&nbsp;Метод: {{ optional($port->dcmethod)->name }}
                            @if(optional($port->dcmethod)->is_need_param) ({{ $port->dc_method_params }}) @endif
                        @else <i class="f-s-14">Метод не указан</i> @endif
                    </span>
                </td>
                <td class="text-left">
                    <span id="long{{ $port->id }}" style="cursor: pointer;"
                            class="@if($port->lcmethod) btn-warning @else btn-default @endif
                                    m-b-10 btn-sm js-method-btn"
                            data-type="long"
                            data-port-id="{{ $port->id }}"
                            data-method-id="{{ $port->lcmethod ? $port->lcmethod : '' }}"
                            data-object-id="@if($port->lcmethod) {{ optional($port->lcmethod)->id_object }} @endif">
                        @if($port->lcmethod) Объект: {{ optional($port->lcmethod)->eobject->name }}
                            <br>&nbsp;&nbsp;Метод: {{ optional($port->lcmethod)->name }}
                            @if(optional($port->lcmethod)->is_need_param) ({{ $port->lc_method_params }}) @endif
                        @else <i class="f-s-14">Метод не указан</i> @endif
                    </span>
                </td>
                <td>
                <a href="{{ route('ports.edit', [$port->id,'tab=1']) }}" class="btn btn-info btn-sm btn-rounded">
                    <i class="fa fa-cog fa-lg"></i>
                </a>
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
                    <th class="text-left">Связанный объект</th>
                    <th>Один. нажатие</th>
                    <th>Двойн. нажатие</th>
                    <th class="text-left">Длит. нажатие</th>
                    <th></th>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
<p class="text-right">Всего портов IN: {{ $port_count }}</p>