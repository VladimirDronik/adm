@if(count($elements))
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th style="width: 50px;">ID</th>
                <th style="width: 300px;">Название</th>
                <th>Тип</th>
                <th>Значение</th>
                <th>Активно</th>
                <th class="text-center">Сортировка</th>
                <th class="text-center" style="width: 80px;"></th>
                <th class="text-center" style="width: 80px;"></th>
            </tr>
            </thead>

            @foreach($elements as $element)
                @if($element->position == '2')
                    @include('elements.tablebody')
                @endif
            @endforeach

            @if(count($elements) > 10)
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Тип</th>
                    <th>Значение</th>
                    <th>Активно</th>
                    <th>Сортировка</th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>
            @endif
        </table>
    </div>

    {{ $elements->appends(request()->input())->links() }}
    <p class="text-right">Найдено: {{ $elements->total() }}</p>
@else
    <p class="mt-3">Элементы не найдены</p>
@endif