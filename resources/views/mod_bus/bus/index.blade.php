@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Шины'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.buses.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить шину</a>
                        <a href="{{ route('mod_bus.buses.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Шины</h4></div>
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item"> <a class="nav-link @if($tab == 'rtu') active @endif"  data-toggle="tab" href="#rtu" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">RTU</span></a></li>
                <li class="nav-item"> <a class="nav-link @if($tab == 'tcp') active @endif"  data-toggle="tab" href="#tcp" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">TCP</span></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane p-20 @if($tab == 'rtu') active @endif" id="rtu" role="tabpanel">
                    @include('mod_bus.bus.index_tabs.rtu_tab')
                </div>
                <div class="tab-pane p-20 @if($tab == 'tcp') active @endif" id="tcp" role="tabpanel">
                    @include('mod_bus.bus.index_tabs.tcp_tab')
                </div>
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.del_btn').click(function () {
                del_id = $(this).attr('data-id');
                $('#del_modal_title').text('Вы точно хотите удалить шину №'+ del_id +' ?');
                $('#del_modal_body').text('При удалении шины, удалятся все устройства на шине и их регистры !');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function () {
                if (del_id) {
                    $.ajax({
                    url: '{{ route('ajax.mod_bus.buses.delete') }}',
                    data: {'_token': _token, 'id': del_id},
                        success: function (data) {
                            if (data.result) {
                                $('#tr' + del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
