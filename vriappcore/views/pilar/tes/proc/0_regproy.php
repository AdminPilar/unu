<div id="loadPy">

    <?PHP

        // echo "<div class='alert alert-danger'>";
        // echo "<b>Aviso</b> : Ha concluido el proceso de subida de proyectos de tesis nuevos. Plataforma PILAR se re-aperturará el próximo semestre 2020-I";
        // echo "</div>";

        // echo "<div class='alert alert-danger'>";
        // echo "<b>Trámites</b> : Los trámites de sorteo, cambio de jurado, validación de formato, sustentaciones continuaran una vez reprogramado el cronograma del año académico.";
        // echo "</div>";

        // exit;
    ?>

    <div class="page-header">
	    <h4 class="titulo"> ¿Usted presentará un proyecto Individual o en Equipo? </h4>
    </div>

    <div class="contenido">
        <p class="text-danger">
            Antes de iniciar este procedimiento le recordamos que su Asesor de tesis
            deberá haber revisado previamente el <b>proyecto de tesis</b> asi evitar
            el rechazo del mismo.
        </p>
        <div id="mos"  class="form-group" style="display: none" >
            <div id='me' name='me' class="col-md-12 alert alert-warning" >
                 <strong id="demo">  </strong>
            </div>
        </div>        
        <hr>
        <div class="col-md-6 btn-select">
            <button class="btn btn-default indi_group bg-1" onclick="cargaProy(1)"> Proyecto Individal</button> <!--(3.8.0)-->
        </div>
        <div class="col-md-6 btn-select">
            <button class="btn btn-default indi_group bg-2" onclick="cargaProy(2)"> Proyecto Grupal</button> <!--(3.9.0)-->
        </div>

        <div class="col-md-12">           
            <hr>
            <?php if($prev->num_rows()>0)
            {
                ?>
                <center><h5 class="titulo"> Tramites Presentados </h5></center> 
                <?php                  
                    foreach( $prev->result() as $row ){

                        $mode = ($row->Tipo == -2)? "warning" : "warning";

                        $dets = $this->dbPilar->inLastTramDet($row->Id, 3);
                        $dets = ($dets==null)? $this->dbPilar->inTramDetIter($row->Id) : $dets;
                        //$fecha= date("d/m/Y",$dets->Fecha);
                        //$fecha=date_format($dets->Fecha,"Y-m-d");

                        $dias = mlDiasTranscHoy( $dets->Fecha );

                        echo "<div class='alert alert-success'>";
                         if( $row->Tipo == 0 )
                            echo "Trámite <b>$row->Codigo : (Rechazado)</b> desde $dets->Fecha";
                        if( $row->Tipo == -2 )
                            echo "Trámite <b>$row->Codigo : (Caducado)</b>: Por haber excedido el tiempo de ejecución, transcurrieron <b>$dias de 730</b> (2 años) dias desde $dets->Fecha";
                        if( $row->Tipo == -1 )
                            echo "Trámite <b>$row->Codigo</b> : Ha sido <b>desaprobado</b> y archivado al contar con dos Jurados en desacuerdo con sus correcciones.";
                        echo "</div>";
                    }
            }
                ?>
        </div>
    </div>

</div>

<!-- Modal -->
<div id="twiceModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Proyecto de Investigación Grupal</h4>
      </div>
      <div class="modal-body">
        <p>EstimadoTesista, recuerde que una vez iniciado el trámite no podrá realizar cambios de compañero o realizar proyectos independientes con este proyecto.</p>

        	<!-- Text input-->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="textinput">Código de tu compañero:</label>
			  <div class="col-md-4">
			  <input id="textinput" name="textinput" type="text" placeholder="placeholder" class="form-control input-md">
			  <button ><span class="glyphicon glyphicon-search"></span> Buscar</button>
			  </div>
			</div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- Modal End -->