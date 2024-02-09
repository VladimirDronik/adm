@if($tcpBuses->isNotEmpty())
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Устройство</th>
                    <th>IP адрес</th>
                    <th>Порт</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tcpBuses as $bus)
                <tr id="tr{{$bus->id}}">
                    <td scope="row">{{ $bus->id }}</td>
                    <td>
                        {{ $bus->device }}
                    </td>
                    <td>
                        {{ $bus->ip_address }}
                    </td>
                    <td>
                        {{ $bus->port }}
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
            @if(count($tcpBuses) > 10)
            <tfoot>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Устройство</th>
                    <th>IP адрес</th>
                    <th>Порт</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <br>
    {{ $tcpBuses->appends(['tab' => 'tcp'])->links() }}
    <p class="text-right">Найдено: {{ $tcpBuses->total() }}</p>
@else
    <p>Данные не найдены</p>
@endif
