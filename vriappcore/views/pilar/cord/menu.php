<?php
    $IdCarrera=mlGetGlobalVar("IdCarrera");
    $Carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$IdCarrera");
    $sess = $this->gensession->GetSessionData(PILAR_CORDIS);
 ?>
<div class="col-md-2 menuIzq hidden-print" >
    <h5><?php echo $Carrera; ?></h5>
	<div class="list-group hidden-print"> 
		<ul class="nav nav-pills bderecha"> 
			<!--<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwinfoCoord')" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Mi Cuenta</a> -->
			<?php if($sess->userLevel==4 | $sess->userLevel==1 ){ ?>
			<a hrepf="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwInicio')" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Inicio</a>
			<br>
			<!--<a disabled href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwbusq')" class="list-group-item"><span class="glyphicon glyphicon-search " ></span> Busquedas <span class="label label-info">En construcción</span> </a>
            <a disabled href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwFormatos')" class="list-group-item" style='background: #fffb3fe0;'><span class="glyphicon glyphicon-search " ></span> Formatos de la Escuela <span class="label label-success"> Cargar</span> </a> //Modificado unuv1.0-->

			<br>
			<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwDocentes')" class="list-group-item"><i class="glyphicon glyphicon-th-list"></i> Docentes </a>
			<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/lineasReg')" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Lineas de Investigación</a>
			<a href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwValidaLineas')" class="list-group-item" ><span class="glyphicon glyphicon-align-justify"></span> Validar Lineas </a>    
			<!--  -->
			<br>
			<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwProyectos')" class="list-group-item"><span class="glyphicon glyphicon-bookmark"></span> Proyectos de Tesis</a>
			<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwBorradores')" class="list-group-item"><span class="glyphicon glyphicon-bookmark"></span> Borrador de Tesis</a>
			
			<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwSustentac')"class="list-group-item"><span class="glyphicon glyphicon glyphicon-calendar"></span> Sustentacion Presencial</a>
            <a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwSustentacVir')"class="list-group-item"><span class="glyphicon glyphicon glyphicon-calendar"></span> Sustentacion Virtual</a>
			<br>

		<!--	<a href="javascript:void(0)" onclick="" class="list-group-item"><span class="glyphicon glyphicon glyphicon-calendar"></span> Cambio de Jurado</a> 
			<a href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwReportes')" class="list-group-item"><span class="glyphicon glyphicon-align-justify"></span> Reportes </a> Modificado unuv1.0-->
			<br>
			<a href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwReportes')" class="list-group-item"><span class="glyphicon glyphicon-align-justify"></span> Reportes </a>
			<br>
			 <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/Manual de coordinador 1.0.pdf");?>" class="list-group-item blink"><span class="glyphicon glyphicon-book"></span> Manual de Coordinador</a>
			<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwLogCordinador')" class="list-group-item bg-info"><span class="glyphicon glyphicon-book"></span> Historial de Actividades</a>
			<?php }
			if ($sess->userLevel==3) { ?>
				<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwInicio')" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Inicio</a>

				<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwProy2018')" class="list-group-item" disabled><span class="glyphicon glyphicon-home" ></span> Proyectos de Tesis</a>
				<a href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwReportes')" class="list-group-item"><span class="glyphicon glyphicon-align-justify"></span> Reportes </a>
				<a href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwValidaLineas')" class="list-group-item" style='background: #fffb3fe0;'><span class="glyphicon glyphicon-align-justify"></span> Validar Lineas </a>
				<br>
				<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwLogCordinador')" class="list-group-item bg-info"><span class="glyphicon glyphicon-book"></span> Historial de Actividades</a>
			<?php }
		 		if($sess->userLevel==2){ 
			 ?>
			 	<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwInicio')" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Inicio</a>
			 	<a href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwValidaLineas')" class="list-group-item" style='background: #fffb3fe0;'><span class="glyphicon glyphicon-align-justify"></span> Validar Lineas </a>
			 	<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/lineasReg')" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Lineas de Investigación</a>
			 	<a href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwReportes')" class="list-group-item"><span class="glyphicon glyphicon-align-justify"></span> Reportes </a>
				<br>
				<a href="javascript:void(0)" onclick="lodPanel('panelCord','cordinads/vwLogCordinador')" class="list-group-item bg-info"><span class="glyphicon glyphicon-book"></span> Historial de Actividades</a>
			 <?php } ?>
 
			 <?php 
		 		if($sess->userLevel==1){

			 ?>  
			 <?php } ?>

		</ul>
    </div>
</div> 