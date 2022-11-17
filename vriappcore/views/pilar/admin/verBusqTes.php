<div class="col-md-12">
<!--    <form class="form-horizontal" name="frmbusq" method="post" onsubmit='sndLoad("admin/listBusqTesi", new FormData(this),true); return false'>//-->
    <form id="buste" class="form-horizontal" name="frmbusq" method="post" onsubmit='$("#panelBar").html("<center><img width=80 src=http://img6.cache.netease.com/2008/2014/4/18/201404180822583ffad.gif>");$("#panelBar").load("admin/listBusqTesi", $("#buste").serializeArray() );return false'>
        <fieldset>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-2 control-label"> Buscar por: </label>
                <div class="col-md-4">
                    <!-- <input name="tipo" type="hidden" value="3"> -->
                    <input name="cod" type="text" class="form-control input-md" placeholder="Ingrese Codigo de Proyecto" autofocus>
                </div>
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
