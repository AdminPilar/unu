<div class="col-md-12 workspace">
    <hr>
    <form class="form-horizontal" onsubmit='sndLoad("admin/inSavePass", new FormData(this),true)'>
        <input type="hidden" name="idte" value="<?=$idtes?>">
        <div class="form-group">
            <label class="col-md-offset-1 col-md-2"> Numero de DNI </label>
            <div class="col-md-8">
                <input name="ldni" type="number" class="form-control" placeholder="Solo dígitos">
                <small class="form-text text-muted"> Solo si desea cambiarlo </small>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-offset-1 col-md-2"> Correo electrónico </label>
            <div class="col-md-8">
                <input name="mail" type="email" class="form-control" placeholder="Ejm. mi_correo@do.com">
                <small class="form-text text-muted"> Solo si desea cambiarlo </small>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-offset-1 col-md-2"> Nueva contraseña </label>
            <div class="col-md-8">
                <input name="pass" type="password" class="form-control" placeholder="Ejm. ***">
                <small class="form-text text-muted"> Solo si desea cambiarlo </small>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-offset-6 col-md-3">
                <button type="submit" class="form-control btn btn-success"> <i class="glyphicon glyphicon-save"></i> Grabar Cambios </button>
            </div>
            <div class="col-md-2">
                <button type="button" class="form-control btn btn-danger" onclick='sndLoad("admin/listBusqTesi", new FormData(frmbusq),true)'> <i class="glyphicon glyphicon-save"></i> Cancelar </button>
            </div>
        </div>
    </form>
</div>