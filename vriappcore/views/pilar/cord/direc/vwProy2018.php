<?php
    $IdCarrera=mlGetGlobalVar("IdCarrera");
    $Carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$IdCarrera");
    if (!$IdCarrera) {
    	$Carrera="No se ha seleccionado ninguna escuela profesional.";
	}
?>
<h3>Proyectos de Tesis :: <small><?php  echo $Carrera; ?></small></h3>
<div class="col-md-12">
	<h4>Listado de Proyectos</h4>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Num</th>
				<th align="center">Código</th>
				<th>Fecha</th>
				<th align="center">Estado</th>
				<th align="center">Revisiones</th>
				<th align="center">Opciones</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$pyts=$this->dbPilar->getTable("tesTramites","IdCarrera='$IdCarrera' AND  Tipo='1' AND Anio>='2018' ORDER BY Estado ASC , FechModif DESC ");
			$i=1;  
			// popUp con Id Tipo
			$nro= $pyts->num_rows();
			foreach($pyts->result() as $row){
				$rowi=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite='$row->Id' ORDER BY Iteracion desc"); 

		        $estado = "";
				$archi = "/repositor/docs/$rowi->Archivo";
				$actap = base_url("pilar/tesistas/actaProy/$rowi->Id");
				$opt = "<a href='$archi' class='btn btn-xs btn-info no-print' target=_blank> ver PDF </a>";
				switch ($row->Estado) {
					case 1:
						$opt="";
						$estado="Revisión de Formato <br> <small>Responsable: Secretaria de Coord de Investigación</small>";
						break;
					case 2:
						$opt="";
						$estado="En revisión por el Asesor";
						break;
					case 3:
						$opt .= " | <button onclick='jsMdlSorteo(\"cordinads/execSorteo/$row->Id\",$nro)' class='btn btn-xs btn-warning'> Sorteo </button> ";
						$opt.="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/execRechaza/')\" class='btn btn-danger btn-xs'>Rechazar</a>";
						$estado="Sorteo de Jurados";
						break;
					case 4:
						$opt="<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/vwProyectosMemos/')\" class='btn btn-info btn-xs'>Memo</a>";
						$estado="Revisón por Jurados";
						break;
					case 5:
						$opt="";
						$estado="Dictaminación de Proyecto";
						break;
					case 6:
						$opt="<a href='".base_url("pilar/tesistas/actaProy/$row->Id")."' target=_blank class='btn btn-success btn-xs'>Acta de Aprobación</a>";
						$estado="Proyecto Aprobado";
						break;
					default:
						$opt="";
						$estado=" Eroor.....! Comunicar!";
						break;
				}
				$fech=strtotime($row->FechModif);
				$fech= date("Y-m-d",$fech);
				echo "<tr>
						<td align='center'>$nro</td>
						<td align='center' style='font-size:18px;'><a href='javascript:void(0)' 
						onclick=\"jsLoadModalCord($row->Id,'cordinads/vwInfo/')\">$row->Codigo</a> </td>
						<td align='center'>$fech</td>
						<td align='center'>$estado</td>
						<td align='center'>[ $rowi->vb1 / $rowi->vb2 / $rowi->vb3 ] </td>
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