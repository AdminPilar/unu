<div class="col-md-12 workspace">
    <hr>
    <form name='frmtitu' class="form-horizontal" onsubmit='sndLoad("admin/inSaveRenun", new FormData(this),true)'>
        <input type="hidden" name="idtram" value="<?=$idtram?>">
        <div class="form-group">
            <label class="col-md-offset-1 col-md-1"> Titulo </label>
            <div class="col-md-9">
                <textarea name="titulo" rows="3" class="form-control" readonly><?=$titulo?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-offset-1 col-md-1"> Motivo </label>
            <div class="col-md-9">
                <textarea name="motivo" rows="3" class="form-control" required></textarea>
                <small class="form-text text-muted"> Mediante solicitud Nro XX presentada el dd/mm/aa por el Sr. XXX Renuncia al proyecto de tesis.</small>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-6 col-md-3">
                <button type="submit" class="form-control btn btn-warning"> <i class="glyphicon glyphicon-save"></i> Grabar Renuncia </button>
            </div>
            <div class="col-md-2">
                <button type="button" class="form-control btn btn-danger" onclick='sndLoad("admin/listBusqTesi", new FormData(frmbusq),true)'> <i class="glyphicon glyphicon-save"></i> Cancelar </button>
            </div>
        </div>
    </form>
</div>