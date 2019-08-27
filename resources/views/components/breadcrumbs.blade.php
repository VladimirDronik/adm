<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-primary">{{ $title }}</h3></div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            @foreach($links ?? [] as $link => $name)
                <li class="breadcrumb-item"><a href="{{ $link }}">{{ $name }}</a></li>
            @endforeach
            <li class="breadcrumb-item active">{{ is_null($last_link ?? null) ? $title : $last_link}}</li>
        </ol>
    </div>
</div>