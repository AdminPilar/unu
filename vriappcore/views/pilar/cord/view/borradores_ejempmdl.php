<?php 
	$tram=$this->dbPilar->getSnapRow("tesTramites","Id=$IdProyect");
	$det=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite=$tram->Id");
	// <a href='mundo' target=_blank class='btn btn-info btn-xs'><span class='glyphicon glyphicon-book'></span> Memo</a>
?>
<!-- Modal content-->
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<center><h4 class="modal-title">Borrador de Tesis <br> <small> <?php echo $det->Titulo; ?></small></h4></center>
	</div>
	<div class="modal-body">
		<div class="container-fluid">
			<div id="envia">
			<h5 class="text-justify">La Coordinación de Investigación, está recibiendo cuatro (04) ejemplares anillados del borrador de tesis corregido según las observaciones del Jurado. <br><br> Dada la conformidad de la Tabla N° 1  del reglamento, el borrador se encuentra listo para el proceso de Dictamen.
			<br> <br><span class="text-right"> Art. 8 <small>(REGLAMENTO DE PRESENTACIÓN, DICTAMEN DE BORRADORES Y DEFENSA
				DE LA TESIS) <i><a href="http://vriunap.pe/vriadds/pilar/doc/resReglaBorrador.pdf" target="_blank">(Enlace)</a></i></small></span>
			</h5>

			<button type="button" class="btn btn-success btn-md btn-opt" onclick="if( confirm('Señor Coordinador esta seguro de que el Tesista Entregó 4 Ejemplares del Borrador de tesis?') ) lodPanel('envia','cordinads/recepEjemplares/<?php echo $tram->Id;?>')";
			> <span class="glyphicon glyphicon-send"></span> Recepcionar los Ejemplares </button>		
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="lodPanel('panelCord','cordinads/vwBorradores')"> Cerrar esta Ventana</button>
	</div>
</div>
<!-- //Modal content-->