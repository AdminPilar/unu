<div class="col-md-12 workspace">
    <center>
   <div class="admin-title col-wine"> Habilitar Proyecto de Tesis</div></center>
    <form name='frmtitu' class="form-horizontal" onsubmit='sndLoad("admin/inSaveHabilitarPro", new FormData(this),true)'>
        <input type="hidden" name="idtram" value="<?=$tram->Id?>">
        <div class="form-group">
            <label class="col-md-offset-1 col-md-1"> Titulo </label>
            <div class="col-md-9">
                <textarea name="titulo" rows="3" class="form-control" readonly><?=$dets->Titulo?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-offset-1 col-md-1"> Motivo </label>
            <div class="col-md-9">
                <textarea name="motivo" rows="3" class="form-control" required></textarea>
                <small class="form-text text-muted"> Mediante solicitud justificada Nro XX presentada el dd/mm/aa por el Sr. XXX  se habilita el proyecto de tesis.</small>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-6 col-md-3">
                <button type="submit" class="form-control btn btn-warning"> <i class="glyphicon glyphicon-save"></i> Grabar </button>
            </div>
            <div class="col-md-2">
                <button type="button" class="form-control btn btn-danger" onclick='sndLoad("admin/listBusqTesi", new FormData(frmbusq),true)'> <i class="glyphicon glyphicon-save"></i> Cancelar </button>
            </div>
        </div>
    </form>
</div>