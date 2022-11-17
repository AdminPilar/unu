<?php 
 	$opciones = "<button class='btn btn-success btn-xs' data-toggle='modal' data-target='#mdlVer'>Ver</button> 
 				 <button class='btn btn-warning btn-xs' data-toggle='modal' data-target='#mdlCarga'>Cargar</button>"
 ?>
 <br><br><br>
 <h3>Información del Coordinador</h3>
<div class="col-md-12 panel-info-docente">
	<div class="col-md-3 info-sidebar pull-right">
		 <div class="list-group">
	      	<ul class="nav nav-pills bderecha"> 
	        <a href="#" onclick="" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Inicio </a>
	        </ul>
        </div>
	</div>
	<div class="col-lg-9"> <!-- -->

         
				<form class="form-horizontal" method="post" onsubmit="lodPanelFrm('panelView','docentes/grabIndexDoc',this)" _onsubmit_="return grabalo(this)">
					<fieldset>
						<!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label">Datos Personales</label>
							<div class="col-md-8">
								<input name="nameDoc" type="text" class="form-control input-md" disabled=""  >
							</div>
						</div>
                        <!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label"> ORCID </label>
							<div class="col-md-8">
								<input name="orcid" type="text" class="form-control input-md"  placeholder="El Registrado hoy" autofocus>
							</div>
						</div>
                        <!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label"> Scopus ID </label>
							<div class="col-md-8">
								<input name="scopus" type="text" class="form-control input-md"  placeholder="Si cuenta con publicaciones Scopus">
							</div>
						</div>
                        <!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label"> DINA Concytec </label>
							<div class="col-md-8">
								<input name="dina" type="text" class="form-control input-md"  placeholder="Solo dígitos">
							</div>
						</div>
                        <!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label"> RENACYT Id </label>
							<div class="col-md-8">
								<input name="regina" type="text" class="form-control input-md"  placeholder="Solo los vàlidos por Concytec">
							</div>
						</div>
                        <hr>
                        <!-- input -->
						<div class="form-group">
							<label class="col-md-8 control-label"> </label>
							<div class="col-md-3">
								<input type="submit" class="form-control btn-info" value="Grabar">
							</div>
						</div>
                    </fieldset>
                </form>
           

	<div class="col-lg-9">
	</div>
	<div class="col-lg-6">

	</div>	
</div>

<!--<span class="help-block">help</span>   -->

