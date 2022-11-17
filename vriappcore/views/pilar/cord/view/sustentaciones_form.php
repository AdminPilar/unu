<!-- Modal content-->
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<center><h4 class="modal-title">Publicación de Sustentación</h4></center>
	</div>
	<div class="modal-body" id='postSusten'>

		<form class="form-horizontal" name="frmbusq" method="post">
		    <fieldset>
		        <!-- Text input-->
		        <div class="form-group">
		            <label class="col-md-4 control-label"> Codigo Pilar : </label>
		            <div class="col-md-4">
		                <!-- <input name="tipo" type="hidden" value="3"> -->
		                <input name="cod" type="text" class="form-control input-sm" placeholder="2016-1523" autofocus>
		            </div>
		            <div class="col-sm-2">
		                <button type="button" class="btn btn-primary" onclick="LoadForm('postSusten','cordinads/evaluaSusten',frmbusq)">
		                    <span class="glyphicon glyphicon-search"></span> Buscar
		                </button>
		            </div>
		        </div>
		    </fieldset>
		</form>

	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="lodPanel('panelCord','cordinads/vwSustentac')"> Cerrar esta Ventana</button>
	</div>
</div>
<!-- //Modal content-->