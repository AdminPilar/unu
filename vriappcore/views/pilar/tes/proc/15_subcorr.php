<div id="total" style="display: block;">
<div class="page-header">
	<h4 class="titulo" id='titulo'> Borrador de Tesis Final  </h4>
</div>

<div class="contenido">
	 <p><span class='label label-warning'> <span class='glyphicon glyphicon-info-sign' ></span> Solo realiza este procedimiento si es el documento FINAL.</span></p>
      <p> Una vez registrado el borrador no hay manera de corregir el documento, usted deberá estar seguro de que el jurado ha aprobado el borrador de Tesis, de lo contrario será rechazado e iniciará un nuevo trámite en 60 días reglamentarios.</p>
	<p>
    <p> Antes de subir su Borrador de Tesis Final,debera considerar los siguientes ITEM en su archivo PDF. Asi mismo asegúrese de que el dictamen esté incluido en el archivo</p>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
               <div class="form-group success"  align="left">                
              </div>
              <div class="item active">
                <img class="img-responsive" src="<?= base_url("vriadds/pilar/imag/img3mt/paso0.jpg"); ?>" alt="...">
              </div>
              <div class="item">
                <img class="img-responsive" src="<?= base_url("vriadds/pilar/imag/img3mt/paso1.jpg"); ?>" alt="...">
              </div>              
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
          </div>

  <p>
    <center>
		<button class="btn btn-primary" onclick="lodShifs(3)">
            <span class="glyphicon glyphicon-save" ></span>
			&nbsp;&nbsp; Subir Borrador Final
        </button>
    </center>
	</p>
	
</div>
</div> 
<div id='Final' style="display: none;">
   <div class="panel panel-info">
    <div class="panel-heading">
        <h2 class="panel-title"> <b>SUBIDA DE BORRADOR DE TESIS FINAL</b> </h2>
    </div>
    <div class="panel-body" id="plops">

      <div id="plock" style="display: none; z-index: 1000; position: fixed; left: 0; top: 0; width:100%; height:100%; padding: 300px; background: rgba(0,0,0,0.5)">
          <div style="margin: 0 auto; width: 320px; height: 80px; background: white; padding: 15px">
            <center> <b> Enviando datos, espere ... </b> </center>
            <div class="progress progress-striped active" style="margin-bottom:0;">
            <div class="progress-bar" style="width: 100%"></div></div>
          </div>
      </div>
      <!-- form -->
      <form class="form-horizontal" id="frmborr" method="POST" onsubmit="grabaCorrBorr(2); return false"
            accept-charset="utf-8" enctype="multipart/form-data">
          <fieldset>
                <?php
                //
                // local Id(s), IdTramite
                //
                $sess  = $this->gensession->GetData();

                $tram = $this->dbPilar->inTramByTesista($sess->userId);
                $autors = $this->dbPilar->inTesistas( $tram->Id );
                $titulo = $this->dbPilar->inLastTramDet( $tram->Id )->Titulo;
                $lineai = $this->dbRepo->inLineaInv( $tram->IdLinea );
              ?>

              <!-- area de datos a almacenar en la BD -->
              <!-- select areas -->
              <div class="form-group success">
                   <div class="col-md-4" align="left">
                  <label class="control-label" style="color:green"> Linea de Investigación  </label>
                  </div> 
                  <div class="col-md-7" style="padding-top:7px"> <?=$lineai?> </div>
              </div>

              <div class="form-group success">
                 <div class="col-md-4" align="left">
                  <label class="control-label"> Autor(es) </label>
                  </div> 
                  <div class="col-md-7" style="padding-top:7px"> <?=$autors?>
                  </div>
              </div>
              <hr>
              <div class="form-group success">
                  <div class="col-md-4" align="left">
                  <label class="control-label"> Informe Final de Tesis (*) </label>
                  </div>                  
                  <div class="col-md-7">
                      <input name="nomarch" id="nomarch" type="file" class="file form-control input-md" accept="application/pdf" required>
                      <span id="filemsg" class="help-block"> <center>Puede subir un PDF con un máximo de 10MB</center> </small></span>
                  </div>
              </div>

               <div class="form-group success">
                  <div class="col-md-4" align="left">                  
                  <label > Titulo de Proyecto (*) </label>
                  </div> 
                  <div class="col-md-7">
                      <textarea name="nomproy" type="text" class="form-control" rows="3" style="text-transform: uppercase" required><?=$titulo?></textarea>
                  </div>
              </div>
              <!-- Text area -->
              <div class="form-group success">
                  <div class="col-md-4" align="left">                  
                  <label > Resumen (Abstract) (*) </label>
                  </div> 
                  <div class="col-md-7">
                      <textarea name="resumen" type="text" class="form-control" rows="3" placeholder="acepta varias lineas" required></textarea>
                  </div>
              </div>
              <!-- Text area -->
              <div class="form-group success">
                  <div class="col-md-4" align="left">                  
                  <label > Palabras clave (keywords) (*) </label>
                  </div> 
                  <div class="col-md-7">
                      <input name="pclaves" type="text" class="form-control input-md" placeholder="separadas por coma y acaba en punto" required>
                      <!-- <span class="help-block">  </span> -->
                  </div>
              </div>
                 <div class="form-group success">
                  <div class="col-md-4" align="left">                  
                  <label > Conclusiones : (*) </label>
                  </div> 
                  <div class="col-md-7">

                        <textarea name="conclus" type="text" class="form-control" rows="3" placeholder="acepta varias lineas" required></textarea>
                      <!-- <span class="help-block">  </span> -->
                  </div>
              </div>
              <div class="form-group success">
                  <div class="col-md-12" align="left">                  
                  <span class='label label-warning'> <span class='glyphicon glyphicon-info-sign' ></span> Asegúrese de que el dictamen esté incluido en el archivo.</span>
                  </div>
              </div>
               

              <div class="form-group">
                  <div class="col-md-12">
                      <center>
                      <button type="button"class="btn btn-danger" onclick="lodShifs(4)">
                          <span class="glyphicon glyphicon-save" ></span>
                          &nbsp; Atras
                      </button>  &nbsp; &nbsp;
                      <button type="submit" class="btn btn-primary">
                          <span class="glyphicon glyphicon-save"></span> &nbsp; Enviar Proyecto
                      </button>  
                      </center>                     
                  </div>
              </div>
          </fieldset>
      </form> 
      <!-- form -->
    </div>
</div>
</div>