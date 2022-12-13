<ol class="breadcrumb">
    <small class="text-right" id="ixp">
        <?php //  $this->benchmark->elapsed_time(); ?>
        Tiempo de carga: <strong> {elapsed_time} s</strong>
    </small>
</ol>
<?php
    $IdCarrera=mlGetGlobalVar("IdCarrera");
    $Carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$IdCarrera");
    if (!$IdCarrera) {
    	$Carrera="No se ha seleccionado ninguna escuela profesional.";
	}
	$sess = $this->gensession->GetSessionData(PILAR_CORDIS); //agregado unuv1.0 - estado sorteo
?>
<h3>Proyectos de Tesis :: <small><?php  echo $Carrera; ?></small></h3>
<?php
 $sess = $this->gensession->GetSessionData(PILAR_CORDIS);
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
                    <select id="estado" name="estado" class="form-control" onchange="<?=$onsubm?>" autofocus> 
                        <option value="0">(todos)</option>
                        <?php
                        foreach ($tEstadotip->result() as  $value) {
                            $issel = ($value->Id==$estado)? "selected" : "";
                            echo "<option value='$value->Id' $issel>$value->Nombre </option>";
                        }
                        ?>
                    </select>
                </div>             

            </div>
        </fieldset>
    </form>
</div>

</div>
<div class="col-md-12">
	<div class="table-responsive">
	<table class="table table-bordered table-hover">
    <thead>
      <tr>
      	<th align="center">Nº</th>
		<th align="center">Código</th>
		<th align="center">Fecha</th>
		<th align="center">Estado</th>
		<th align="center">Revisiones</th>
		<th align="center">Opciones</th>
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
        7 => "btn-default",
        8 => "btn-success",
        9 => "btn-default",
        10 => "btn-danger",
        11 => "btn-warning",
        12 => "btn-success",
        13 => "btn-info",
        14 => "btn-default"
    );
			
		foreach( $tproys->result() as $row ) 
   		{
	    	$rowi=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite='$row->Id' ORDER BY Iteracion desc");
	    	$Rowdicestatramite = $this->dbPilar->getSnapRow( "dicestadtram", "Id=$row->Estado");  //se sgrego unuv2.0        
			$diasp  = mlDiasTranscHoy( $row->FechModif );
			$estado = "";
			$archi = base_url("/repositor/docs/$rowi->Archivo");
			$actap = base_url("pilar/tesistas/actaProy/$rowi->Id");
			$opt = "";
			$revis ="";
			
			if( $row->Tipo == 1 ) 
        	{	
        		$estado = $Rowdicestatramite->Nombre;
				switch ($row->Estado) {
					case 1:
						 $opt="<a href='$archi' class='btn btn-xs btn-info no-print' target=_blank> ver PDF </a> | ";
						//$opt .= " |  <button onclick=\"pyDirect($nro,$row->Id)\" class='btn btn-xs btn-warning'> Enviar al Asesor</button> ";
						$opt .= "  <button  onclick=\"jsLoadModalCord($row->Id,'cordinads/execEnvia/')\" class='btn btn-warning btn-xs'> Enviar al Asesor</button>  | "; //Modificacion unuv1.0 - Estado enviar proyecto al Asesor
						$opt.="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/execRechaza/')\" class='btn btn-danger btn-xs'>Rechazar</a>";	//Modificacion unuv1.0 - Estado rechazar proyecto por formato
						//$estado="Revisión de Formato";
						break;
					case 2:
						$opt="";
						//$estado="En revisión por el Asesor";
						break;
					case 3:
						//Agregado unuv1.0 - Estado sorteo de jurados
						if($sess->userLevel==4  | $sess->userLevel==1)
						{ 
							$opt.=" <a href='javascript:void(0)' onclick=popLoad(\"cordinads/execSorteo/$row->Id\",$nro) class='btn btn-xs btn-warning'> Sorteo</a>";
						}
						// $opt .= " | <button onclick='popLoad(\"admin/execSorteo/$row->Id\",$nro)' class='btn btn-xs btn-warning'> Sorteo </button>";
						//$estado="Sorteo de Jurados"; 
						break;
					case 4:
						$opt="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwProyectosMemos/')\" class='btn btn-info btn-xs'>Memo</a>";
						if($sess->userLevel==4 | $sess->userLevel==1){
							$opt.= " | <button onclick='popLoad(\"cordinads/execAprobPy/$row->Id\",$nro)' class='btn btn-xs btn-warning'> Dictaminar </button>";
						}	//agregado unuv1.0 - Estado revision 1			 
						//$estado="Revisón por Jurados (1)";
						$revis ="[ $rowi->vb1 / $rowi->vb2 / $rowi->vb3 ]";
						break;
					case 5: //agregado unuv1.0 - estado revision 2
						$opt="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwProyectosMemos/')\" class='btn btn-info btn-xs'>Memo</a>";
						if($sess->userLevel==4 | $sess->userLevel==1){
							$opt.= " |  <button onclick='popLoad(\"cordinads/execAprobPy/$row->Id\",$nro)' class='btn btn-xs btn-warning'> Dictaminar </button>";
						}	//agregado unuv1.0 - Estado revision 2			 
						//$estado="Revisón por Jurados (2)";
						$revis ="[ $rowi->vb1 / $rowi->vb2 / $rowi->vb3 ]";
						break;	
					case 6: //agregado unuv1.0 - estado revision 3
						$opt="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwProyectosMemos/')\" class='btn btn-info btn-xs'>Memo</a>";
						if($sess->userLevel==4 | $sess->userLevel==1){
							$opt.= " |  <button onclick='popLoad(\"cordinads/execAprobPy/$row->Id\",$nro)' class='btn btn-xs btn-warning'> Dictaminar </button>";
						}	//agregado unuv1.0 - Estado revision 3			 
						//$estado="Revisón por Jurados (3)";
						$revis ="[ $rowi->vb1 / $rowi->vb2 / $rowi->vb3 ]";
						break;
					case 7: //agregado unuv1.0 - estado dictamen
						if($sess->userLevel==4 | $sess->userLevel==1){
						$opt.= "<button onclick='popLoad(\"cordinads/execAprobPy/$row->Id\",$nro)' class='btn btn-xs btn-warning'> Dictaminar </button>"; 
									//$opt = "  <button onclick='popLoad(\"cordinads/execCancelPy/$row->Id\",$nro)' class='btn btn-xs btn-danger'> Rechazar </button>";  
						}
						//$estado="En Dictamación";
						$revis ="[ $rowi->vb1 / $rowi->vb2 / $rowi->vb3 ]";
						break;
					case 8:
						$opt="<a href='".base_url("pilar/tesistas/actaProy/$row->Id")."' target=_blank class='btn btn-success btn-xs'>Acta de Aprobación</a>";
						//$estado="Proyecto Aprobado";
						break;
					default:
						$opt="";
						$estado=" Eroor.....! Comunicar!";
						break;
				}

        }
         else if( $row->Tipo == 0 )
             {
                $estado='Rechazado';
             }
				$fech=strtotime($row->FechModif);
				$fech= date("d/m/Y",$fech);
				echo "<tr id='item".$nro."'>
						<td align='center'>$nro</td>
						<td align='center' style='font-size:18px;'><a href='javascript:void(0)' 
						onclick=\"jsLoadModalCord($row->Id,'cordinads/vwInfo/')\">$row->Codigo</a> </td>
						<td align='center'>$fech <br><b>$diasp dia(s)</td>
						<td align='center'>$estado</td>
						<td align='center'> $revis</td>
						<td align='center'>
							$opt
						</td>
					  </tr>";
				$nro--;
			}  
		?>
		</tbody>
	</table>
</div>
</div>


<!--Agregado unuv1.0 - sorteo de jurado------->
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
  </form>
	<div class="modal-footer">
		<button class="btn btn-success" id="popOk" onclick="popProcede('cordinads/popExec',new FormData(fX))"> Procesar </button>
		<button onclick="lodPanel('panelCord','cordinads/vwProyectos')" class="btn btn-danger" data-dismiss="modal"> Cerrar </button>
	</div>
  </div>
</div>
</div>
<!-- /MODAL  -->