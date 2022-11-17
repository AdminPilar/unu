

    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"> Validación de datos </div>
            <div class="panel-body">
                <form onsubmit="return LxPost('#dvBody','/asistencias/web/mnuReport', new FormData(this) )">
                    <div class="form-group">
                        <label for=""> Contraseña </label>
                        <input name="pass" type="password" class="form-control" autofocus required>
                    </div>
                    <div id="dvAlert"></div>
                    <hr style="margin: 7px">
                    <button type="submit" class="form-control btn-warning"> <span class="glyphicon glyphicon-search"></span> Verificar </button>
                </form>
            </div>
        </div>
    </div>
