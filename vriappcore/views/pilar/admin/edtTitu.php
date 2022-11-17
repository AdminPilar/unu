<div class="col-md-12 workspace">
    <hr>
    <form name='frmtitu' class="form-horizontal">
        <input type="hidden" name="idtram" value="<?=$idtram?>">
        <div class="form-group">
            <label class="col-md-offset-1 col-md-1"> Titulo </label>
            <div class="col-md-9">
                <textarea name="titulo" rows="4" class="form-control"><?=$titulo?></textarea>
                <small class="form-text text-muted"> Revise errores gramáticales, seguidamente proceda a guardar </small>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-offset-6 col-md-3">
                <button type="submit" class="form-control btn btn-success" onclick='sndLoad("admin/inSaveTitu", new FormData(frmtitu),true)'> <i class="glyphicon glyphicon-save"></i> Grabar Cambios </button>
            </div>
            <div class="col-md-2">
                <button type="button" class="form-control btn btn-danger" onclick='sndLoad("admin/listBusqTesi", new FormData(frmbusq),true)'> <i class="glyphicon glyphicon-save"></i> Cancelar </button>
            </div>
        </div>
    </form>
</div>