<div class="row">
    <div class="col col-md-8">
        <h4>ID {{ $carbdioxide->id_object }}: {{ $carbdioxide->name }}</h4>
    </div>
    <div class="col col-md-4">
        <select class="form-control select_period" id="select_period{{$carbdioxide->id}}" autocomplete="off" data-id="{{ $carbdioxide->id }}">
            <option value="7" selected>за последние 7 дней</option>
            @foreach($periods as $key => $period)
                <option value="{{ $key }}">{{ $period }}</option>
            @endforeach
        </select>
    </div>
</div>
