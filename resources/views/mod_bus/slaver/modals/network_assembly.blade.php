<!-- модальное окно сборки сети -->
<div class="modal" id="modalNetworkAssembly">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Запустить сборку сети?</h4>
            </div>
            <div class="modal-body">
                В ходе сборки шины DALI все данные о ранее добавленных устройствах удалятся.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="startNetworkAssembly">Запустить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_network_assembly_init_btn" style="display: none;" data-toggle="modal" data-target="#modalNetworkAssembly">&nbsp;</button>
