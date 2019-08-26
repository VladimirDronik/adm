@if(session()->has('success'))
    <div class="alert alert-primary alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        {{ session('success') }}
    </div>
@endif

