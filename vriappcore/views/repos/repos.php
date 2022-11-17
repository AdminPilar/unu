
<div class="modal fade" id="msgPosterX" role="dialog">
    <div class="modal-dialog">

      <div class="modal-content">
        <div class="modal-header" style="background: red">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"> Mis TESIS ESCANEADAS TIO !!! </h4>
        </div>
        <div class="modal-body">
            <iframe frameBorder=0 width="100%" height="320" src="http://upsc.edu.pe/docentes/rpedrolm/tesis">
            </iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
</div>

<script>
$(document).ready(function(){
	///$("#msgPosterX").modal('show');
});
</script>


<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" rel="home" href="<?=base_url("pilar")?>" title="Universidad Nacional de Ucayali | Vicerrectorado de Investigación">
                <img class="img-responsive" style="max-width:160px; margin-top: -15px;"
                     src="<?=base_url("vriadds/pilar/imag/logos-u-v-p.png");?>">
            </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?=base_url()?>"> VRI Home </a></li>
                <li><a href="<?=base_url("pilar")?>"> PILAR </a></li>
                <li><a href="<?=base_url("pilar/sustentas")?>"> Sustentaciones </a></li>
                <li><a href="<?=base_url("cursos/web")?>"> Cursos </a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container info-pilar margin" style="height: 23%">
  <img class="img-responsive logo-pilar3" src="<?=base_url("vriadds/pilar/imag/pilar-n.png");?>">
</div>
<div class="" style="height: 3px; background:rgb(149,0,68)"> </div>


<?php

    $panTitulo = "Control de Acceso";
    $panNotics = "Repositorio Institucional";

    $sess = $this->gensession->GetData( REPO_ADMIN );
    if( $sess ) {
        $panTitulo = "MENÚ";
        $panNotics = "Panel de Control";
    }

?>

<div class="container ">
  <div class="col-md-12 contenido1">
      <div class="col-md-3 hidden-print" style="color: black">

          <div class="panel panel-info">
              <div class="panel-heading" id="hmenu">
                  <h2 class="panel-title"> <img src="../cursos/web/includefile/imgs/menu-icon.png" height="25">  
                    <?=$panTitulo?>
                  </h2>
              </div>
              <div class="panel-body well" id="bmenu" style="margin-bottom: 0px;">
              <?php if( ! $sess ) { ?>
                  <form class="form-horizontal" id="login" method="POST" action="admin/login">
                      <fieldset>
                          <!-- Text input-->
                          <div class="form-group">
                              <label class="col-md-4 control-label" for="textinput"> USUARIO </label>
                              <div class="col-md-8">
                                  <input name="user" type="text" placeholder="usuario" class="form-control input-md" required autofocus>
                              </div>
                          </div>
                          <!-- Text input-->
                          <div class="form-group">
                              <label class="col-md-4 control-label" for="textinput"> CLAVE </label>
                              <div class="col-md-8">
                                  <input name="pass" type="password" placeholder="*****" class="form-control input-md" required>
                                  <!-- <span class="help-block"><strong>Verifique</strong> / <small>escriba con calma</small></span> -->
                              </div>
                          </div>
                          <!-- Button (Double) -->
                          <div class="form-group">
                              <div class="col-md-6"> <small>Nota: la clave es sagrada</small> </div>
                              <div class="col-md-6">
                                  <input type="submit" class="btn btn-info col-xs-12" value="Ingresar">
                              </div>
                          </div>
                      </fieldset>
                  </form>
              <?php } else { ?>
                  <div class="list-group">
                      <a class="list-group-item" href="admin"><span class="glyphicon glyphicon-home"></span> INICIO </a>
                      <a class="list-group-item" onclick="repoLoad('dvDisplay','admin/jsSeeRepo',null)" href="javascript:void(0)"><span class="glyphicon glyphicon-chevron-right"></span> VER LISTADO </a>
                      <a class="list-group-item" onclick="repoLoad('dvDisplay','admin/repolista',null)" href="javascript:void(0)"><span class="glyphicon glyphicon-chevron-right"></span> REPORTE </a>
                      <br>
                      <a class="list-group-item" href="admin/logout"><span class="glyphicon glyphicon-chevron-right"></span> SALIR </a>
                  </div>
              <?php } ?>
              </div>
          </div>

      </div> <!-- end col-md-4 -->

      <!-- begin -->
      <div class="col-md-9 bg-white">

        <div class="titulo"> <?=$panNotics?> </div>
        <div id="dvDisplay" class="alert alert-custom">
        <?php if( ! $sess ) { ?>
            <p>
                El Repositorio Institucional Digital de la Universidad Nacional de Ucayali,
                tiene como objetivo facilitar y mejorar la visibilidad de la producción cientifica y académica
                permitiendo el acceso abierto a sus contenidos y garantizando la preservación y
                mantenimiento de dicha producción, asegurando el acceso de la comunidad universitaria.
            </p>
            <small>
                <br> <b>M.Sc. Leonel Coyla Idme</b>
                <br> Jefe de Repositorio
                <br> <b>Ing. Romel Percy Melgarejo Bolivar</b>
                <br> Administrador de Base de Datos
            </small>
        <?php } else { ?>
            <form id="frmtes" name="frmtes" method=post onsubmit="return repoLoad('dvDispIn','admin/jsBusqa',new FormData(frmtes))" class="form-horizontal">
              <fieldset>
                <!-- Text input-->
                <div class="form-group" style="border: 0px solid red; margin-bottom: 0px;">
                    <label class="col-md-2 control-label" for="textinput"> Verificación </label>
                    <div class="col-md-3">
                        <input id="codtes" name="codtes" type="number" placeholder="Código" class="form-control input-md" required>
                        <!-- <span class="help-block"> <small> digite código </small></span> -->
                    </div>
                    <div class="col-md-3">
                        <input id="dnites" name="dnites" type="number" placeholder="número de DNI" class="form-control input-md">
                        <!-- <span class="help-block"> <small> digite DNI </small></span> -->
                    </div>
                    <!-- Button (Double) -->
                    <div class="col-md-2">
                        <input type="submit" class="btn btn-primary col-xs-12" value="Buscar">
                    </div>
                    <div class="col-md-2">
                        <input type="button" class="btn btn-success col-xs-12" value="Listar" onclick="repoLoad('dvDisplay','admin/jsSeeRepo',null)">
                    </div>
                </div>
              </fieldset>
            </form> <br>
            <div id="dvDispIn">
                <small> Esperando resultados ... </small>
            </div>
        <?php } ?>
        </div>
      </div>
      <!-- end -->

      <div class="col-md-12 bg-vino footer">
        Universidad Nacional de Ucayali<br>
        Vicerrectorado de Investigación<br>
        Dirección General de Invesatigación<br>
        &copy; Plataforma de Investigación y Desarrollo</a>
      </div>
  </div>
</div>



<!-- MODAL  -->
<div class="modal" role="dialog" id="pdfDlg">
<div class="modal-dialog modal-lg">
  <!-- Modal content-->
  <div class="modal-content modal-pilar">
    <div class="modal-header modal-pilar-title">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"> Defensa de Tesis </h4>
    </div>
        <div class="modal-body modal-pilar modal-pilar-content">
            <iframe id="iframe" height=610 width=100% frameborder=0 src=""></iframe>
            <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar Aviso </button>
        </div>
      <!--
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar Aviso </button>
    </div> -->
  </div>

</div>
</div>
<!-- /MODAL  -->