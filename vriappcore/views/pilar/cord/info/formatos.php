<?php
	    $IdCarrera=mlGetGlobalVar("IdCarrera");
	    if (!$IdCarrera) {
	    	$Carrera="No se ha seleccionado ninguna escuela profesional.";
		}
		$Carrera=$this->dbRepo->inCarrera($IdCarrera);
		$this->load->helper(array('form', 'url'));
	?>
	<h2>Panel de Trabajo <small><?php  echo $Carrera; ?></small></h2> 
	<div class="col-md-12">
		
		<h3>FORMATOS :  <?php  echo $Carrera; ?></h3> 
		<div>
			<table class="table table-bordered">
					<thead>
						<tr>
							<th>N°</th>
							<th>Nombre de Formato</th>
							<th align="center">Archivo</th>
							<th align="center">Fecha</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
		</div>

		<h3>CARGAR NUEVO FORMATO</h3>

			<?php echo $error;?>
			<?php echo form_open_multipart('pilar/cordinads/do_upload');?>
			<?php echo "<input type='file' name='userfile' size='20' />"; ?>
			<?php echo "<input type='submit' name='submit' value='upload' /> ";?>
			<?php echo "</form>"?>

	</div> 