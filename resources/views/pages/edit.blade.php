@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
            'title' => 'Страница «'.$page->name.'»',
            'links' => [route('pages.index') => 'Все страницы'],
            'last_link' => $page->name
        ])
@endsection

@section('content')
    <div class="container-fluid">
        @include('elements.header')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-10">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item"> <a class="nav-link @if($tab==1) active @endif"  data-toggle="tab" href="#portstab1" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Блок 1</span></a> </li>
                            <li class="nav-item"> <a class="nav-link @if($tab==2) active @endif"  data-toggle="tab" href="#portstab2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Блок 2</span></a> </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane p-20 @if($tab==1) active @endif" id="portstab1" role="tabpanel">
                                @include('elements.block1')
                            </div>
                            <div class="tab-pane p-20 @if($tab==2) active @endif" id="portstab2" role="tabpanel">
                                @include('elements.block2')
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')

    @include('elements.index_modals')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/pagescripts/element.js') }}"></script>
    <script>
        const url = '{{ route('pages.edit', [$page->id]) }}';
        const sortUrl = '{{ route('ajax.element.sort') }}';
        const deleteUrl = '{{ route('ajax.element.delete') }}';

        let del_id;
        let addingString;

        $(document).ready(function () {

            $('.del_btn').click(function () {
                del_id = $(this).data('id');

                if($(this).data('type') == 'accordeon')
                    addingString = ' и все дочерние элементы, если имеются';
                else addingString = '';

                $('#del_modal_body').text('Удалить элемент № ' + $(this).data('id') +
                    ' «' + $(this).data('name') + '»' + addingString +'?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(del);

            // add

            $('#addMenuBtn').click(function() {
                $('#modalMenu #modalMenuTitle').text('Добавить новый пункт меню');
                $('#modalMenu #modalType').val('menu');
                $('#modalMenu #modal_groups_div').show();
                $('#modalMenu #nameMenu').val('');
                $('#modal_menu_init_btn').click();
            });

            $('#addGroupBtn').click(function() {
                $('#modalMenu #modalMenuTitle').text('Добавить новую группу меню');
                $('#modalMenu #modalType').val('group');
                $('#modalMenu #modal_groups_div').hide();
                $('#modalMenu #nameMenu').val('');
                $('#modal_menu_init_btn').click();
            });
        });

        $(document).ready(function(){
            $('.active_checkbox').change(function(){
                let active = this.checked ? 1 : 0;
                let view_id = $(this).attr('data-id');

                $.ajax({
                    url: '{{ route('ajax.element.active') }}',
                    data: { '_token': _token, 'id': view_id, 'active': active},
                    success: function (data) {
                        if (data.result) {
                            showSuccessModal('Активность успешно изменена');
                        } else {
                            showErrorModal('Ошибка при изменении активности');
                        }
                    },
                });
            });
        });

    </script>
@endsection
