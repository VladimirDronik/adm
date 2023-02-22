<div class="card">
    <div class="card-body">
        @if(count($conditionerCodes))
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="no-border-top">
                            <th>Название</th>
                            <th>Код</th>
                            <th>Изменить</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conditionerCodes as $conditionerCode)
                        <tr>
                            <td>
                                {{ $conditionerCode->status }}
                            </td>
                            <td>
                                {{ $conditionerCode->code }}
                            </td>

                            <td>
                                <a href="/" class="btn btn-info btn-sm btn-rounded">
                                    <i class="fa fa-cog fa-lg"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
