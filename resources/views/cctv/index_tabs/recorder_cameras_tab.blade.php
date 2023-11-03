@if($recorder->cameras->isNotEmpty())
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Название</th>
                    <th>Тип</th>
                    <th>Изображение</th>
                    <th class="text-center">Сортировка</th>
                    <th class="text-center">Активно</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($recorder->cameras as $camera)
                <tr id="tr{{$camera->id}}">
                    <td scope="row">{{ $camera->id }}</td>
                    <td>
                        {{ $camera->name }}
                    </td>
                    <td>
                        {{ $camera->type }}
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
                </tr>
                @endforeach
            </tbody>
            @if(count($recorder->cameras) > 10)
            <tfoot>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Название</th>
                    <th>Тип</th>
                    <th>Изображение</th>
                    <th class="text-center">Сортировка</th>
                    <th class="text-center">Активно</th>
                    <th></th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <p class="text-right">Найдено: {{ $recorder->cameras->count() }}</p>
@else
    <p>Данные не найдены</p>
@endif
