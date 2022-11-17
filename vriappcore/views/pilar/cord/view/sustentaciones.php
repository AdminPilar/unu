<?php
    $IdCarrera=mlGetGlobalVar("IdCarrera");
    $Carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$IdCarrera");
    if (!$IdCarrera) {
    	$Carrera="No se ha seleccionado ninguna escuela profesional.";
	}
?>
<h3>Sustentaciones de Tesis :: <small><?php  echo $Carrera; ?></small></h3>
<div class="col-md-12">
	<h4>Listado de Borradores de Tesis 
	<button type="button" class="pull-right btn btn-success" data-dismiss="modal" onclick="jsLoadModalCord('','cordinads/vwPubSusten')"> Publicar Sustentación </button></h4>
	<br>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Num</th>
				<th align="center">Código</th>
				<th>Fecha</th>
				<th>Tesista</th> 
				<th align="center">Titulo</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$pyts=$this->dbPilar->getTable("tesTramites","IdCarrera='$IdCarrera' AND  Tipo='3' ORDER BY Estado");
			$i=1; 
			foreach($pyts->result() as $row){
				$rowi=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite=$row->Id");
				// $sust=$this->dbPilar->getSnapRow("tesSustentas","IdTramite=$row->Id");
				$tesista=$this->dbPilar->inTesistas("$row->Id");
				echo "<tr>
						<td align='center'>$i</td>
						<td align='center' style='font-size:18px;'><a href='javascript:void(0)' 
						onclick=\"jsLoadModalCord($row->Id,'cordinads/vwInfo/')\">$row->Codigo</a> </td>
						<td align='center'>$row->FechModif </td>
						
						 <td align='center'>$tesista</td>
						<td align='center'>$rowi->Titulo</td>
					  </tr>";
				$i++;
			}  
		?>
		</tbody>
	</table>
</div>
