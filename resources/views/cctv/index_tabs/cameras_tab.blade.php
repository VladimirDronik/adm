@if($cameras->isNotEmpty())
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Название</th>
                    <th>Тип</th>
                    <th>Производитель</th>
                    <th>Видеорегистратор</th>
                    <th>Изображение</th>
                    <th class="text-center">Активно</th>
                    <th class="text-center">Сортировка</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cameras as $camera)
                <tr id="tr{{$camera->id}}">
                    <td scope="row">{{ $camera->id }}</td>
                    <td>
                        {{ $camera->name }}
                    </td>
                    <td>
                        {{ $camera->type_name }}
                    </td>
                    <td>
                        {{ $camera->vendor_name }}
                    </td>
                    <td>
                        @if($camera->recorder)
                            <a href="{{ route('recorders.edit',[$camera->recorder->id]) }}">{{ $camera->recorder->name }}</a>
                        @endif
                    </td>
                    <td scope="row">
                        @if($camera->type == \App\Models\Camera::TYPE_DIRECT_LINK)
                            <a href="{{ $camera->link }}" target="_blank"><img src="{{ $camera->image }}" onerror="this.src='{{ asset('ela/images/no-cam-image.jpg') }}'" width="120" height="80" loading="lazy"></img></a>
                        @else
                            <a href="{{ config('app.url') }}:8888/camera{{ $camera->id }}?muted=1&controls=0&autoplay=1" target="_blank"><img src="{{ $camera->image }}" onerror="this.src='{{ asset('ela/images/no-cam-image.jpg') }}'" width="120" height="80" loading="lazy"></img></a>
                        @endif
                    </td>
                    <td scope="row" align="center">
                        <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$camera->id}}" value="1" @if($camera->active) checked @endif>
                    </td>
                    <td style="width: 150px;">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control input-default" readonly value="{{ $camera->sort }}">
                            </div>
                            <div class="col-md-6 text-left">
                                <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $camera->id }}" onclick="changeCameraSort({{ $camera->id }}, 'up');">выше
                                </button>
                                <button type="button" class="btn btn-info btn-xs" id="sortBtn{{ $camera->id }}" onclick="changeCameraSort({{ $camera->id }}, 'down');">ниже
                                </button>
                            </div>
                        </div>
                    </td>
                    <td align="center">
                        <a href="{{ route('cameras.edit',[$camera->id]) }}" class="btn btn-info btn-sm btn-rounded">
                            <i class="fa fa-cog fa-lg"></i>
                        </a>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id="{{ $camera->id }}" data-name="{{ $camera->name }}" data-type="camera">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            @if(count($cameras) > 10)
            <tfoot>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Название</th>
                    <th>Тип</th>
                    <th>Производитель</th>
                    <th>Видеорегистратор</th>
                    <th>Изображение</th>
                    <th class="text-center">Активно</th>
                    <th class="text-center">Сортировка</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <br>
    {{ $cameras->appends(['tab' => 'cameras'])->links() }}
    <p class="text-right">Найдено: {{ $cameras->total() }}</p>
@else
    <p>Данные не найдены</p>
@endif