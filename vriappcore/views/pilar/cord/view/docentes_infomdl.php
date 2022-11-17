<!-- Modal content-->
<div class="modal-content">
	<div class="modal-header" style="background: #920738; color:white">
	  <button class="close" data-dismiss="modal" style="color:white">&times;</button>
	  <h4 class="modal-title"> Informacion Docente  </h4>
	</div>
	<div class="modal-body">
		<?php 
			$dat=$this->dbRepo->getSnapRow("tblDocentes","Id=$IdDocente");
			$grados=$this->dbPilar->getSnapView("docEstudios","IdDocente=$IdDocente");
			$lineas=$this->dbPilar->getSnapView("docLineas","IdDocente=$IdDocente");
			echo "<h4 class='text-center'>$dat->Apellidos, $dat->Nombres</h4>";
			echo "
						<table class='table table-bordered'>
						<thead>
							<tr>
								<th width='35%'>Item</th>
								<th>Detalle</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>DNI</td>
								<td> $dat->DNI</td>
							</tr>
							<tr>
								<td>CORREO</td>
								<td> $dat->Correo</td>
							</tr>
							<tr>
								<td>CELULAR</td>
								<td> $dat->NroCelular</td>
							</tr>
							<tr>
								<td>Codigo</td>
								<td> $dat->Codigo</td>
							</tr>
							<tr>
								<td>Fecha de Nacimiento</td>
								<td> $dat->FechaNac</td>
							</tr>
							<tr>
								<td>Fecha de Contrato</td>
								<td> $dat->FechaIn</td>
							</tr>
							<tr>
								<td>Grados y Títulos</td>
								<td>
									<ol style='padding:0px 10px;'>";
										$f=1;
										foreach ($grados->result() as $gra) {
											echo strtoupper("<li style='font-size:11px;'>$gra->AbrevGrado/$gra->Mencion</li>");
											$f++;
										}
			echo "		 			</ol>
								</td>
							</tr>
							<tr>
								<td>Lineas de Invesigación</td>
								<td>
									<ol style='padding:0px 10px;'>";
										$f=1;
										foreach ($lineas->result() as $lin) {
											$nameLin=$this->dbRepo->inLineaInv($lin->IdLinea);
											$estLin=$this->dbRepo->getOneField("tblLineas","Estado","Id=$lin->IdLinea");
											$style=($estLin==0)?"color:red;":"color:green;";
											echo "<li style='font-size:11px;$style'>$nameLin</li>";
											$f++;
										}
			echo "		 			</ol>

								</td>
							</tr>
						</tbody>
				  </table>
			";

		?>
		<!--<h5> Enviar Recordatorio de Actualización de Información : <button type="button" class="btn btn-xs btn-primary"> <span class="glyphicon glyphicon-envelope"></span> Enviar MSJ</button> </h5> -->
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar</button>
	</div>
</div>
<!-- //Modal content-->