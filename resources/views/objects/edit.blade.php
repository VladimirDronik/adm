@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование объекта «'. $object->name .'»',
        'links' => [ route('objects.index') => 'Объекты'],
        'last_link' => 'Редактирование объекта'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('objects.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок объектов</a>
                        <a href="{{ route('objects.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить объект</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-10 col-xl-9">
                    {!! Form::model($object, ['route' => ['objects.update', $object->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_title('Основные данные') }}

                        @if(!$object->is_system)
                            {{ Form::bs_radio('type', 'Тип элемента*:', $types, old('type', $object->type),
                                ['required' => true]) }}
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                            {{ Form::bs_submit_btn() }}
                        @else
                            {{ Form::bs_radio('type', 'Тип элемента*:', $types, old('type', $object->type),
                                ['required' => true, 'disabled' => true]) }}
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true, 'disabled' => true]) }}
                            {{ Form::bs_simple_text('Тип объекта:', 'Системный') }}
                            <br>
                        @endif

                        @include('objects.methods')
                        @include('objects.sheduler')

                    </div>
                    {!! Form::close() !!}
                </div>

                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_method_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>

            </div>
        </div>
    </div>

    @include('objects.method_modal')

    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script>
        const store_url = '{{ route('ajax.methods.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ $object->id }}';
        const is_admin = {{ user()->is_admin ? 1 : 0 }};
        const url_device = '{{ route('ajax.devices.type_controller') }}';
        let del_id;

        $(document).ready(function () {
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

