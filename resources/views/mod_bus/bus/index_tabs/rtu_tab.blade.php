@if($rtuBuses->isNotEmpty())
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Устройство</th>
                    <th>Скорость</th>
                    <th>Биты данных</th>
                    <th>Бит четности</th>
                    <th>Стоповые биты</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($rtuBuses as $bus)
                <tr id="tr{{$bus->id}}">
                    <td scope="row">{{ $bus->id }}</td>
                    <td>
                        {{ $bus->device }}
                    </td>
                    <td>
                        {{ $bus->baudrate ? $bus->baudrate . ' кб/с' : '' }}
                    </td>
                    <td>
                        {{ $bus->length }}
                    </td>
                    <td>
                        {{ $bus->parity }}
                    </td>
                    <td>
                        {{ $bus->stopbits }}
                    </td>
                    <td align="center">
                        <a href="{{ route('mod_bus.buses.edit', [$bus->id]) }}" class="btn btn-info btn-sm btn-rounded">
                            <i class="fa fa-cog fa-lg"></i>
                        </a>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id="{{ $bus->id }}">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            @if(count($rtuBuses) > 10)
            <tfoot>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Устройство</th>
                    <th>Скорость</th>
                    <th>Биты данных</th>
                    <th>Бит четности</th>
                    <th>Стоповые биты</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <br>
    {{ $rtuBuses->appends(['tab' => 'rtu'])->links() }}
    <p class="text-right">Найдено: {{ $rtuBuses->total() }}</p>
@else
    <p>Данные не найдены</p>
@endif
