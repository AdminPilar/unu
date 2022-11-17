<?php 
	$tram=$this->dbPilar->getSnapRow("tesTramites","Id=$IdProyect");
	//$det=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite=$tram->Id");
	$rowi=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite=$tram->Id ORDER BY Iteracion DESC");
	$opciones=  
	"
		
	";
	$docu="<a href='".base_url("pilar/cordinads/memosGen/$tram->Id")."' target=_blank class='btn btn-info btn-xs'><span class='glyphicon glyphicon-print'></span> Imprimir Memo</a>";
	// <a href='mundo' target=_blank class='btn btn-info btn-xs'><span class='glyphicon glyphicon-book'></span> Memo</a>
?>
<!-- Modal content-->
<div class="modal-content">
	<div class="modal-header" style="background: #920738; color:white">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Archivos Subidos al Tramite <?php echo $tram->Codigo; ?></h4>
	</div>
	<div class="modal-body">		
		<table class="table table-bordered">
			<thead>
				<tr>
					<th >Nombre de Archivo</th>					
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Contancia COTI</td>
					<td><?php echo "<a href='".base_url("/repositor/coti/$rowi->Coti")."' target=_blank class='btn btn-success btn-xs'><span class='glyphicon glyphicon-open-file'></span> Ver</a>";?></td>
				</tr>
				<tr>
					<td>Informe Final de Tesis</td>
					<td><?php echo "<a href='".base_url("/repositor/docs/$rowi->Archivo")."' target=_blank class='btn btn-success btn-xs'><span class='glyphicon glyphicon-open-file'></span> Ver</a>";?></td>
				</tr>
				<tr>
					<td>Anexo del Informe Final de Tesis</td>
					<td><?php echo "<a href='".base_url("/repositor/docs/$rowi->Anexo")."' target=_blank class='btn btn-success btn-xs'><span class='glyphicon glyphicon-open-file'></span> Ver</a>";?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="lodPanel('panelCord','cordinads/vwBorradores')"> Cerrar esta Ventana</button>
	</div>
</div>
<!-- //Modal content-->