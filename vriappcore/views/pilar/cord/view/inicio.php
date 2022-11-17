<?php
	    $IdCarrera=mlGetGlobalVar("IdCarrera");
	    if (!$IdCarrera) {
	    	$Carrera="No se ha seleccionado ninguna escuela profesional.";
		}
	    $Carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$IdCarrera");
		$tesis=$this->dbPilar->getTable("tesTramites","IdCarrera=$IdCarrera AND Tipo = 1");
		$borrad=$this->dbPilar->getTable("tesTramites","IdCarrera=$IdCarrera AND Tipo = 2");
		$susten=$this->dbPilar->getTable("tesSustens","IdCarrera=$IdCarrera");
		$numTesis=$tesis->num_rows();
		$numBorrad=$borrad->num_rows();
		$numSusten=$susten->num_rows();
	?>
	<h2>Panel de Trabajo <small><?php  echo $Carrera; ?></small></h2> 
	<div class="col-md-12">
		<h3>Indicadores</h3>
		<div class="col-md-4">
			<div class="panel panel-default ">
			 	<div class="panel-heading text-center"><h1><?php echo $numTesis; ?></h1></div>
				<div class="panel-body">
					Proyectos de Tesis
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-default ">
			 	<div class="panel-heading text-center"><h1><?php echo $numBorrad; ?></h1></div>
				<div class="panel-body">
					Borradores de Tesis
				</div>
			</div>
		</div>
		<div class="col-md-4">	
			<div class="panel panel-default ">
			 	<div class="panel-heading text-center"><h1><?php echo $numSusten; ?></h1></div>
				<div class="panel-body">
					Sustentaciones
				</div>
			</div>
		</div>
		<h3>Lineas de Investigación</h3> 
		<div>
			<div class="table-responsive">
			<table class="table table-bordered">
					<thead>
						<tr>
							<th>Num</th>
							<th>Nombre de la Linea de Investigación</th>
							<th align="center">N° Docentes</th>
							<th align="center">N° Proyectos</th>
						</tr>
					</thead>
					<tbody>
					<?php $lineas=$this->dbRepo->getTable("tblLineas","IdCarrera='$IdCarrera' ORDER BY Estado DESC, Nombre  ASC");
						$i=1; 
						foreach($lineas->result() as $row){
							$count=$this->dbPilar->getSnapView("docLineas","IdLinea=$row->Id  AND estado='2'")->num_rows();
							if($count>=4){ 
							$nDoc=$this->dbPilar->getTable("docLineas","IdLinea=$row->Id AND estado='2'"); //se aumento que los docentes esten habilitados - unuv1.0
							$nTes=$this->dbPilar->getTable("tesTramites","IdLinea=$row->Id AND Tipo = 1 and Estado<=8"); 
							$estado='';
							if($row->Estado==0) $estado="<br><small style='color:red;'>Esta Linea de Investigación se encuentra deshabilitada.</small>";

							echo "<tr>
									<td align='center'>$i</td>
									<td>  ".$row->Nombre." $estado</td>
									<td align='center'>".$nDoc->num_rows()."</td>
									<td align='center'>".$nTes->num_rows()."</td>
								  </tr>";
							$i++;
							}
						}  
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div> 