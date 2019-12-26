<div style="height:300px;overflow:auto;">
    @if(count($methods))
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th class="text-left">Название</th>
                </tr>
                </thead>
                <tbody>
                @foreach($methods as $method)
                    <tr>
                        <td>{{$method->id}}</td>
                        <td class="text-left">
                            <a href="#" id="object_{{ $method->id }}"
                               onclick="select_script({{ $method->id }}, '{{ $method->name }}')"
                               data-dismiss="modal">
                                {{ $method->name }}
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
    @else
        <p>У объекта нет методов</p>
    @endif
</div>
<script>
    function select_script(method_id, method_name) {
        $('#method_btn').html('Метод: ' + method_name);
        $('#method_id').val(method_id);
        $('#method_name').val(method_name);
    }
</script>