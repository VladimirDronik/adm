@if($recorders->isNotEmpty())
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Название</th>
                    <th>Производитель</th>
                    <th>IP адрес</th>
                    <th>Логин</th>
                    <th class="text-center">Сортировка</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recorders as $recorder)
                <tr id="tr{{$recorder->id}}">
                    <td scope="row">{{ $recorder->id }}</td>
                    <td>
                        {{ $recorder->name }}
                    </td>
                    <td>
                        {{ $recorder->vendor_name }}
                    </td>
                    <td>
                        {{ $recorder->ip_address }}
                    </td>
                    <td>
                        {{ $recorder->login }}
                    </td>
                    <td style="width: 150px;">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control input-default" readonly value="{{ $recorder->sort }}">
                            </div>
                            <div class="col-md-6 text-left">
                                <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $recorder->id }}" onclick="changeRecorderSort({{ $recorder->id }}, 'up');">выше
                                </button>
                                <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $recorder->id }}" onclick="changeRecorderSort({{ $recorder->id }}, 'down');">ниже
                                </button>
                            </div>
                        </div>
                    </td>
                    <td align="center">
                        <a href="{{ route('recorders.edit', [$recorder->id]) }}" class="btn btn-info btn-sm btn-rounded">
                            <i class="fa fa-cog fa-lg"></i>
                        </a>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id="{{ $recorder->id }}" data-name="{{ $recorder->name }}" data-type="recorder">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            @if(count($recorders) > 10)
            <tfoot>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Название</th>
                    <th>Производитель</th>
                    <th>IP адрес</th>
                    <th>Логин</th>
                    <th class="text-center">Сортировка</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <br>
    {{ $recorders->appends(['tab' => 'recorders'])->links() }}
    <p class="text-right">Найдено: {{ $recorders->total() }}</p>
@else
    <p>Данные не найдены</p>
@endif