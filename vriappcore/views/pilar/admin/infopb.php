<?php 
	$tram=$this->dbPilar->getSnapRow("tesTramites","Id=$IdProyect");
	$det=$this->dbPilar->inLastTramDet($tram->Id);
	// <a href='mundo' target=_blank class='btn btn-info btn-xs'><span class='glyphicon glyphicon-book'></span> Memo</a>
?>
<!-- Modal content-->
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<center><h4 class="modal-title">Información del Proyecto <br> <small> <?php echo $det->Titulo; ?></small></h4></center>
	</div>
	<div class="modal-body">
		<div>
			<?php 
				if ($tram->Tipo==1 && $tram->Estado<=3 ) {
					$doc1=$this->dbRepo->inDocente("$tram->IdJurado4");
					echo "Asesor de Tesis: $doc1 <br>";
					if($tram->IdTesista2)echo "<br><b class='text-danger'> NOTA : Este proyecto tiene 2 Tesistas</b>";
				} else
				if ($tram->Tipo>=1 and $tram->Estado>3){
					$doc1=$this->dbRepo->inDocente("$tram->IdJurado4");
					echo "Asesor de Tesis: $doc1 <br>";
					if($tram->IdTesista2)echo "<br><b class='text-danger'> NOTA : Este proyecto tiene 2 Tesistas</b>";
					$doc1=$this->dbRepo->getSnapRow('tblDocentes',"Id=$tram->IdJurado1");
					$doc2=$this->dbRepo->getSnapRow('tblDocentes',"Id=$tram->IdJurado2");
					$doc3=$this->dbRepo->getSnapRow('tblDocentes',"Id=$tram->IdJurado3");
					$doc4=$this->dbRepo->getSnapRow('tblDocentes',"Id=$tram->IdJurado4");
					echo "<br><dt>Presidente : $doc1->Apellidos, $doc1->Nombres</dt><dt>Celular: $doc1->NroCelular </dt><dt>Correo: $doc1->Correo</dt></dl>";
						echo "<br><dt>Jurado 1 :  $doc2->Apellidos, $doc2->Nombres</dt><dt>Celular: $doc2->NroCelular </dt><dt>Correo: $doc2->Correo</dt>";
					echo "<br><dt>Jurado 2 :  $doc3->Apellidos, $doc3->Nombres</dt><dt>Celular: $doc3->NroCelular</dt><dt>Correo: $doc3->Correo</dt>";
					//echo "<br><dt>Jurad
				}
				//if($tram->IdTesista2)echo "<br><b class='text-danger'> NOTA : Este proyecto tiene 2 Tesistas</b>"; comentado unuv1.0

				//echo "Linea de Investigación:".$this->dbRepo->inLineaInv($tram->IdLinea); comentado unuv1.0
			 ?>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar</button>
	</div>
</div>
<!-- //Modal content-->