<div id="total">
<div class="page-header">
	<h4 class="titulo" id='titulo'> Revision de Jurados <?php echo $detTram->Iteracion-4;?>  </h4>
</div>

<div class="contenido">
	<p>
       Antes de iniciar este procedimiento le recordamos que debe poner en texto de color
       <b class='text-danger'>rojo</b> con fondo blanco los parrafos o elementos que su jurado le ha indicado corregir, por ello revise bien su <b>Borrador de Tesis</b>.
    </p>
    <p>
        Para realizar las correcciónes deberá coordinar con sus jurados 
    </p>
	<p>
		<button class="btn btn-primary" onclick="lodShifs(1)">
            <span class="glyphicon glyphicon-folder-open" ></span>
			&nbsp;&nbsp; Ver mis Correcciones
        </button>

	<!-- 	<button class="btn btn-warning" onclick="alert('Presentar 4 Ejemplares en Coordinación')">
            <span class="glyphicon glyphicon-upload" ></span>
			&nbsp;&nbsp; Presentar Ejemplares en Coordinación de Investigación
        </button> -->
        <?php 
            if( $detTram->vb1 >0 && $detTram->vb2 >0 && $detTram->vb3 >0 ) { ?>
            <button class="btn btn-warning" onclick="lodShifs(2)">
            <span class="glyphicon glyphicon-upload" ></span>
            &nbsp;&nbsp; Subir PDF con Correcciones 
        </button>
         <?php } else{ ?>
          <button class="btn btn-warning" onclick="lodShifs(21)">
            <span class="glyphicon glyphicon-upload" ></span>
            &nbsp;&nbsp; Subir PDF con Correcciones
        </button>

       <?php   }
        ?>

       
        <!--<span class="label label-info"> <span class="glyphicon glyphicon-info-sign" ></span> Solo si ya no tiene observaciones.</span>-->
	</p>
	<div id="blq1" class="col-md-12" style="background: #FFFFFA">
        <p>Si usted tiene consultas sobre sus observaciones, contáctese directamente con el jurado evaluador utilizando los correos electrónicos, en la sección de <b>Líneas de Investigación</b> .</p>
		<ul class='nav nav-tabs'>
  			<li class='active'><a data-toggle='tab' href='#tab1'> Presidente </a></li>
  			<li><a data-toggle='tab' href='#tab2'> Primer Miembro </a></li>
  			<li><a data-toggle='tab' href='#tab3'> Segundo Miembro </a></li>
		</ul>
		<div class='tab-content'>

		<?php
		for( $i=1; $i<=4; $i++ ) {

			$extra = ($i==1) ? "in active" : "";
			echo "<div id='tab$i' class='tab-pane fade $extra pre-scrollable' style='height: 320px'>";
            if($i==1)
            {
                if($detTram->vb1==2)
                {
                    echo "<p><b>Aprobo, sin observaciones</b> </p>";
                }
            }
            if($i==2)
            {
                if($detTram->vb2==2)
                {
                    echo "<p><b>Aprobo, sin observaciones</b> </p>";
                }
            }
            if($i==3)
            {
                if($detTram->vb3==2)
                {
                    echo "<p><b>Aprobo, sin observaciones</b> </p>";
                }
            }
            // echo "<p>Nombres y Apellidos $i</p>";
			foreach( $arrCorr[$i]->result() as $row ) {
				$fecha = mlFechaNorm( $row->Fecha );
				echo "<p><b>[ $fecha ]</b> : $row->Mensaje </p>";
			}
			echo "</div>";
		}
		?>
		</div>
	</div>
	<!-- ----------------------------------------------------------------------------------------- -->
	<div id="blq2" class="col-md-12" style="display: none;">
	<?php

		// comprobamos que haya correcciones sin VB
		$totCorrs1 = $arrCorr[1]->num_rows();
		$totCorrs2 = $arrCorr[2]->num_rows();
		$totCorrs3 = $arrCorr[3]->num_rows();
		$totCorrs4 = $arrCorr[4]->num_rows();

		// controlar vb
		if( $detTram->vb1 >0 && $detTram->vb2 >0 && $detTram->vb3 >0 ) {

		} else {

			// si falta de alguno, muestra y termina
			if(  $totCorrs1==0 or $totCorrs2==0 or $totCorrs3==0  )
			{   
                //echo "<h4><span class='label label-danger'> <span class='glyphicon glyphicon-info-sign' ></span> Contáctese con sus jurados para completar las revisiones.</span></h4>";
				echo "<br> <b>Verificación de Correcciones:</b>";
				echo "<br>Presidente      : <b> " .($detTram->vb1==0? '' : ($detTram->vb1==1? 'Observado' : ($detTram->vb1==2?'Aprobado' : -1))). "</b>";
				echo "<br>Primer Miembro  : <b> " .($detTram->vb2==0? '' : ($detTram->vb2==1? 'Observado' : ($detTram->vb2==2?'Aprobado' : -1)))."</b>";
				echo "<br>Segundo Miembro : <b> " .($detTram->vb3==0? '' : ($detTram->vb3==1? 'Observado' : ($detTram->vb3==2?'Aprobado' : -1)))."</b>"; //modificado unuv2.0
				//echo "<br>Segundo Miembro : <b> " .($detTram->vb4==0? '' : ($detTram->vb4==1? 'Observado' : ($detTram->vb4==2?'Aprobado' : -1)))."</b>";

				return;
			}
		}
	?> 
	
	</div> 
</div>
</div>
<div id='Final' style="display: none;" class="panel panel-info">
   <div class="panel-heading">
         <h2 class="panel-title">  <b>SUBIDA DE BORRADOR DE TESIS CORREGIDO</b></h2> 
    </div>
    <div class="panel-body" id="plops">

      <div id="plock" style="display: none; z-index: 1000; position: fixed; left: 0; top: 0; width:100%; height:100%; padding: 300px; background: rgba(0,0,0,0.5)">
          <div style="margin: 0 auto; width: 320px; height: 80px; background: white; padding: 15px">
            <center> <b> Enviando datos y borrador, espere ... </b> </center>
            <div class="progress progress-striped active" style="margin-bottom:0;">
            <div class="progress-bar" style="width: 100%"></div></div>
          </div>
      </div>

      <!-- form 
      <h4><span class='label label-danger'> <span class='glyphicon glyphicon-info-sign' ></span> Solo realiza este procedimiento si es el documento FINAL.</span></h4>
      <p> Una vez registrado el borrador no hay manera de corregir el documento, usted deberá estar seguro de que el jurado ha aprobado el borrador, de lo contrario será rechazado e iniciará una nuevo trámite en 60 días reglamentarios.</p> -->
      <form class="form-horizontal" id="frmborr" method="POST" onsubmit="grabaCorrBorr(1); return false"
            accept-charset="utf-8" enctype="multipart/form-data">
          <fieldset>
              <?php
                //
                // local Id(s), IdTramite
                //
                $sess = $this->gensession->GetData();

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

              <!-- Text area -->
              <div class="form-group success">
                  <div class="col-md-4" align="left">                  
                  <label > Titulo de Proyecto (*) </label>
                  </div> 
                  <div class="col-md-7">
                      <textarea id='nomproy' name="nomproy" type="text" class="form-control" rows="3" style="text-transform: uppercase" required><?=$titulo?></textarea>
                  </div>
              </div>
              <!-- Text area -->
              <div class="form-group success">
                  <div class="col-md-4" align="left">                  
                  <label > Resumen (Abstract) (*) </label>
                  </div> 
                  <div class="col-md-7">
                      <textarea id='resumen' name="resumen" type="text" class="form-control" rows="3" placeholder="acepta varias lineas" required></textarea>
                  </div>
              </div>
              <!-- Text area -->
              <div class="form-group success">
                  <div class="col-md-4" align="left">                  
                  <label > Palabras clave (keywords) (*) </label>
                  </div> 
                  <div class="col-md-7">
                      <input id ='pclaves' name="pclaves" type="text" class="form-control input-md" placeholder="separadas por coma y acaba en punto" required>
                      <!-- <span class="help-block">  </span> -->
                  </div>
              </div>
                 <div class="form-group success">
                  <div class="col-md-4" align="left">                  
                  <label > Conclusiones : (*) </label>
                  </div> 
                  <div class="col-md-7">

                        <textarea id ='conclus' name="conclus" type="text" class="form-control" rows="3" placeholder="acepta varias lineas" required></textarea>
                      <!-- <span class="help-block">  </span> -->
                  </div>
              </div>                   
             <div class="form-group">
                  <div class="col-md-12">
                      <center>
                      <button type="button"class="btn btn-danger" onclick="lodShifs(4)">
                          <span class="glyphicon glyphicon-circle-arrow-left" ></span>
                          &nbsp; Atras
                      </button>  &nbsp; &nbsp;
                      <button type="submit" class="btn btn-primary">
                          <span class="glyphicon glyphicon-save"></span> &nbsp;Subir Archivo Corregido
                      </button>  
                      </center>                     
                  </div>
              </div>
          </fieldset>
      </form>
      <!-- form -->
    </div>
</div>
 <!------- Modal comunicado al inicio -->
      <div class="modal fade" id="msgPosterX" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content modal-pilar">
            <div class="modal-header ">
              <h4 class="modal-title">Aviso Importante</h4>
            </div>
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
               <div class="form-group success"  align="left">
                <div class="alert alert-warning col-md-12" align="left"> 
                  <small><b>Antes de subir su Borrador de Tesis Corregido,debera considerar los siguientes ITEM en su archivo PDF. </b> </small>
                </div>
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
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar </button>
            </div>
          </div>
        </div>
      </div>
      <!-------- fin Modal comunicado - descomentado en unuv2.0-->

