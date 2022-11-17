<?php
    $IdCarrera=mlGetGlobalVar("IdCarrera");
    $Carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$IdCarrera");
    if (!$IdCarrera) {
    	$Carrera="No se ha seleccionado ninguna escuela profesional.";
	}
?>
<h3>Docentes de la Escuela Profesional de <small><?php  echo $Carrera; ?></small></h3>
<div class="col-md-12">
	<h4>Listado de Docentes</h4>
	<div class="table-responsive">
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Num</th>
				<th align="center">Categoría</th>
				<th>Apellidos y Nombres</th>
				<th>Correo</th>
				<th align="center">Estado</th>
				<th align="center">Info</th>
				<th align="center">Opciones</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			$docentes=$this->dbRepo->getTable("tblDocentes","IdCarrera='$IdCarrera' ORDER BY  IdCategoria ASC,Activo DESC, Apellidos  ASC");
			$i=1; 
			foreach($docentes->result() as $row){

				// if($row->Estado==0) $estado="<br><small style='color:red;'>Esta Linea de Investigación se encuentra desbilitada.</small>";
				$cat=$this->dbRepo->getOneField("dicCategorias","Nombre","Id=$row->IdCategoria");
				$est=$this->dbRepo->getOneField("dicEstadosDoc","Nombre","Id=$row->Activo");
				$color=($row->Activo==5)? "color: green;":"color:red;";
				$color=($row->Activo==6)? "color: green;":"color:red;";
				echo "<tr>
						<td align='center'>$i</td>
						<td align='center' style='font-size:10px'>".$cat."</td>
						<td>$row->Apellidos , $row->Nombres</td>
						<td>$row->Correo</td>
						<td align='center' style='$color'>".$est."</td>
						<td align='center'>
						
						<a href='javascript:void(0)' onclick=\"jsLoadModalCord($row->Id,'cordinads/jsmdlDocInfo/')\"class='btn btn-info btn-xs'>Información</a>

						</td>
						<td align='center'><a href='javascript:void(0)'onclick='jsCordDocOpt($row->Id)'class='btn btn-warning btn-xs'>Opciones</a></td>
					  </tr>";
				$i++;
			}  
		?>
		</tbody>
	</table>
</div>
</div>