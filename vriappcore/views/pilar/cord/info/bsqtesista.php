<div class="col-md-12">
    <br><br>
    <h4 class="text-danger">MODULO EN CONSTRUCCIÓN ... !</h4>
    <!-- <form class="form-horizontal" name="frmbusq" method="post" onsubmit='sndLoad("admin/listBusqTesi", new FormData(this),true); return false'> -->
    <form id="buste" class="form-horizontal" name="frmbusq" method="post" onsubmit='$("#panelBar").html("<center><img width=80 src=http://img6.cache.netease.com/2008/2014/4/18/201404180822583ffad.gif>");$("#panelBar").load("cordinads/listBusqTesis", $("#buste").serializeArray() );return false'>
        <fieldset>
            <!-- Text input--> 
            <div class="form-group"> 
                <label class="col-md-2 control-label"> Busqueda por codigo: </label>
                <div class="col-md-4">
                    <input name="dni" type="text" class="form-control input-md" placeholder="Ingrese DNI o Apellidos"> <!-- required -->
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-search"></span> Buscar
                    </button>
                </div>
            </div>
        </fieldset>
    </form>

    <div id="panelBar" class="alerts"></div>
</div>
