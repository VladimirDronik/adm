@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Добавление отображения</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Главная</a></li>
                <li class="breadcrumb-item"><a href="/views">Отображения</a></li>
                <li class="breadcrumb-item active">Добавление</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('views.index') }}" class="btn btn-success m-b-10 m-l-5">Все отображения</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'views.store', 'method' => 'post']) !!}
                {{ csrf_field() }}
                {{ Form::bs_text('surname', 'Фамилия:') }}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/pagescripts/views.js"></script>
@endsection
