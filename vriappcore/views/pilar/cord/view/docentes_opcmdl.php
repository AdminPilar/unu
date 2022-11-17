<?php 
	$dat=$this->dbRepo->getSnapRow("tblDocentes","Id=$IdDocente");
 ?>
<!-- Modal content-->
<div class="modal-content">
	<div class="modal-header" style="background: #920738; color:white">
	  <button class="close" data-dismiss="modal" style="color:white">&times;</button>
	  <h4 class="modal-title">  Cambiar Estado  </h4>
	</div>
	<div class="modal-body" id='chDoc'>
		<h5>Recuerde que toda modificación es bajo su responsabilidad, y con la debida documentación del caso.</h5>
		<form class="form-horizontal" method="post" id="formUpStaDoc" name="formUpStaDoc" onsubmit="LoadForm('chDoc','cordinads/jsUpdateEstadoDoc',formUpStaDoc); return false">
		<fieldset>
			<div class="form-group">
				<label class="col-md-4 control-label" >Docente</label>  
				<div class="col-md-6">
					<input type="hidden"  name="idDoc" value="<?php echo $IdDocente; ?>">
					<input type="text"  class="form-control input-md" disabled name="doc" value="<?php echo "$dat->Apellidos, $dat->Nombres";?>">
				</div>
			</div> 
			<div class="form-group">
				<label class="col-md-4 control-label" >Cambiar de Estado </label>  
				<div class="col-md-4">
					<select id="idStado" name="idStado" class="form-control">
						<?php 	$estadoc=$this->dbRepo->getTable("dicEstadosDoc");
								foreach($estadoc->result() as $est){
									echo "<option value='$est->Id'>$est->Nombre</option>";
								}
						?>
					</select>
				</div>
			</div> 
			<div class="form-group"> 
				<label class="col-md-4 control-label" >Documento</label>  
				<div class="col-md-6">
					<input id="just" name="just" type="text" placeholder="R.D. N° 0123 - F - UNU" class="form-control input-md" required> 
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label" for="textarea">Detalle</label>
				<div class="col-md-8">                     
					<textarea class="form-control" id="detalle" name="detalle" placeholder="Justificación del cambio de Estado al Docente." rows="4" required></textarea>  
				</div>
			</div>
			<!-- Button -->
			<div class="form-group">
			  <label class="col-md-4 control-label" for="singlebutton">Registro</label>
			  <div class="col-md-4">
			    <button type="submit" class="btn btn-success"> <span class="glyphicon glyphicon-send"></span> Cambiar Estado</button>
			  </div>
			</div>
		</fieldset>
		</form>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="lodPanel('panelCord','cordinads/vwDocentes')"> Cerrar</button>
	</div>
</div>
<!-- //Modal content-->	