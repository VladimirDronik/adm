<!-- модальное окно добавления нового параметра -->
<div class="modal" id="modalPage">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalPageTitle"> Выберите тип создаваемого параметра</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="modalType" value="">

                <a href="{{ route('settings.create') }}" ><i class="fa fa-cog fa-lg"></i>&nbsp;&nbsp;Стандартный параметр</a>

                <br><br>

                <a href="{{ route('time_zone.create') }}" ><i class="fa fa-clock-o fa-lg"></i>&nbsp;&nbsp;Параметр часового пояса</a>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_page_init_btn" style="display: none;" data-toggle="modal" data-target="#modalPage">&nbsp;</button>
