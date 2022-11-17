<?php 
 	$opciones = "<button class='btn btn-success btn-xs' data-toggle='modal' data-target='#mdlVer'>Ver</button> 
 				 <button class='btn btn-warning btn-xs' data-toggle='modal' data-target='#mdlCarga'>Cargar</button>"
 ?>
<div class="col-md-12 panel-info-docente">
	<div class="col-md-3 info-sidebar pull-right">
		 <div class="list-group">
	      	<ul class="nav nav-pills bderecha"> 
	       <!--<a href="#" onclick="" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Inicio </a> Modificado unuv1.0-->
	        </ul>
        </div>
	</div>
	<div class="col-lg-9"> <!-- -->
		<ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#tab0"> Datos de Investigador </a></li>
            <li><a data-toggle="tab" href="#tab1"> Datos Personales </a></li>
			<li><a data-toggle="tab" href="#tab2"> Lineas de Investigación </a></li>
			<li><a data-toggle="tab" href="#tab3"> Grados Obtenidos </a></li>
		</ul>


		<div class="tab-content">
            <div id="tab0" class="tab-pane fade in active">
				<form class="form-horizontal" method="post" onsubmit="lodPanelFrm('panelView','docentes/grabIndexDoc',this)" _onsubmit_="return grabalo(this)">
					<fieldset>
						<!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label">Datos Personales</label>
							<div class="col-md-8">
								<input name="nameDoc" type="text" class="form-control input-md" disabled="" value="<?php echo "$datDoc->Apellidos $datDoc->Nombres";?>" >
							</div>
						</div>
                        <!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label"> ORCID </label>
							<div class="col-md-8">
								<input name="orcid" type="text" class="form-control input-md" value="<?=$idxs?$idxs->Orcid:""?>" placeholder="El Registrado hoy" autofocus>
							</div>
						</div>
                        <!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label"> Scopus ID </label>
							<div class="col-md-8">
								<input name="scopus" type="text" class="form-control input-md" value="<?=$idxs?$idxs->Scopus:""?>" placeholder="Si cuenta con publicaciones Scopus">
							</div>
						</div>
                        <!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label"> DINA Concytec </label>
							<div class="col-md-8">
								<input name="dina" type="text" class="form-control input-md" value="<?=$idxs?$idxs->Dina:""?>" placeholder="Solo dígitos">
							</div>
						</div>
                        <!-- input -->
						<div class="form-group">
							<label class="col-md-3 control-label"> RENACYT Id </label>
							<div class="col-md-8">
								<input name="regina" type="text" class="form-control input-md" value="<?=$idxs?$idxs->Regina:""?>" placeholder="Solo los Vàlidos por Concytec">
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
            </div>
			<div id="tab1" class="tab-pane fade in">
				<hr>
				<h4 class="titulo">Datos de Docente</h4>
				<form class="form-horizontal">
					<fieldset>
						<!-- Apellidos -->
						<div class="form-group">
							<label class="col-md-3 control-label">Apellidos :</label>
							<div class="col-md-8">
								<input name="nameDoc" type="text" class="form-control input-md" disabled="" value="<?php echo "$datDoc->Apellidos";?>" >
							</div>
						</div>
						<!-- Nombres -->
						<div class="form-group">
							<label class="col-md-3 control-label">Nombres :</label>
							<div class="col-md-6">
								<input name="nameDoc" type="text" class="form-control input-md" disabled="" value="<?php echo "$datDoc->Nombres";?>">
							</div>
						</div>
						<!-- Fecha de Nacimiento -->
						<div class="form-group">
							<label class="col-md-3 control-label">Fech. de Nac. :</label>  
							<div class="col-md-5">
								<input name="nameDoc" type="date" class="form-control input-md" disabled=""value="<?php echo "$datDoc->FechaNac";?>">
							</div>
						</div>
						<!-- DNI -->
						<div class="form-group">
							<label class="col-md-3 control-label">DNI :</label>  
							<div class="col-md-4">
								<input name="nameDoc" type="number" class="form-control input-md" disabled="" value="<?php echo "$datDoc->DNI";?>">
							</div>
						</div>
						<!-- CODIGO -->
						<div class="form-group">
							<label class="col-md-3 control-label">Codigo :</label>  
							<div class="col-md-4">
								<input name="nameDoc" type="number" class="form-control input-md" disabled="" value="<?php echo "$datDoc->Codigo";?>">
							</div>
						</div>
						<!-- Categoría -->
						<div class="form-group">
							<label class="col-md-3 control-label">Categoría :</label>  
							<div class="col-md-9">
								<input name="nameDoc" type="text" class="form-control input-md" disabled="" value="<?php echo "$datDoc->Categoria";?>">
							</div>
						</div>
						<!-- Escuela -->
						<div class="form-group">
							<label class="col-md-3 control-label">Escuela :</label>  
							<div class="col-md-9">
								<input name="nameDoc" type="text" class="form-control input-md" disabled="" value="<?php echo "$datDoc->Carrera";?>">
							</div>
						</div>

						<!-- Facultad -->
						<div class="form-group">
							<label class="col-md-3 control-label">Facultad :</label>  
							<div class="col-md-9">
								<input name="nameDoc" type="text" class="form-control input-md" disabled="" value="<?php echo "$datDoc->Facultad";?>">
							</div>
						</div>

						<?php  if($datDoc->Tipo=="N"){?>
						<!-- Asenso -->
						<div class="form-group">
							<label class="col-md-3 control-label">Ascenso  :</label>
							<div class="col-md-9">
								<input type="text" class="form-control input-md" disabled="" value="<?php echo $datDoc->FechaAsc." / ".$datDoc->ResolAsc;?>">
							</div>
						</div>
						<?php 	}else{?>
						<!-- Contrato -->
						<div class="form-group">
							<label class="col-md-3 control-label">Contrato  :</label>
							<div class="col-md-9">
								<input type="text" class="form-control input-md" disabled="" value="<?php echo $datDoc->FechaCon." / ".$datDoc->ResolCon;?>">
							</div>
						</div>
						<!-- Contrato -->
						<?php 	}?>
					</fieldset>
				</form>


			</div> <!-- datos personales -->
			<div id="tab2" class="tab-pane fade">
				<hr>
				<h4 class="titulo">Lineas de Investigación</h4>
				<fieldset>
					<div class="table-responsive">
						<table class="table">
							<tr>
								<!--<th class="col-md-1"> Nro </th> -->
								<th class="col-md-1"> Tipo </th>
								<th class="col-md-8"> Linea </th>
								<th class="col-md-2"> Opciones </th>
							</tr>
							 
							 	<?php 
							 		$sess = $this->gensession->GetData();
							 		$i=0;
							 		foreach($linDoc->result() as $row){
										$nameLine=$this->dbRepo->inLineaInv($row->IdLinea);
										echo "<tr>
												<td>".$row->Tipo."</td>
												<td>".$nameLine."</td>
												<td>  </td>
											  </tr>";
										$i++;
									}
							 	 ?>
							 
						</table>
						<table class="table" id="tlin">
						</table>
					</div> <!-- fin div lineas table -->
					<div>
					<hr>
						<!-- Select input-->
						<!--<div class="form-group">
							<label class="col-md-3 control-label">Linea por Escuela Profesional </label>
							<div class="col-md-7">
								<select id="clin" name="clin" class="form-control" onchange="" required>
									<option value="" disabled selected> seleccione </option>
									<?php
										foreach( $lineas->result() as $row )
										{
											echo "<option value=$row->Id> $row->Nombre </option>";
										}
									?>
								</select>
							</div>
							<div class="col-md-2">
								<button class="btn btn-success" onclick="feedDiv(clin.value)">
									<span class="glyphicon glyphicon-upload"></span> Agregar </button>
							</div>
						</div> //Modificado unuv1.0 -->
						
						<hr>
					</div>
				</fieldset>
			</div> <!-- fin de lineas  -->
			<div id="tab3" class="tab-pane fade">
				<hr>
				<h4 class="titulo">Listado de Grados Obtenidos</h4>

				<fieldset>
					<div class="table-responsive">
						<table class="table">
							<tr>
								<th class="col-md-1"> Nro </th>
								<th class="col-md-1"> Abrev </th>
								<th class="col-md-7"> Mención </th>
								<th class="col-md-3"> Opciones </th>
							</tr>
							 
							 	<?php 
							 		$sess = $this->gensession->GetData();
							 		$i=1;
							 		foreach($grad->result() as $row){
										echo "<tr>
												<td>$i </td>
												<td>".$row->AbrevGrado."</td>
												<td> $row->Mencion <br> <small>$row->Universidad</small></td>
												<td> </td>
											  </tr>";
										$i++;
									}
							 	 ?>
							 
						</table>
						<table class="table" id="tlin">
						</table>
					</div> <!-- fin div lineas table -->
					<div>
						</div>
						
						<hr>
					</div>
				</fieldset>

			</div>
		</div>
	</div> <!-- -->

	<div class="col-lg-9">
	</div>
	<div class="col-lg-6">

	</div>	
</div>

<!--<span class="help-block">help</span>   -->