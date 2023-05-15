@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Настройка сети'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    @if(!isset($main_network) || !isset($network))
                        <p class="text-danger">Не удалось загрузить данные</p>
                    @else
                    {!! Form::model((object)[], ['route' => ['network.update'], 'method' => 'put', 'class' => 'form-horizontal form-bordered', 'id' => 'form']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_title('Основной адрес сервера') }}

                        {{ Form::bs_text('main_ip', 'IP*:', old('main_ip', $main_network[0]), ['required' => true]) }}
                        {{ Form::bs_text('main_mask', 'Маска*:', old('main_mask', $main_network[1]), ['required' => true]) }}
                        {{ Form::bs_text('main_gateway', 'Шлюз*:', old('main_gateway', $main_network[2]), ['required' => true]) }}

                        {{ Form::bs_title('Адрес для подсети устройств') }}
                        {{ Form::bs_text('ip', 'IP*:', old('ip', $network[0]), ['required' => true]) }}
                        {{ Form::bs_text('mask', 'Маска*:', old('mask', $network[1]), ['required' => true]) }}

                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                    @endif

                </div>
            </div>
        </div>
    </div>
    @include('components.confirm_modal')
@endsection

@section('scripts')
    @if(isset($main_network) && isset($network))
    <script>
        let data = {};
        data.main_ip = '{{ $main_network[0] }}';
        data.main_mask = '{{ $main_network[1] }}';
        data.main_gateway = '{{ $main_network[2] }}';
        data.ip = '{{ $network[0] }}';
        data.mask = '{{ $network[1] }}';

        $(document).ready(function(){

            function getChanges() {
                let html = '';
                let main_ip = $('input[name=main_ip]').val().trim();
                let main_mask = $('input[name=main_mask]').val().trim();
                let main_gateway = $('input[name=main_gateway]').val().trim();
                let ip = $('input[name=ip]').val().trim();
                let mask = $('input[name=mask]').val().trim();

                if (main_ip !== '' && data.main_ip !== main_ip) {
                   html += 'IP основного адреса изменен с '+data.main_ip+' на '+main_ip+'<br>';
                }
                if (main_mask !== '' && data.main_mask !== main_mask) {
                    html += 'Маска основного адреса изменена с '+data.main_mask+' на '+main_mask+'<br>';
                }
                if (main_gateway !== '' && data.main_gateway !== main_gateway) {
                    html += 'Шлюз основного адреса изменен с '+data.main_gateway+' на '+main_gateway+'<br>';
                }
                if (ip !== '' && data.ip !== ip) {
                    html += 'IP адреса для подсети изменен с '+data.ip+' на '+ip+'<br>';
                }
                if (mask !== '' && data.mask !== mask) {
                    html += 'Маска адреса для подсети изменена с '+data.mask+' на '+mask+'<br>';
                }
                return html;
            }

            function showConfirmModal() {
                let html = getChanges();
                html += '<br><b>Устройство будет доступно по адресу: '
                    +$('input[name=main_ip]').val()+'</b>';
                $('#confirm_modal_body').html(html);
                $('#confirm_modal_btn').text('Перезагрузить');
                $('#confirm_init_btn').click();
            }

            $('button[type=submit]').click(function(){
                showConfirmModal();
                return false;
            });

            $('#confirm_modal_btn').click(function(){
                $('#form').submit();
            });

        });
    </script>
    @endif
@endsection
