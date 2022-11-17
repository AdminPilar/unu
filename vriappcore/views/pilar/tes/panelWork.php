<div class="col-md-9" id="panelTesis">
	    <div class="page-header">
	        <h2 id="timeline">Proceso en PILAR</h2>
	    </div>
	    <ul class="timeline">
	        <li class="timeline-inverted">
	          <div class="timeline-badge"><i class="glyphicon glyphicon-user"></i></div>
	          <div class="timeline-panel danger">
	            <div class="timeline-heading">
	              <h4 class="timeline-title">Bienvenido a PILAR</h4>
	             <!--  <p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> 11 hours ago via Twitter</small></p> -->
	            </div>
	            <div class="timeline-body">
	              <p>La Plataforma de Investigación Universitaria Integrada a la Labor Académica con Responsabilidad (PILAR), 
                     tiene por objetivo simplificar el proceso de investigación brindandote las herramientas necesarias para realizar tu trabajo de investigación, además de realizarlo on-line teniendo la información a primera mano del procedimiento de presentación, revisión, dictaminación y aprobación de proyectos y borradores de Tesis.</p>
	              <p>
	              	<b> RECOMENDACIONES</b>
	              	Inicialmente deberás tener encuenta estas recomendaciones.
	              	<ul>
	              		<li>Tener una idea o un tema de Investigación </li>
	              		<li>Buscar Información en la sección <b>Herramientas del Tesista</b></li>
	              		<li>Identificar la <b>Linea de Investigación </b>a la que pertenece tu tema de Investigación </li>
	              		<li>Elija su Asesor de proyecto de tesis, Los 3 jurados de tesis serán sorteados por la comisión de grados y titulos a travez de la Plataforma PILAR.</li>
	              	</ul>
	              	Para tener una experiencia satisfactoria en <b>PILAR</b> se les recomienda leer el manual del tesista <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/Manual de tesista_v1.pdf");?>"> <b><i>Click Aqui</i></b></a>
	              </p>
	            </div>
	          </div>
	        </li>
	        <?php

		        $estTram = $this->dbPilar->inTramByTesista("$sess->userId");
		        if($estTram){
			        if($estTram->Estado>2)
			        	echo "	<div class='flecha' id='textdown' >
			        				<a href='#'  onclick='showHidden(".$estTram->Estado.")'>

			        					<span class='glyphicon glyphicon-chevron-down'></span>
			        				</a>
		        			 	</div>";
	        	}
	        	$estados = $this->dbPilar->getTable("dicEstadTram");
	        	$flag = 1;
	        	foreach($estados->result() as $row){
	        		$color='green';
		            $estado='';
			        if($estTram){
			         	if($row->Id < $estTram->Estado){
			         		$estado= "hidden";
			         		$color = "danger";
			         	}
			         	if($row->Id == $estTram->Estado){
			         		$color = "danger";
			         	}
		    		}
			        ?>
			        <li class="timeline-inverted " id='est<?= $row->Id;?>'<?= $estado;?>>
			          <div class="timeline-badge <?= $color;?>"><?=$flag;?></i></div>
			          <div class="timeline-panel">
			            <div class="timeline-heading">
			              <h4 class="timeline-title"><?=$row->Nombre;?></h4>
			            </div>
			            <div class="timeline-body">
			              <p><?=$row->Descrip;?></p>
			            </div>
			          </div> 
			        </li>
			        <?php
			    $flag++;
	    	}
	        ?>
	    </ul>
	    </div>

	<!-- /timeline -->