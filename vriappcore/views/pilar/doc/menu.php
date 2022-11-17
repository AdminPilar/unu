<!-- Menu de Docente  -->
  <div class="col-md-2 col-sm-3 sidemenu">
    <div class="docente-info">
        <h4 class="docente-titulo"> BIENVENIDO A <span>PILAR</span></h4>
         <!-- <img class="img-responsive  img-circle docente-img" src="<?= base_url("vriadds/pilar/imag/pilar-user.png");?>" alt="Docente - PILAR"> -->

        <center>
        <img class="img-responsive  img-circle docente-img" src="<?= base_url("vriadds/pilar/imag/pilar-user.png");?>"
        <?PHP
            
            /*if( $media = $this->genapi->getDataPer($sess->userDNI) )
                echo "<img width=110 src='$media->foto' class='img-responsive'><hr style='margin: 8px; border: 1px dotted gray'>";
            else
                echo ">>Sin imagen<< <hr>";//Modificado unuv1.0*/

        ?>
        <h3 class="docente-name"> <?php echo $this->dbPilar->after(",","$sess->userName"); echo "<br>".$sess->userMail; ?> </h3> <h5 class="docente-cargo">Docente Universitario</h5>
    </div>

    <div class="list-group">
      <br><br>
      <ul class="nav nav-pills bderecha">
        <a href="<?=base_url("pilar/docentes");?>" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Inicio </a>
        <a onclick="$('#panelView').load('docentes/infoDocente')"  href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-user"></span> Mis Datos y Pérfil </a> <!--4.2.0 -->
        <a onclick="lodPanel('panelView','docentes/infoTrams/1')"  href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-th-list"></span> Proyectos de Tesis</a>
        <a onclick="lodPanel('panelView','docentes/infoTrams/2')"  href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Borradores de Tesis</a>
        
        <a onclick="lodPanel('panelView','docentes/infoTrams/3')" href="javascript:void(0)" class="list-group-item" ><span class="glyphicon glyphicon-camera"></span> Sustentaciones</a>        
        <hr>
        <a class="list-group-item" onclick="Enlaces()" href="javascript:void(0)"><span class="glyphicon glyphicon-book"></span> Enlaces Directos</a>
        <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/Manual de docente_v1.pdf");?>" class="list-group-item blink"><span class="glyphicon glyphicon-book"></span> Manual de Docentes</a>
        <!-- lodPanel('panelView','docentes/infoConsta') -->
        <!--<a onclick="lodPanel('panelView','docentes/infoTrams/3')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Constancias</a> -->


      <!--  <a  href="<?=base_url('tramiteonline');?>" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Nuevos Reglamentos </a> -->
        <hr>
    <!--     <a onclick="lodPanel('panelView','docentes/programaLaspau')" href="javascript:void(0)" class="list-group-item"><center></span>Convocatoria <br> LASPAU </center>
            <img class="img-responsive pull-left vri-logo-small" src= "<?php //echo base_url("vriadds/vri/web/laspau.png");?>" >
        </a> -->
      </ul>
    </div>
  </div>
  <div class="modal fade" id="msgPosterX" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content modal-pilar">
            <div class="modal-header modal-pilar-title" style="background-color:#1c679c">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" style="color:#FFFFFF">Enlaces Directos</h4>
            </div>
            <div class="modal-body" style="padding: 10px">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th >URL</th>
                      <th >Descripcion</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    <td>
                      <a href="https://www.urkund.com/es/inicio-de-sesion/" target="_blank"><img class="img-responsive"  src="<?php echo base_url("vriadds/pilar/imag/herram/urkund.jpg");?>"></a>
                    </td>
                    <td>Urkund es un sistema de reconocimiento de texto de aprendizaje automatizado diseñado para detectar, prevenir y gestionar el plagio, con independencia del idioma en que esté escrito el texto</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar </button>
            </div>
          </div>
        </div>
      </div>
<!-- /Menu de Docente  -->
