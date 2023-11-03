@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('cameras.breadcrumbs', ['title' => 'Видеонаблюдение'])
@endsection

@section('content')
    <div class="container-fluid">
        @include('cctv.header')
        <div class="card">
            <div class="card-title"><h4>Видеонаблюдение</h4></div>
            <ul class="nav nav-tabs customtab" role="tablist">
                <li class="nav-item"> <a class="nav-link @if($tab == 'cameras') active @endif"  data-toggle="tab" href="#cameras" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Камеры без видеорегистратора</span></a></li>
                <li class="nav-item"> <a class="nav-link @if($tab == 'recorders') active @endif"  data-toggle="tab" href="#recorders" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Видеорегистраторы</span></a></li>
                @if($recorders->isNotEmpty())
                    @foreach($recorders as $recorder)
                        <li class="nav-item"> <a class="nav-link @if($tab == 'recorder' . $recorder->id) active @endif"  data-toggle="tab" href="#recorder{{ $recorder->id }}" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">{{ $recorder->name }}</span></a></li>
                    @endforeach
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane p-20 @if($tab == 'cameras') active @endif" id="cameras" role="tabpanel">
                    <div class="card-body">
                        @include('cctv.index_tabs.cameras_tab')
                    </div>
                </div>
                <div class="tab-pane p-20 @if($tab == 'recorders') active @endif" id="recorders" role="tabpanel">
                    @include('cctv.index_tabs.recorders_tab')
                </div>
                @if($recorders->isNotEmpty())
                    @foreach($recorders as $recorder)
                        <div class="tab-pane p-20 @if($tab == 'recorder' . $recorder->id) active @endif" id="recorder{{ $recorder->id }}" role="tabpanel">
                            @include('cctv.index_tabs.recorder_cameras_tab', ['recorder' => $recorder])
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
    @include('cctv.create_modal')
@endsection

@section('scripts')
    <script>
        const url = '{{ route('cctv.index') }}';
        const cameraSortUrl = '{{ route('ajax.cameras.sort') }}';
        const recorderSortUrl = '{{ route('ajax.recorders.sort') }}';

        function changeCameraSort(id, direction) {
            $.ajax({
                url: cameraSortUrl,
                data: {'_token': _token, 'id': id, 'direction': direction},
                success: function (data) {
                    if (data.result) {
                        window.location.href = url + '?tab=' + data.tab;
                    } else {
                        showErrorModal('Ошибка при сохранении изменений');
                    }
                }
            });
        }

        function changeRecorderSort(id, direction) {
            $.ajax({
                url: recorderSortUrl,
                data: {'_token': _token, 'id': id, 'direction': direction},
                success: function (data) {
                    if (data.result) {
                        window.location.href = url + '?tab=recorders';
                    } else {
                        showErrorModal('Ошибка при сохранении изменений');
                    }
                }
            });
        }

        $(document).ready(function () {
            $('.active_checkbox').change(function () {
                let active = this.checked ? 1 : 0;
                let camera_id = $(this).attr('data-id');

                $.ajax({
                    url: '{{ route('ajax.cameras.active') }}',
                    data: {'_token': _token, 'id': camera_id, 'active': active},
                    success: function (data) {
                        if (data.result) {
                            showSuccessModal('Активность успешно изменена');
                        } else {
                            showErrorModal('Ошибка при изменении активности');
                        }
                    },
                });
            });

            $('.del_btn').click(function () {
                del_id = $(this).attr('data-id');
                del_name = $(this).attr('data-name');
                del_type = $(this).attr('data-type');
                $('#del_modal_body').text('Удалить '+(del_type == 'camera' ? 'камеру ': 'видеорегистратор ')+del_name+'?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function () {
                if (del_id) {
                    $.ajax({
                    url: del_type == 'camera' ? '{{ route('ajax.cameras.delete') }}' : '{{ route('ajax.recorders.delete') }}',
                    data: {'_token': _token, 'id': del_id},
                        success: function (data) {
                            if (data.result) {
                                $('#tr' + del_id).hide();
                                if (del_type == 'recorder') {
                                    window.location.href = url + '?tab=recorders';
                                }
                            } else {
                                showErrorModal('Ошибка при удалении');
                            }
                        }
                    });
                }
            });

            $('#addDeviceBtn').click(function() {
                $('#modal_add_device_init_btn').click();
            });
        });
    </script>
@endsection
