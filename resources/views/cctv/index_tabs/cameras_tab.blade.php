@if($camerasWithoutRecorders->isNotEmpty())
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Название</th>
                    <th>Тип</th>
                    <th>Размещение</th>
                    <th>Изображение</th>
                    <th class="text-center">Сортировка</th>
                    <th class="text-center">Активно</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($camerasWithoutRecorders as $camera)
                <tr id="tr{{$camera->id}}">
                    <td scope="row">{{ $camera->id }}</td>
                    <td>
                        {{ $camera->name }}
                    </td>
                    <td>
                        {{ $camera->type }}
                    </td>
                    <td>
                        {{ $camera->relationRoom ? $camera->relationRoom->name : 'Нет' }}
                    </td>
                    <td scope="row">
                        <img src="{{ $camera->image }}" onerror="this.src='{{ asset('ela/images/no-cam-image.jpg') }}'" width="120" height="80" loading="lazy">
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
                    <td scope="row" align="center">
                        <input type="checkbox" class="active_checkbox" style="cursor: pointer;" data-id="{{$camera->id}}" value="1" @if($camera->active) checked @endif>
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
            @if(count($camerasWithoutRecorders) > 10)
            <tfoot>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Название</th>
                    <th>Тип</th>
                    <th>Размещение</th>
                    <th>Изображение</th>
                    <th class="text-center">Сортировка</th>
                    <th class="text-center">Активно</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <p class="text-right">Найдено: {{ $camerasWithoutRecorders->count() }}</p>
@else
    <p>Данные не найдены</p>
@endif