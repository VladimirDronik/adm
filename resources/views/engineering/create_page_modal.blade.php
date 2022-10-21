<!-- модальное окно добавления новой страницы в меню -->
<div class="modal" id="modalNewMenu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalNewMenuTitle"> Добавление новой страницы в меню для инженерного устройства</h4>
            </div>
            <div class="modal-body">
              
		Котёл успешно добавлен. 
		<br>Теперь можно автоматически создать для него пункт в инженерном меню, либо сделать это позже вручную из раздела "меню".
		

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success"  id="newmenu_success_btn" data-dismiss="modal">Добавить меню</button>
                <button type="button" class="btn btn-default" id="newmenu_cancel_btn" data-dismiss="modal">Не добавлять меню</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_newmenu_init_btn" style="display: none;" data-toggle="modal" data-target="#modalNewMenu">&nbsp;</button>
