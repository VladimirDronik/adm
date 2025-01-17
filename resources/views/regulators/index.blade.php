@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Регуляторы</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item active">Регуляторы</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('regulators.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить регулятор</a>
                        <a href="{{ route('regulators.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Регуляторы</h4></div>
            <div class="card-body">
                @if(count($regulators))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Источник данных</th>
                                    <th>Размещение</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($regulators as $regulator)
                                    <tr id="tr{{$regulator->id}}">
                                        <td scope="row">{{ $regulator->object_id }}</td>
                                        <td>
                                            <a href="{{ route('regulators.edit', [$regulator->id]) }}">
                                                {{ $regulator->object->name }}
                                            </a>
                                        </td>
                                        <td>{{ $regulator->source }}</td>
                                        <td>{{ $regulator->relatedRoom->name }}</td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('regulators.edit', [$regulator->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id="{{ $regulator->id }}" data-name="{{ $regulator->object->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if(count($regulators) > 10)
                                <tfoot>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Название</th>
                                        <th>Источник данных</th>
                                        <th>Размещение</th>
                                        <th style="width: 60px;"></th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $regulators->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $regulators->total() }}</p>
                @else
                    <p>Регуляторы не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить регулятор № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: "{{ route('ajax.regulators.delete') }}",
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении регулятора');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
