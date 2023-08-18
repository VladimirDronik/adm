@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Устройства: сухие контакты</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Датчики</li>
                <li class="breadcrumb-item active">Сухие контакты</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @include('detectors.tab_header', ['active' => 'drycontacts'])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('drycontacts.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить датчик</a>
                        <a href="{{ route('drycontacts.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Сухие контакты</h4></div>
            <div class="card-body">
                @if(count($drycontacts))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 160px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($drycontacts as $drycontact)
                                    <tr id="tr{{$drycontact->id}}">
                                        <td scope="row">{{ $drycontact->object['id'] }}</td>
                                        <td><a href="{{ route('drycontacts.edit', [$drycontact->id]) }}">{{ $drycontact->name }}</a></td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('drycontacts.edit', [$drycontact->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $drycontact->id }}" data-name="{{ $drycontact->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Название</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    {{ $drycontacts->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $drycontacts->total() }}</p>
                @else
                    <p>Сухие контакты не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
    @include('components.info_modal')
@endsection

@section('scripts')
    <script>
        let url = '{{ route('drycontacts.index') }}';

        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить сухой контакт № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.drycontacts.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении сухого контакта');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
