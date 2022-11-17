<div class="panel panel-success">
    <div class="panel-heading">
        <h4>SOLICITUD DE EXPOSICIÓN Y DEFENSA <b>NO PRESENCIAL</b></h4>
    </div>
    <div class="panel-body" id="plops">

      <div id="plock" style="display: none; z-index: 1000; position: fixed; left: 0; top: 0; width:100%; height:100%; padding: 300px; background: rgba(0,0,0,0.5)">
          <div style="margin: 0 auto; width: 320px; height: 80px; background: white; padding: 15px">
            <center> <b> Enviando Bachiller, espere ... </b> </center>
            <div class="progress progress-striped active" style="margin-bottom:0;">
            <div class="progress-bar" style="width: 100%"></div></div>
          </div>
      </div>
      <p><b>Nota :</b>Los trámites grupales (2 tesistas), solo envían una solicitud. </p>
      <!-- form -->
      <form class="form-horizontal" id="frmbach" method="POST" onsubmit="solSusten(); return false"
            accept-charset="utf-8" enctype="multipart/form-data">
          <fieldset>
            <?php 
                    $name=$this->dbPilar->inTesista($sess->userId);
                ?>
              <!-- Info Área-->
              <div class="form-group success">
                  <label class="col-md-4 control-label"> Tesista : </label>
                  <div class="col-md-7">
                      <input name="tesista" type="text" class="form-control input-md" disabled="" value="<?=$name;?>">
                  </div>
              </div>

              <!-- date area -->
              <div class="form-group success">
                  <label class="col-md-4 control-label">Fecha de Dictamen :</label>
                  <div class="col-md-7">
                      <input name="dated" type="date" class="form-control input-md" required>
                  </div>
              </div>
          
              <!-- date area -->
              <div class="form-group success">
                  <label class="col-md-4 control-label">Fecha de Sustentación :</label>
                  <div class="col-md-7">
                      <input name="dates" type="date" class="form-control input-md" required>
                  </div>
              </div>

              <!-- Text area -->
              <div class="form-group success">
                  <label class="col-md-4 control-label">URL de Repositorio :</label>
                  <div class="col-md-7">
                      <input name="enlarepo" type="text" class="form-control input-md" required>
                      <span class="help-block"><b>Ejemplo :</b>http://tesis.unap.edu.pe/handle/UNAP/13534</span>
                  </div>
              </div>

            <!-- File input-->
              <div class="form-group success">
                  <label class="col-md-4 control-label"> Seleccione DIAPOSITIVAS :</label>
                  <div class="col-md-7">
                      <input name="nomarch" id="nomarch" type="file" class="file form-control input-md" required>
                      <span id="filemsg" class="help-block"> <center>Subir el PDF de su presentación con un máximo de 2MB</center> </small></span>
                  </div>
              </div>

              <!-- Button (Double) -->
              <div class="form-group">
                  <div class="col-md-6"></div>
                  <div class="col-md-5">
                      <button type="submit" class="btn btn-primary col-xs-12">
                          <span class="glyphicon glyphicon-save"></span> &nbsp; Enviar Mi Bachiller
                      </button>
                  </div>
              </div>
          </fieldset>
      </form> 
      <!-- form -->
    </div>
</div>
