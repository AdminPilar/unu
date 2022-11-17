<?php 
	 $sess = $this->gensession->GetData();
 ?>
<div class="modal fade" id="msgPosterX" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-pilar">
			<center>
				<div class="modal-header">
					<h3 class="modal-title" style="color:rgba(0, 138, 49);">Mensaje</h3>
				</div>
				<div class="alert alert-success" role="alert" style="padding: 15px">
					<span id="demo2">hola xd</span>
				</div>
				<div class="modal-footer">
					<center>
						<button type="button" class="btn btn-success" data-dismiss="modal" onclick="cerrar();"> Cerrar </button>
					</center>
				</div>
			</center>
		</div>
	</div>
</div>
<div class="col-md-12" style="background: #FFFFFF;">
	<div class="col-md-3">
	</div>
	<div class="col-md-6" style="border-style: solid; border-color: rgba(0, 138, 49);">

		<form class="form-horizontal" id="frmproy" style="margin: 10px;" onsubmit="EnviarCambio(); return false">
				<div class="form-group" style="background-color:rgba(0, 138, 49); color:white">
					<left>
						<h4 class="modal-title">&nbsp; Cambiar Contraseña </h4>
					</left>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<left><label>Nueva Contraseña (*) </label></left>
					</div>
					<div class="col-md-6">
						
						<input id="passCambio" name="passCambio" type="password" class="form-control input-md" value="" required="">
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6">
						<label>Confirmar Contraseña (*)</label>
					</div>
					<div class="col-md-6">
						<input id="passCambio2" name="passCambio2" type="password" class="form-control input-md" value="" required="">
					</div>
				</div>
				<div id="mos" class="alert alert-success" role="alert" style="display: none">
					<span id="demo"></span>
				</div>
				<hr>
				<div class="form-group">

					<div class="col-md-12">
						<center>
							<button id='guardar' type="submit" class="btn btn-success">Aceptar</button> &nbsp;&nbsp;
							<button id='salir' type="button" onclick="cerrar();" class="btn btn-danger">Cancelar</button>

						</center>
					</div>

				</div>
		</form>
	</div>
	<div class="col-md-3">
	</div>
</div>

<script type="text/javascript">

function EnviarCambio() {
		var contra = document.getElementById("passCambio").value;
		var contra2 = document.getElementById("passCambio2").value;
		if (contra != contra2) {

			document.getElementById('mos').style.display = 'block';
			document.getElementById("demo").innerHTML = 'La nuevas contraseñas no coinciden';
		} else {

			$('#msgPosterX').modal({
				backdrop: 'static',
				keyboard: false
			}) //agregado unuv2.0
			document.getElementById("demo2").innerHTML = 'Procesando........ espere por favor.';
			jVRI.ajax({
				type: 'POST',
				url: "Docentes/CambiarPass",
				data: $('#frmproy').serialize(),
				success: function(arg) {
					;
					if (arg.trim() == '') {

						document.getElementById("demo2").innerHTML = 'Se realizó exitosamente el cambio de contraseña.';

					} else {
						setTimeout(document.getElementById('mos').style.display = 'block', 5000);
						document.getElementById("demo").innerHTML = arg;
					}
					console.log(arg);
				},
				error: function(data) {
					alert(data);
				}
			});
		}
	}
function cerrar()
{
	m_href = "Docentes/logout";

	 location.href = m_href;
	
}

</script>