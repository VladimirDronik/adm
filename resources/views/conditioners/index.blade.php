@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('conditioners.breadcrumbs', ['title' => 'Кондиционеры'])
@endsection

@section('content')
    <div class="container-fluid">
        @include('conditioners.header')
        <div class="card">
            <div class="card-title"><h4>Кондиционеры</h4></div>
            <div class="card-body">
                @if(count($conditioners))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Тип</th>
                                    <th>Устройство</th>
                                    <th>Размещение</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conditioners as $conditioner)
                                    <tr id="tr{{$conditioner->id}}">
                                        <td scope="row">{{ $conditioner->id_object }}</td>
                                        <td>{{ $conditioner->name }}</td>
                                        <td>{{ $conditioner->relatedType->name }}</td>
                                        <td>{{ $conditioner->modbusSlaver->name }}</td>
                                        <td>{{ $conditioner->room?->name }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('conditioners.edit',[$conditioner->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn" data-id="{{ $conditioner->id }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if(count($conditioners) > 10)
                                <tfoot>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Название</th>
                                        <th>Тип</th>
                                        <th>Устройство</th>
                                        <th>Размещение</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $conditioners->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $conditioners->total() }}</p>
                @else
                    <p>Данные не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        const deleteUrl = '{{ route('ajax.conditioners.delete') }}';

        $(document).ready(function(){
            let del_id;
            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить кондиционер № ' + $(this).data('id') + ' ?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function() {
                if (del_id) {
                    $.ajax({
                        url: deleteUrl,
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении кондиционера');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
