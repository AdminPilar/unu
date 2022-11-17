<div class='page-header'>
    <h4>Lineas de investigación Registradas</h4>
</div>
<div>
	<p>El listado de líneas de Investigación registradas en tu escuela profesional con sus respectivos docentes especialistas</p>

	<p class="text-danger"><b>Nota :</b> Usar los correos electrónicos en casos estrictamente necesarios.</p>
	<div class="panel-group">
	<?php
		$flag=1;
		foreach($lineas->result() as $row){
			$count=$this->dbPilar->getSnapView("docLineas","IdLinea=$row->Id  AND estado='2'")->num_rows();
			if($count>=4){ //se muestra las lineas que solo tienen mas de 4 docentes activos -unuv1.0
	 ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
				<a data-toggle="collapse" href="#linea<?php echo $flag;?>"><?php echo $row->Nombre;?></a>
				</h4>
			</div>
			<div id="linea<?php echo $flag;?>" class="panel-collapse collapse">
				<?php

                    // docentes nombrados y activos
                    $doci = $this->dbPilar->getTable( "vxDocInLin", "Activo>='5' AND IdLinea='$row->Id' AND LinEstado='2' ORDER BY DatosPers" );
					$flaqui = 1;
					foreach( $doci->result() as $rina ) {
						///$name=$this->dbRepo->inDocente( $rina->IdDocente );
						$mail= $this->dbRepo->inCorreo($rina->IdDocente);
						echo "<div class='panel-body listdoc'>$flaqui.- $rina->DatosPers <b>($mail)</b> </div>";
						$flaqui++;
					}
				?>
			</div>
		</div>
	<?php
		$flag++;
		 	}
		}
	?>
	</div>
</div>