<?php
    $IdCarrera=mlGetGlobalVar("IdCarrera");
    $Carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$IdCarrera");
    if (!$IdCarrera) {
    	$Carrera="No se ha seleccionado ninguna escuela profesional.";
	}
	$Opciones="";
	$cancelar="lodPanel('panelCord','cordinads/vwProyectos')";

	if($tipo==2)
	{
		$cancelar="lodPanel('panelCord','cordinads/vwBorradores')";
	}
?>
<h3>Borradores de Tesis :: <small><?php  echo $Carrera; ?></small></h3
<?php
//se agrego unuv2.0
 $sess = $this->gensession->GetSessionData(PILAR_CORDIS);
    if( $tipo <= 2 && $tipo!=0 ) {

        $ini = ($tipo==1)? 1 : 9;
        $fin = ($tipo==1)? 8 : 16;
        // OJO :
        // Estado = 14 para los sustentados completos
 $procesos = array (
        0 => "",
        1 => "Revisión de Formato",
        2 => "Para Asesor",
        3 => "Para Sorteo",
        4 => "Para Revisión (1)",
        5 => "Para Revisión (2)",     // 05
        6 => "Para Revisión (3)", 
        7 => "Dictaminacion",
        8 => "Proy. Aprobado",      // 06
        9 => "Presentacion Grado de Bach.",      // 10
        10 => "Revisión de Formato de Borrador",     // 11
        11 => "Revision Borrador (1)",
        12 => "Revision Borrador (2)",
        13 => "Revision Borrador (3)",
        14 => "Dictamen",
        15 => "Revision Presencial",
        16 => "Archivo Final"
    );
 

 $onsubm = "sndLoad('Cordinads/innerTrams/$tipo', new FormData(fsee) )";
?>
<div class="col-md-12">
	<div class="col-md-12">
    <form id="fsee" class="form-horizontal" onsubmit="<?=$onsubm?>; return false">
        <fieldset>
            <!-- Select Basic -->
            <div class="form-group no-print">
                <input type="hidden" name="tipo" value="<?=$tipo?>"> <!-- Kind of view -->
                <label class="col-md-1 control-label" for="selectbasic"> ESTADO </label>
                <div class="col-md-2">
                    <select id="estado" name="estado" class="form-control" onchange="<?=$onsubm?>" autofocus> <!-- required -->
                        <option value="0">(todos)</option>
                        <?php
                        for( $Id=$ini; $Id<=$fin ; $Id++  )
                        {
                            $issel = ($Id==$estado)? "selected" : "";
                            $estado1 = $procesos[ ($Id >18 or $Id <0)? 0:$Id ];
                            echo "<option value=$Id $issel> $estado1 </option>";
                        }
                        ?>
                    </select>
                </div>               

            </div>
        </fieldset>
    </form>
</div>

<?php } ?>



<div class="col-md-12">
	<div class="table-responsive">
	<table class="table table-responsive table-bordered">
		<thead>
			<tr>
				<th width="5%">N°</th>
				<th width="" align="center">Código</th>
				<th width="">Fec. Ultima Mod.</th>
				<th width="" align="center">Estado</th>
				<th width="40%">Titulo</th>
				<th width="" align="center">Revisiones</th>
				<th width="" align="center">Opciones</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		
		$nro = $tproys->num_rows();
    $proceclr = array(
        0 => "",
        1 => "btn-success",
        2 => "btn-primary",
        3 => "btn-danger",
        4 => "btn-success",
        5 => "btn-warning",
        6 => "btn-default",
        10 => "btn-danger",
        11 => "btn-warning",
        12 => "btn-success",
        13 => "btn-info",
        14 => "btn-default"
    );
    //se modifico unuv2.0

    foreach( $tproys->result() as $row ) 
   		{
				$rowi=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite=$row->Id ORDER BY Iteracion DESC");
				$rowgrado=$this->dbPilar->getOneField("testramsbach","File","IdTramite=$row->Id and IdTesista=$row->IdTesista1");
				$diasp  = mlDiasTranscHoy( $row->FechModif );
				$estado = "";
				$archi = base_url("/repositor/docs/$rowi->Archivo");
				$opt = "";
				$revisiones="";
				$grado =base_url("/repositor/bach/$rowgrado");
				$grado2="";
				if( $row->Estado >= 9 )
				$aut = "<p style='font-size:9.5px;font-weight:bold; margin-bottom: 0px'>"
				 . "TESISTA(S): ".$this->dbPilar->inTesistas($row->Id)."</p>";

				switch ($row->Estado) {
					case 9:
						$opt=""; 
						$estado="Presentacion Grado de Bach.";
						$opt="<a href='$grado' target=_blank class='btn btn-success btn-xs' title='Grado de Bachiller - Tesista 1'><span class='glyphicon glyphicon-list-alt'></span></a>";
						if($row->IdTesista2!=0)
							{
								$rowgrado=$this->dbPilar->getOneField("testramsbach","File","IdTramite=$row->Id and IdTesista=$row->IdTesista2");
								$grado2 =base_url("/repositor/bach/$rowgrado");
								$opt.=" | <a href='$grado2' target=_blank class='btn btn-success btn-xs' title='Grado de Bachiller -Tesista 2'><span class='glyphicon glyphicon-list-alt'></span> </a>";
							}
						break;
					case 10:
						$opt="";
						$estado="Revisión de Formato de Borrador";						

						$opt.="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/MostrarArchivos/')\" class='btn btn-info btn-xs' title='Archivos Subidos'><span class='glyphicon glyphicon-folder-open'></span></a> </a> | ";

						
						$opt .= "  <button  onclick=\"jsLoadModalCord($row->Id,'cordinads/execEnviaBorra/')\" class='btn btn-warning btn-xs' title='Envir a Revision' title='Enviar a Revisión'><span class='glyphicon glyphicon-send'></span></button>   "; 
						$opt .=" | <a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/execRechaza/')\" class='btn btn-danger btn-xs' title='Rechazar Borrador'><span class='glyphicon glyphicon-remove'></span></a>";


						break;
					case 11:
						$revisiones ="[ $rowi->vb1 / $rowi->vb2 / $rowi->vb3 ]";
						$days=mlDiasTranscHoy($row->FechModif);

						// Recibir Ejemplares [Temporalmente Desactivado]
						// $opt2="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwRecibirEjemplares/')\" class='btn btn-warning btn-xs btn-opt'>Recibir 4 Ejemplares [$days]</a>";
						$opt="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/MostrarArchivos/')\" class='btn btn-info btn-xs' title='Archivos Subidos'><span class='glyphicon glyphicon-folder-open'></span></a> </a> | ";
						$opt.="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwBorradoresMemos/')\" class='btn btn-info btn-xs' title='Memorandum' title='Enviar a Revisión'><span class='glyphicon glyphicon-list'></span></a>";
						$opt.="";
						if($sess->userLevel==4 | $sess->userLevel==1){
							$opt.= " | <button onclick='popLoad(\"cordinads/execAprobBorr/$row->Id\",$nro)' class='btn btn-xs btn-warning' title='Monitoriar'> <span class='glyphicon glyphicon-check'></span> </button>";
						}						
						// Solo si ya pasó mas de 10 días recibe Ejemplares
						/*if ($rowi->vb1==1 AND $rowi->vb2==1 AND $rowi->vb3==1 ) {
								$opt="$opt1 $opt2";
						}else{
							if($days>=0){
								$opt="$opt1 $opt2";
							}
							else{
								$opt="$opt1";
							}
						}*/
						$estado="Revisión Borrador (1)";
						break;
					case 12:
						$revisiones ="[ $rowi->vb1 / $rowi->vb2 / $rowi->vb3 ]";
						$days=mlDiasTranscHoy($row->FechModif);

						// Recibir Ejemplares [Temporalmente Desactivado]
						// $opt2="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwRecibirEjemplares/')\" class='btn btn-warning btn-xs btn-opt'>Recibir 4 Ejemplares [$days]</a>";
						$opt="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/MostrarArchivos/')\" class='btn btn-info btn-xs' title='Archivos Subidos'><span class='glyphicon glyphicon-folder-open'></span></a> </a> | ";
						$opt.="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwBorradoresMemos/')\" class='btn btn-info btn-xs' title='Memorandum' title='Enviar a Revisión'><span class='glyphicon glyphicon-list'></span></a>";
						$opt.="";
						if($sess->userLevel==4 | $sess->userLevel==1){
							$opt.= " | <button onclick='popLoad(\"cordinads/execAprobBorr/$row->Id\",$nro)' class='btn btn-xs btn-warning' title='Monitoriar'> <span class='glyphicon glyphicon-check'></span> </button>";
						}
						$estado="Revisión Borrador (2)";
						break;
					case 13:
						$revisiones ="[ $rowi->vb1 / $rowi->vb2 / $rowi->vb3 ]";
						$days=mlDiasTranscHoy($row->FechModif);

						// Recibir Ejemplares [Temporalmente Desactivado]
						// $opt2="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwRecibirEjemplares/')\" class='btn btn-warning btn-xs btn-opt'>Recibir 4 Ejemplares [$days]</a>";
						$opt="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/MostrarArchivos/')\" class='btn btn-info btn-xs' title='Archivos Subidos'><span class='glyphicon glyphicon-folder-open'></span></a> </a> | ";
						$opt.="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwBorradoresMemos/')\" class='btn btn-info btn-xs' title='Memorandum' title='Enviar a Revisión'><span class='glyphicon glyphicon-list'></span></a>";
						$opt.="";
						if($sess->userLevel==4 | $sess->userLevel==1){
							$opt.= " | <button onclick='popLoad(\"cordinads/execAprobBorr/$row->Id\",$nro)' class='btn btn-xs btn-warning' title='Monitoriar'> <span class='glyphicon glyphicon-check'></span> </button>";
						}
						$estado="Revisión Borrador (3)";
						break;
					case 14:
						$days=mlDiasTranscHoy($row->FechModif);

						// Recibir Ejemplares [Temporalmente Desactivado]
						// $opt2="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwRecibirEjemplares/')\" class='btn btn-warning btn-xs btn-opt'>Recibir 4 Ejemplares [$days]</a>";
						$opt="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/MostrarArchivos/')\" class='btn btn-info btn-xs' title='Archivos Subidos'><span class='glyphicon glyphicon-folder-open'></span></a> </a>";
						
						$opt.="";
						if($sess->userLevel==4 | $sess->userLevel==1){							
							$opt .= " | <button  onclick=\"jsLoadModalCord($row->Id,'cordinads/exeNotificarDictamen/')\" class='btn btn-warning btn-xs' title='Notificar Dictamen' title='Notificar Dictamen'><span class='glyphicon glyphicon-send'></span></button>   "; 
						}


						$estado="Dictamen";
						break;
					case 15:
						$opt="";
						$estado="Revision Presencial ";
						if($sess->userLevel==4 | $sess->userLevel==1){
							$opt.= "<button onclick='popLoad(\"cordinads/execAprobBorr/$row->Id\",$nro)' class='btn btn-xs btn-warning' title='Monitoriar'> <span class='glyphicon glyphicon-check'></span> </button>";
						}
						break;
					case 16:
						
						$opt="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/MostrarArchivos/')\" class='btn btn-info btn-xs' title='Archivos Subidos'><span class='glyphicon glyphicon-folder-open'></span></a> </a>";
						$estado="Archivo Final";
						break;
					default:
						$opt="";
						$estado=" Eroor.....! Comunicar!";
						break;
				}
				$fech=strtotime($row->FechModif);
				$fech= date("d/m/Y",$fech);
				if($row->Estado>=9){
				echo "<tr id='item".$nro."'>
						<td align='center'>$nro</td>
						<td align='center' style='font-size:18px;'><a href='javascript:void(0)' 
						onclick=\"jsLoadModalCord($row->Id,'cordinads/vwInfo/')\">$row->Codigo</a> </td>
						<td align='center'> $fech <br><b>$diasp dia(s) </td>
						<td align='center'>$estado</td>
						<td style='font-size:10px;' class='title-py'>$aut $rowi->Titulo</td>
						<td align='center'>$revisiones</td>
						<td align='center'>
							$opt
						</td>
					  </tr>";
				$nro--;
				}
			}  
		?>
		</tbody>
	</table>
</div>
</div>

<div id="dlgPan" class="modal" role="dialog">
<div class="modal-dialog modal-md">
  <br><br><br><br><br>
  <div class="modal-content">
	<div class="modal-header" style="background: #920738; color:white">
	  <button class="close" data-dismiss="modal" style="color:white">&times;</button>
	  <h4 class="modal-title"> COMISION GYT </h4>
	</div>
  <form name="fX" id="fX" method="post">
	<div class="modal-body" id="vwCorrs" style="font-size:13px">
		<!-- <div class="row"></div> -->

	</div>
	<div  id="notas" class="alert alert-success" role="alert">
	  Nota : Si los 3 miembros de jurados han aprobado el Borrador de Tesis, Por favor hacer clic en Procesar.
	</div>
	
  </form>
	<div class="modal-footer">
		<button class="btn btn-success" id="popOk" onclick="popProcede('cordinads/popExec',new FormData(fX))"> Procesar </button>
		<button onclick="lodPanel('panelCord','cordinads/vwBorradores')" class="btn btn-danger" data-dismiss="modal"> Cerrar </button>
	</div>
  </div>
</div>
</div>