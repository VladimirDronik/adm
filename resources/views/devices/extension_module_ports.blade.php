<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr class="no-border-top">
                <th>#</th>
                <th>Тип</th>
                <th>Описание</th>
                <th class="text-left">Связанный объект</th>
            </tr>
        </thead>
        <tbody>
        @php $port_count = 0; @endphp
        @foreach($extensionModule->ports as $port)
                @php $port_count++; @endphp
                @php  switch ($port->status) {

                        case 'NC': $badge = 'badge-secondary';
                        break;

                        case '0..10V': $badge = 'badge-secondary';
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
                    <th scope="row"> {{ $port->num_port }}</th>
                    <td>
                        <span class="badge {{ $badge }}">{{ $port->status }}</span>
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
                </tr>
        @endforeach
        </tbody>
        @if($port_count > 10)
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Тип</th>
                    <th>Описание</th>
                    <th class="text-left">Связанный объект</th>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
<p class="text-right">Всего портов: {{ $port_count }}</p>