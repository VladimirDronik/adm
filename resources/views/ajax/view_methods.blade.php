<div style="height:300px; overflow:auto;">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="text-left">Название</th>
                </tr>
            </thead>
            <tbody>
                @foreach($methods as $method)
                    <tr>
                        <td class="text-left">
                            <a href="#" id="method_{{ $method->id }}"
                               onclick="selectMethod({{ $method->id }},'{{ $method->name }}', '{{ $method->params ? $method->params : '' }}')"
                               data-dismiss="modal">
                                {{$method->name}}
                                @if($method->is_need_param)
                                    <i class="fa fa-asterisk f-s-10" title="Метод с параметром"></i>
                                @endif
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function selectMethod(id, name, params, paramValue = '') {
        let view_name = '{{ $view }}';
        let arr_view = view_name.split('_');

        if (params === '') {
            //Заносим данные в таблицу с помощью ajax

            let data = {};
            data['id_method'] = id;
            data['id_view'] = arr_view[1];
            data['params'] = paramValue;

            $.ajax({
                url: '/add_method_to_view',
                data: data,
                success: function (data) {
                    //alert(data.status);
                    //   $("#objectframe").html(data.html);
                }
            });

            //Отдаем данные странице
            let html = $('#method_' + id).html();
            if (typeof html === 'undefined') {
                html = 'Отсутствует';
            } else {
                html = '<b>' + html + '</b>';
            }

            $('#{{$view}}').attr({"class": "btn btn-warning  m-b-10 btn-sm"});
            $('#{{$view}}').html(html);
            $('#{{$view}}').val(id + ',' + name + ',' + '{{$view}}');
        } else {
            $('#paramsModal #paramsMethodId').val(id);
            $('#paramsModal #paramsMethodName').val(name);
            $('#paramsModal #paramsLabel').text(params + ':');
            $('#paramsModal #param').val('');
            $('#paramsModal #params_error_text').text('');
            $('#paramsModal #params_error_div').hide();
            $('#params_modal_init_btn').click();
        }
    }
</script>