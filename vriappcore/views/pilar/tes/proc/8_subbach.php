<div id="total">
<div class="contenido">
  <div id="" class="col-md-12">
    <center><img class='img-responsive' style='height:70px;' src="<?= base_url("vriadds/pilar/imag/pilar-tes.png"); ?>"/> 
    <h4 class="titulo" id='titulo'> Presentacion de Borrador de Tesis </h4> </center>
    <center>
    <h4> Su proyecto tiene <?=$dias?> dia(s) de Ejecución de un total de 180 mínimos. </h4>
    </center>
    <p>Antes de continuar con el proceso usted deberá : <br>(a) Completar el tiempo mínimo. <br>(b) Poseer el grado académico de Bachiller.<br> Si cumple con los requisitos (a) y (b) está apto para proseguir con su trámite, de lo contrario deberá esperar hasta cumplir lo estipulado. <br> <div class='alert alert-warning'><b>Nota :</b> La información registrada será responsabilidad del usuario y tienen caracter de <b>Declaración Jurada</b>, de lo contrario estará sujeto a las sanciones que determine la Universidad Nacional de Ucayali. </p></div>

    <?php $sess = $this->gensession->GetSessionData();
    $consulta=$this->dbPilar->getOneField('tesTramsBach',"Id","Estado=1 AND IdTesista= $sess->userId");
          //modificar los dias
            if ($dias>=0 AND !$consulta) { ?>
               <center>
                <button class='btn btn-lg btn-success' onclick="lodShifs(5)">
           <span class='glyphicon glyphicon-upload'></span>
      &nbsp;&nbsp; Cargar Bachiller
        </button>
    </center>
                
     <?php } ?>

  </div>
	
	</div> 

</div>
<div id='Final' style="display: none;" class="panel panel-info">
   <div class="panel-heading">
        <h2 class="panel-title">Formulario de Presentacion de Grado de Bachiller</h2>
    </div>
    <div class="panel-body" id="plops">

      <div id="plock" style="display: none; z-index: 1000; position: fixed; left: 0; top: 0; width:100%; height:100%; padding: 300px; background: rgba(0,0,0,0.5)">
          <div style="margin: 0 auto; width: 320px; height: 80px; background: white; padding: 15px">
            <center> <b> Enviando Bachiller, espere ... </b> </center>
            <div class="progress progress-striped active" style="margin-bottom:0;">
            <div class="progress-bar" style="width: 100%"></div></div>
          </div>
      </div>

      <!-- form -->
      <form class="form-horizontal" id="frmbach" method="POST" onsubmit="subBatch(); return false"
            accept-charset="utf-8" enctype="multipart/form-data">
          <fieldset>
              <div class="alert alert-info col-md-offset-1 col-md-10">
                <?php 
                $sess  = $this->gensession->GetData();
                    $name=$this->dbPilar->inTesista($sess->userId);
                ?>
                  <center><b>DECLARACIÓN JURADA</b><br></center>
                  Yo, <?=$name;?>, en amparo de lo dispuesto en el Artículo N° 41 de la Ley N° 27444, a efectos de cumplir con los requisitos y proseguir los trámites para presentar los resultados de mi investigación y defender mi borrador de tesis  <b>DECLARO BAJO JURAMENTO </b>, que los datos que adjunto en el presente formulario son auténticos y me sujeto a la normativa vigente de demostrarse lo contrario.
              </div>
              <!-- Info Área-->
              <div class="form-group success">
                  <div class="col-md-4" align="left">
                  <label class="control-label"> Tesista (*) </label>
                  </div>
                  <div class="col-md-7">
                      <input name="tesista" type="text" class="form-control input-md" disabled="" value="<?=$name;?>">
                  </div>
                  <div class="col-md-4"></div>
                  <!-- <span class="help-block col-md-7">El Asesor de Proyecto deberá ser un docente Nombrado</span> -->
              </div>

              <!-- Text area -->
              <div class="form-group success">
                   <div class="col-md-4" align="left">
                  <label class="control-label">Res. de Consejo Universitario N° (*)</label>
                  </div>
                  <div class="col-md-7">
                      <input name="rrec" type="number" title="Debe poner solo números" class="form-control" maxlength="10" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required></input>
                  </div>
              </div>
              <!-- Text area -->
              <div class="form-group success">
                  <div class="col-md-4" align="left">
                  <label class="control-label">Año de Resolución (*)</label>
                </div>
                  <div class="col-md-7">
                      <input name="anio" type="number" min="1900" max="<?php echo date("Y"); ?>" step="1" value="<?php echo date("Y"); ?>" title="Debe poner un año válido." maxlength="4" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" class="form-control" required></input>
                  </div>
              </div>
              <!-- date area -->
              <div class="form-group success">
                <div class="col-md-4" align="left">
                  <label class="control-label">Fecha de Aprobación de Proyecto (*)</label>
                </div>
                  <div class="col-md-7">
                      <input name="dater" type="date" value="" class="form-control input-md" max="<?php echo date("Y")."-".date("m")."-".date("d");?>" required>
                  </div>
              </div>

            <!-- File input-->
              <div class="form-group success">
                  <div class="col-md-4" align="left">
                  <label class=" control-label"> Seleccione Archivo  (*)</label>
                </div>
                  <div class="col-md-7">
                      <input name="nomarch" id="nomarch" type="file" class="file form-control input-md" accept="application/pdf"  required>
                      <span id="filemsg" class="help-block"> <center>Puede subir un PDF con un máximo de 1MB</center> </small></span>
                  </div>
              </div>
               <div class="form-group success"  align="left">
                <div class="alert alert-warning col-md-11" align="left"> 
                  <small><b>Nota :</b> (*) Todos los campos marcados con asterisco son obligatorios. </small>
                </div>
              </div>

              
              <!-- Button (Double) -->
              <div class="form-group">
                  <div class="col-md-12">
                      <center>
                      <button type="button"class="btn btn-danger" onclick="lodShifs(4)">
                          <span class="glyphicon glyphicon-circle-arrow-left" ></span>
                          &nbsp; Atras
                      </button>  &nbsp; &nbsp;
                      <button type="submit" class="btn btn-primary">
                          <span class="glyphicon glyphicon-save"></span> &nbsp; Enviar Mi Bachiller
                      </button>  
                      </center>                     
                  </div>
              </div>
          </fieldset>
      </form> 
      <!-- form -->
    </div>
</div>
     

