<div class="panel panel-info">
    <div class="panel-heading">
        <h2 class="panel-title"> Registro de Proyecto de Tesis </h2>
    </div>
    <div class="panel-body" id="plops">

      <div id="plock" style="display: none; z-index: 1000; position: fixed; left: 0; top: 0; width:100%; height:100%; padding: 300px; background: rgba(0,0,0,0.5)">
          <div style="margin: 0 auto; width: 320px; height: 80px; background: white; padding: 15px">
            <center> <b> Enviando datos y proyecto, espere ... </b> </center>
            <div class="progress progress-striped active" style="margin-bottom:0;">
            <div class="progress-bar" style="width: 100%"></div></div>
          </div>
      </div>

      <!-- form -->
      <form class="form-horizontal" id="frmproy" method="POST" onsubmit="grabaProy(); return false"
            accept-charset="utf-8" enctype="multipart/form-data">
          <fieldset>
              <?php
                // iterar los N tesistas a
                foreach( $tbltes as $row ) {
              ?>
              <!-- Text input-->
              <div class="form-group">
                  <label class="col-md-3 control-label" for="textinput"> Código :</label>
                  <div class="col-md-8">
                      <input value="<?=$row->Codigo?>" type="text" class="form-control input-md" disabled>
                  </div>
              </div>
              <!-- Text input-->
              <div class="form-group">
                  <label class="col-md-3 control-label" for="textinput"> Tesista :</label>
                  <div class="col-md-8">
                      <input value="<?=$row->DatosPers?>" type="text" class="form-control input-md" disabled>
                  </div>
              </div>
              <?php
                  if( $errmsg ) break;
                }
              ?>
                <br>
              <!-- Text input-->
            <!--  <div class="form-group">
                  <div class="col-md-1"></div>
                  <div class="col-md-10 alert alert-warning">
                    <strong> <?=$errmsg?> </strong>
                  </div>
                  <div class="col-md-1"></div>
              </div> Modificacion unuv1.0-->


              <!-- area de datos a almacenar en la BD -->
              <!-- select areas -->
              <div class="form-group success">
                  <label class="col-md-4 control-label" style="color:green"> Linea de Investigación </label>
                  <div class="col-md-7">
                      <select id="cbolin" name="cbolin" class="form-control" onchange="cargaDocEnLin()" autofocus required> <!-- style="background: #F0FFF0" -->
                          <option value="" disabled selected> seleccione </option>
                          <?php
                            foreach( $tlineas->result() as $row ) {
                                $count=$this->dbPilar->getSnapView("docLineas","IdLinea=$row->Id")->num_rows();
                                // if( $row->TotDoceRegs >= 7 )
                                //     echo "<option value=$row->Id> $row->Nombre - ( $row->TotDoceRegs ) </option>";
                                 if( $count>= 4)
                                    echo "<option value=$row->Id> $row->Nombre</option>";
                            }
                          ?>
                      </select>
                  </div>
              </div>

              <?php
                /*
                // habilitar alterna para los maricas de derecho
                if( $this->session->userdata('idCarre') == 22 ) {
              ?>
              <!-- select areas -->
              <div class="form-group success">
                  <label class="col-md-4 control-label" style="color: orange"> <?=lang('tes.lineaalt')?> </label>
                  <div class="col-md-7">
                      <select id="cboalt" name="cboalt" class="form-control" style="background: #F5F5C0" required onchange="tesFillCbos()">
                          <option value="0" selected> <?=lang('web.select')?> </option>
                          <?php
                            foreach( $vwLinAlt->result() as $row ){
                                if( $row->TotDoceRegs >= 4 )
                                    echo "<option value=$row->Id> $row->Nombre - ( $row->TotDoceRegs ) </option>";
                            }
                          ?>
                      </select>
                  </div>
              </div>
              <?php } */ ?>

              <!-- select areas -->
              <div class="form-group success">
                  <label class="col-md-4 control-label"> Elija Asesor </label>
                  <div class="col-md-7">
                      <select id="j4" name="jurado4" class="form-control" required>
                          <option value="" disabled selected> Seleccione </option>
                      </select>
                  </div>
                  <div class="col-md-4"></div>
                  <!-- <span class="help-block col-md-7">El Asesor de Proyecto deberá ser un docente Nombrado</span> -->
              </div>
              <!-- select areas -->
              <div class="form-group success">
                  <!--
                  <label class="col-md-4 control-label"> Jurado de Elección </label>
                  <div class="col-md-7">
                      <select id="j3" name="jurado3" class="form-control" required onchange="tesRevIgu()">
                          <option value="" disabled selected> Seleccione </option>
                      </select>
                  </div>
                  -->
                  <!--<div class="alert alert-success col-md-offset-1 col-md-10">
                      <b>Art 11.</b> El jurado dictaminador de los proyectos de tesis estará conformado
                      por tres (03) docentes de la UNA Puno, sorteados aleatoriamente a travéz de la Plataforma 
                      PILAR considerando las sublineas de investigación.
                  </div> //Modificado unuv1.0 -->
              </div>

              <!-- Text input-->
              <div class="form-group success">
                  <label class="col-md-4 control-label"> Seleccione Archivo </label>
                  <div class="col-md-7">
                      <input name="nomarch" id="nomarch" type="file" class="file form-control input-md" required>
                      <span id="filemsg" class="help-block"> <center>Puede subir un PDF con un máximo de 2MB</center> </span>
                  </div>
                  <!--
                  <input id="nomarch" name="nomarch" type="text" class="form-control input-md" required readonly>
                  <div class="col-md-2">
                      <input type="button" class="btn btn-success col-xs-12" value="Buscar" onclick="tesDownFile('pdf')">
                  </div>
                  -->
              </div>

              <!-- Text area -->
              <div class="form-group success">
                  <label class="col-md-4 control-label"> Titulo de Proyecto </label>
                  <div class="col-md-7">
                      <textarea name="nomproy" type="text" class="form-control" rows="3" style="text-transform: uppercase" required></textarea>
                  </div>
              </div>
              <!-- Text area -->
              <!-- Modificado Ing. Oliver 
              <div class="form-group success">
                  <label class="col-md-4 control-label"> Resumen (Abstract) </label>
                  <div class="col-md-7">
                      <textarea name="resumen" type="text" class="form-control" rows="3" requiered>
                      </textarea>
                  </div>
              </div>
              -->
              <!-- Text area -->
              <!-- Modificado Ing. Oliver 
              <div class="form-group success">
                  <label class="col-md-4 control-label"> Palabras clave (keywords) </label>
                  <div class="col-md-7">
                      <input name="pclaves" type="text" class="form-control input-md" requiered> 
                  </div>
              </div>
              -->
              <!-- Button (Double) -->
              <div class="form-group">
                  <div class="col-md-6"></div>
                  <div class="col-md-5">
                      <button type="submit" class="btn btn-primary col-xs-12">
                          <span class="glyphicon glyphicon-save"></span> &nbsp; Enviar Proyecto
                      </button>
                  </div>
              </div>
          </fieldset>
      </form> 
      <!-- form -->
    </div>
</div>
