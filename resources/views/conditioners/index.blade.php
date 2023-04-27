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
                                <th>Размещение</th>
                                <th>Производитель</th>
                                <th>Модель</th>
                                <th>Доступность</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($conditioners as $conditioner)
                                <tr id="tr{{$conditioner->id}}">
                                    <td scope="row">{{ $conditioner->id }}</td>
                                    <td> {{ $conditioner->room->name }} </td>
                                    <td>
                                        {{ $conditioner->conditionerModel->conditionerVendor->name }}</a>
                                    </td>
                                    <td>
                                        {{ $conditioner->conditionerModel->name }}
                                    </td>

                                    <td>
                                        @if( $conditioner->status  === '1')
                                            <span class="badge badge-success">Активно</span>
                                        @else
                                            <span class="badge badge-danger">Недоступно</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('conditioners.edit',[$conditioner->id]) }}"
                                           class="btn btn-info btn-sm btn-rounded">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-danger btn-sm btn-rounded m-b-10 m-l-5 del_btn"
                                                data-id="{{ $conditioner->id }}" data-name="{{ $conditioner->name }}">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Размещение</th>
                                <th>Производитель</th>
                                <th>Модель</th>
                                <th>Доступность</th>
                                <th></th>
                                <th></th>
                            </tr>
                            </tfoot>
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
    <script src="{{ asset('ela/js/pagescripts/page.js') }}"></script>
    <script>
        const deleteUrl = '{{ route('ajax.engineering.delete') }}';
        const addMenuUrl = '{{ route('ajax.menu.add') }}';
        //const storeUrl = '{{ route('ajax.page.store') }}';
  
        let url = '{{ route('engineering.index') }}';
        let del_id;

 @if(!empty(Session::get('success')) && Session::get('success') == 'Котёл успешно добавлен')
	let idObject = '{{ Session::get('idObject') }}';


              $('#modalNewMenu').show();
              $('#modal_newmenu_init_btn').click();
	@endif

        $(document).ready(function(){

            $('.del_btn').click(function () {

                del_id = $(this).data('id');

                $('#del_modal_body').text('Удалить оборудование № ' + $(this).data('id') +
                    ' «' + $(this).data('name') + '»?');
                $('#del_init_btn').click();

            });


            $('#del_modal_btn').click(del);


            $('#addPageBtn').click(function() {
                $('#modalPage #modal_groups_div').show();
                $('#modalPage #namePage').val('');
                $('#modal_page_init_btn').click();
            });
            
           
            
            $('#newmenu_success_btn').click(function() {
            addMenu(idObject);
            });
            

        });
        
       

        
    </script>
@endsection
