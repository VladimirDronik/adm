@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование счетчика № '. $count->id . ' «' . $count->name .'»',
        'links' => [ route('counts.index') => 'Счетчики'],
        'last_link' => 'Редактирование счетчика'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('counts.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок счетчиков</a>
                        <a href="{{ route('counts.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить счетчик</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($count, ['route' => ['counts.update', $count->id], 'id' => 'count_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('ID:', $count->id) }}
                        <div class="form-group row">
                            <label class="control-label text-right col-md-3 label-fix" for="">
                                Тип счетчика:     </label>
                            <div class="col-md-9">
                                <div class="mt-2">
                                    <img src="{{ asset('ela/images/counts/'.$count->image) }}" title="{{ $count->rus_type }}" alt="{{ $count->rus_type }}" width="30" height="30">
                                    {{ $count->rus_type }}
                                </div>
                            </div>
                        </div>

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        @if($count->object && $count->object->is_system)
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 label-fix" for="">
                                    Объект:     </label>
                                <div class="col-md-9">
                                    <div class="mt-2">
                                        <a class="a-color" href="{{ route('objects.edit', [$count->id_object]) }}">
                                            {{ $count->object->name }} (системный) </a>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id_object" value="{{ $count->id_object }}">
                        @else
                            {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $count->id_object), false, false, ['required' => true]) }}
                        @endif

                        {{ Form::bs_text('impulse', 'Значение за один импульс (в '.$count->unit.')*:', old('impulse', $count->impulse), ['required' => true]) }}
                        {{ Form::bs_text('today_value', 'Значение за сегодня*:', old('today_value', $count->today_value), ['required' => true]) }}
                        {{ Form::bs_text('total_value', 'Общее значение*:', old('total_value', $count->total_value), ['required' => true]) }}
                    </div>
                    {{ Form::bs_submit_btn() }}

                    @include('objects.methods', ['object' => $count->object])
                    @include('objects.events', ['object' => $count->object])

                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
                <button type="button" id="init_method_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>

            </div>
        </div>
    </div>

    @include('objects.method_modal')

    @include('components.info_modal')
    @include('components.del_modal')
    @include('components.create_object_modal', compact('object_types'))
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/count.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($count->object)->id }}';
        let del_id;

        $(document).ready(function () {
            initCountForm();

            $('#auto_sel_btn_id_object').click(function() {
                clearCreateObjectModal();
                $('#create_object_modal_init_btn').click();
                return false;
            });

            $('#create_object_modal_btn').click(function() {
                let message = validateCreateObject();
                if (message !== '') {
                    showCreateObjectError(message);
                    return false;
                }

                storeObject();
            });

            function storeObject() {
                const name = $("#create_object_modal input[name=object_name]").val().trim();
                const type = $("#create_object_modal input[name=object_type]:checked").val().trim();

                $.ajax({
                    url: storeObjectUrl,
                    data: {'_token': _token, 'name': name, 'type': type},
                    success: function (data) {
                        if (data.result) {
                            hideCreateObjectError();
                            updateObjectSelects(data.objects, data.id);
                            $('#create_object_cancel_btn').click();
                        } else {
                            showCreateObjectError(data.message);
                        }
                    },
                    error: function () {
                        showCreateObjectError('Сервер временно недоступен');
                    }
                });
            }

            function updateObjectSelects(objects, selected) {
                const id = $('#auto_sel_id_object').val();
                if (id) {
                    selected = id;
                }
                createObjectSelect('#auto_sel_id_object', objects, selected);
            }

            // methods

            const cancel_btn = $('#cancel_btn');

            $('#add_btn').click(showAddModal);

            $('#apply_btn').click(clickApplyBtn);

            // edit method
            $('body').on('click', '.edit_btn', clickEditBtn);

            // change easy/script/none in modal
            $('input[type=radio][name=actions]').change(changeRadioActions);

            // delete method
            $('body').on('click', '.del_btn', function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить метод «'+$(this).attr('data-name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(clickDelBtn);
        });
    </script>
@endsection
