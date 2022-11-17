<?php 
	$tram=$this->dbPilar->getSnapRow("tesTramites","Id=$idtram");
	$det=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite=$tram->Id");

?>
<!-- Modal content-->
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Mensaje de Rechazo Proyecto de Tesis <?php echo $tram->Codigo; ?></h4>
	</div>
	<div class="modal-body" id='popis'>
		<form id='evitaaa' method='POST'>
		<?php 
 
			$this->gensession->IsLoggedAccess( PILAR_CORDIS );
	        if( !$idtram ) return;

	        $tram = $this->dbPilar->inProyTram($idtram);
	        if(!$tram){ echo "No registro"; return; }

	        echo "<b>Codigo :</b> $tram->Codigo ";
	        echo "<br><b>Linea ($tram->IdLinea) :</b> " . $this->dbRepo->inLineaInv($tram->IdLinea);
	        echo "<br><b>Tesista(s) :</b> "             . $this->dbPilar->inTesistas($tram->Id);
	        echo "<hr>";

	        // mensaje editable
	        $msg = "<b>Saludos</b><br><br>\nSu proyecto ha sido rechazado, contiene los siguientes errores:\n"
	             . "<br><br><ul>\n<li> La redacción tiene que ser mejorada.\n</ul><br>\nDeberá corregir y subir su proyecto a la brevedad posible.\n"
	             . "<br><b>Nota</b>: Revise el <a href='http://vriunap.pe/vriadds/pilar/doc/manual_tesistav3.pdf'>manual de tesista aquí.</a>";


	        // detallaremos evento interno Ev31
	        echo "<input type=hidden name=evt value='10'>";
	        echo "<input type=hidden name='idtram' value='$idtram'>";
	        echo "<div class='form-group'>";
	        echo    "<label for='comment'>Mensaje a enviar:</label>";
	        echo    "<textarea class='form-control' rows=8 name='msg'>$msg</textarea>";
	        echo "</div>
	        	<button type='button' class='btn btn-success' data-dismiss='modal' onclick='popExeRechaza($idtram)'> Enviar mensaje </button>
	        ";

		 ?>
	    </form>


	</div>
	<div class="modal-footer">
		
		<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="lodPanel('panelCord','cordinads/vwProyectos')"> Cerrar esta Ventana</button>
	</div>
</div>
<!-- //Modal content-->