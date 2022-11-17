<?php 
	$tram=$this->dbPilar->getSnapRow("tesTramites","Id=$IdProyect");
	$det=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite=$tram->Id");
	$opciones=  
	"
		<a href='javascript:void(0)' target=_blank class='btn btn-success btn-xs'><span class='glyphicon glyphicon-send'></span> Notificar Docente</a>
	";
	$docu="<a href='".base_url("pilar/cordinads/memosGen/$tram->Id")."' target=_blank class='btn btn-info btn-xs'><span class='glyphicon glyphicon-print'></span> Imprimir Memo</a>";
	// <a href='mundo' target=_blank class='btn btn-info btn-xs'><span class='glyphicon glyphicon-book'></span> Memo</a>
?>
<!-- Modal content-->
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Proyecto de Tesis <?php echo $tram->Codigo; ?></h4>
	</div>
	<div class="modal-body">
		<h5> Miembros de Jurado | Documento de Referencia : <?php echo $docu;?></h5>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th >Orden</th>
					<th >Est</th>
					<th>Opciones</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Presidente de Jurado</td>
					<td><?php echo "[ $det->vb1 ]";?></td>
					<td><?php echo $opciones;?></td>
				</tr>
				<tr>
					<td>Primer Miembro</td>
					<td><?php echo "[ $det->vb2 ]";?></td>
					<td><?php echo $opciones;?></td>
				</tr>
				<tr>
					<td>Segundo Miembro</td>
					<td><?php echo "[ $det->vb3 ]";?></td>
					<td><?php echo $opciones;?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="lodPanel('panelCord','cordinads/vwProyectos')"> Cerrar esta Ventana</button>
	</div>
</div>
<!-- //Modal content-->