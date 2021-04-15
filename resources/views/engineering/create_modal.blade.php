<!-- модальное окно добавления новой страницы -->
<div class="modal" id="modalPage">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalPageTitle"> Добавить новое инженерное устройство</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="modalType" value="">

                <a href="{{ route('boiler.create') }}" >
                <img width="40" height="40" title="" src="{{ asset('ela/images/views_items/boiler.svg') }}">
                Бойлер</a>

                <br><br>

                <a href="{{ route('boiler_gvs.create') }}" >
                    <img width="40" height="40" title="" src="{{ asset('ela/images/views_items/boiler-gvs.svg') }}">
                    Бойлер ГВС</a>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_page_init_btn" style="display: none;" data-toggle="modal" data-target="#modalPage">&nbsp;</button>
