<?php
$IdCarrera=mlGetGlobalVar("IdCarrera");
$Carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$IdCarrera");
if (!$IdCarrera) {
	$Carrera="No se ha seleccionado ninguna escuela profesional.";
}
$oppcion=" ";
?>

<div class="container-fluid">
	<div class="panel">
		<h2 class="text-center">Reconsideración de Lineas de Investigación <br> <small>ESCUELA PROFESIONAL DE <?php  echo strtoupper($Carrera); ?></small></h2>
		<p> Las Lineas de Investigación, corresponde al documento enviado por la comision de grados y titulos.</p>                                                                                      
		<div class="table-responsive">          
			<table class="table">
				<?php
				$flag=1;
				foreach($lineas->result() as $row){
					$nom=$this->dbRepo->getOneField("dic_LineasVRI","Nombre","Id=$row->id_lineaV");
					?>
					<thead>
						<tr>
							<th>N°</th>
							<th><?php echo $row->Nombre ."" ;  ?></th>
							<th>OPCIONES</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$doci = $this->dbPilar->getTable( "docLineas", "IdLinea='$row->Id' order by IdDocente asc");
							$flaquis = 1;
							foreach( $doci->result() as $rina ) {
								$name=$this->dbRepo->inDocente( $rina->IdDocente );
								$tin="";
								$carrera=$this->dbRepo->inCarreDoc( $rina->IdDocente );
								if ($Carrera!=$carrera) {
									$tin=$carrera;
								}
								$butin="<button class='btn btn-xs btn-warning' onclick=\"jsLoadConfirmLin($rina->Id)\">Validar</button>";
								if($rina->Estado==0){$butin="<B style='color:red'>RECHAZADO</B>";}
								if($rina->Estado==2){$butin="<B style='color:green'>VERIFICADO</B>";}
								echo "<tr style='height: 10px;'>
								<td>$flaquis </td>
								<td><a href='javascript:void(0)' onclick=\"jsLoadModalCord($rina->IdDocente,'cordinads/jsmdlDocInfo/')\"class='btn btn-info btn-xs'>Información</a> | $name <small style='color:gray'>$tin</small></td>
								<td id='estado$rina->Id'>
									$butin
								</td>
								</tr>";
								$flaquis++;
							}
						?>
					</tbody>
					<?php 
				}
				?>
			</table>
		</div>
	</div>
	<div class="container-fluid" id="reporte">

	</div>




</div>