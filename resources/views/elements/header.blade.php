<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('page.createElement', $page->id) }}" class="btn btn-success m-b-10 m-l-5">Добавить элемент
                </a>
                <a href="{{ route('pages.edit', $page->id) }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
            </div>
        </div>
    </div>
</div>