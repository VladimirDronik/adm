<!-- footer -->
<footer class="footer"> © 2019 All rights reserved. TouchOn Technology inc. To more info visit:
    <a href="https://touchon.tech" target="_blank">touchon.tech</a>
    @if(\Illuminate\Support\Facades\App::environment('local'))
        <a href="{{ route('generate.fake') }}" class="btn btn-outline-info pull-right" title="Сброс бд: заново выполнение миграций и заполнение тестовыми данными">Сгенерировать тестовые данные</a>
    @endif
</footer>
<!-- End footer -->