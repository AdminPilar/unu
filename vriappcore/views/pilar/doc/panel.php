<div class="col-md-10 col-sm-9 panel-trabajo">
	<div class="col-md-12 panel-view" id='panelView' style="padding: 1px">
			<div class="col-md-12">
			<h1 class="titulo">PILAR</h1>
			<div class="col-md-12 docente-inicio">
			<p>La Universidad Nacional de Ucayali mediante RESOLUCIÓN Nº377-2020-UNU-CU-R <b>“Convenio Específico de Cooperación Interinstitucional entre la Universidad Nacional de Ucayali y la Universidad Nacional de Ucayali de Puno Nº007-2020/UNU-UNA”</b> pone a disposición la Plataforma <i>PILAR</i>  
            para Docentes, Tesistas y Coordinadores; contando con la información disponible para realizar la
            subida, calificacion, revisión y posterior dictaminación de proyectos de investigación de pregrado
            conducentes a la obtención del título profesional. </p>
			</div>
		</div>
		<div class="col-md-12">
			<div class="col-md-6">
				<div class="panel panel-info">
					<div class="panel-heading">
						COMUNICADO
					</div>
					<div class="panel-body">
					Estimado Docente se está reestrucuturando las Líneas de Investigación de la Universidad, y venimos procesando esta información por lo que se le comunica que las Lineas de Investigación que no tienen tesis, no podrán ser mas seleccionadas.
					</div>
				</div>
				<div class="panel panel-warning ">
					<!--<div class="panel-heading docente-fedu">
						RECORDATORIO FEDU <B>(NUEVO!!)</B>
					</div>
					<div class="panel-body">
						 Presentación de Informe <B>DE AVANCE DE INVESTIGACIÓN</B>:<h5> Del <b>01 de Junio </b> al <b>15 de Junio de 2020 </b><h5>
					</div> -->
				</div>
			</div>
			<div class="col-md-6">
				<h4 class="titulo">Vicerrectorado de Investigación</h4>
				<div class="row">
					<div class="col-md-3">
						<img class="img-responsive" src="<?php echo base_url("vriadds/pilar/imag/vri.png");?>"/>
					</div>
					<div class="col-md-9">
						<p class="text-justify">El Vicerrectorado de Investigación, es el organismo de más alto nivel en la universidad en el ámbito de la investigación. Está encargado de orientar, coordinar y organizar los proyectos y actividades que se desarrollan a través de las diversas unidades académicas. Organiza la difusión del conocimiento y promueve la aplicación de los resultados de las investigaciones, así como la transferencia tecnológica y el uso de las fuentes de investigación, integrando fundamentalmente a la universidad, la empresa y las entidades del Estado.</p>
						<p class='text-right'><b>Ley Universitaria N° 30220</b></p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 ">
			<div class="row">
				<h4 class="titulo col-md-6">Resumen PILAR</h4>
				<h4 class="titulo text-right"> Fecha: <?php echo date('d-m-Y  |  H:i:s');?></h4>
			</div>
			<div class="col-md-3">
				<div class="panel panel-default ">
				 	<div class="panel-heading panel-docente-numero panel-num-1">
				 		<?php 
				 			echo $this->dbPilar->cuentaProys($sess->userId,1);
				 		?>
				 	</div>
					<div class="panel-body panel-docente-texto panel-info-1">
						Proyectos de Tesis
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="panel panel-default ">
				 	<div class="panel-heading panel-docente-numero panel-num-2">
				 		<?php 
				 			echo $this->dbPilar->cuentaProys($sess->userId,2);
				 		?>
				 	</div>
					<div class="panel-body panel-docente-texto panel-info-2">
						Borradores de Tesis
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="panel panel-default ">
				 	<div class="panel-heading panel-docente-numero panel-num-3">
				 		<?php 
				 			echo $this->dbPilar->cuentaProys($sess->userId,3);
				 		?>
				 	</div>
					<div class="panel-body panel-docente-texto panel-info-3">
						Sustentaciones
					</div>
				</div>
			</div>
<!-- 			<div class="col-md-3">
				<div class="panel panel-default ">
				 	<div class="panel-heading panel-docente-numero panel-num-4">1</div>
					<div class="panel-body panel-docente-texto panel-info-4">
						Proyecto FEDU
					</div>
				</div>
			</div> -->
		</div>
		<div class="col-md-6">

				<h4 class="titulo">Mis Líneas de Investigación</h4>

			<div >
				 <table class="table table-bordered">
					<thead>
						<tr>
							<th>Num</th>
							<th>Nombre</th>
							<th>Mis Tesis</th>
						</tr>
					</thead>
					<tbody>
					<?php $lineas=$this->dbPilar->getTable("docLineas","IdDocente=$sess->userId");
						$i=1; 
						foreach($lineas->result() as $row){
							$nameLine=$this->dbRepo->inLineaInv($row->IdLinea);
							$ntesis=$this->dbPilar->log("$row->IdLinea","$sess->userId");
							echo "<tr>
									<td>$i <small>($row->IdLinea)</small> </td>
									<td>".$nameLine."</td>
									<td>".$ntesis."</td>
								  </tr>";
							$i++;
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-6">
				<h4 class="titulo">Miembro de Jurado</h4>

			<div >
				 <table class="table table-bordered">
					<thead>
						<tr>
							<th>Num</th>
							<th>Tipo de Miembro</th>
							<th>Cantidad</th>
						</tr>
					</thead>
					<tbody>
					<?php $lineas=$this->dbPilar->getTable("docLineas","IdDocente=$sess->userId");
						$i=1;
						$name = array(
							1 =>"Presidente de Jurado" ,
							2 =>"Primer Miembro de Jurado" , 
							3 =>"Segundo Miembro de Jurado" , 
							4 =>"Asesor de Tesis"  
						);
						for( $j=1; $j<5; $j++ ) {
							$ntesis=$this->dbPilar->getTable("tesTramites","IdJurado$j=$sess->userId and Tipo!=0");
							echo "<tr><td>$i</td><td>".$name[$j]."</td><td>".$ntesis->num_rows()."</td></tr>";
							$i++;
						}
					?>
					</tbody>
				</table>
			</div>
		</div>

	</div>
</div>



</div>

</body>
</html>