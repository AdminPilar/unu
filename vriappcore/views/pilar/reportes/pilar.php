<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Reportes PILAR</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="ha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="crossorigin="anonymous"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="">
	<script src="<?= base_url("vriadds/lightajax.js")?>" type="text/javascript" charset="utf-8" async defer></script>
	<script src="<?= base_url("vriadds/pilar/js/js_cord.js")?>" type="text/javascript" charset="utf-8" async defer></script>
	<style type="text/css" media="screen">
		@import url('https://fonts.googleapis.com/css?family=Oxygen:300,400,700');
		body{
			font-family: 'Oxygen', sans-serif;
			background-color: #eeeeef;
		}
		.main{
			background-color: #fff;
		}
		.navbar{
			border-radius: 0px;
		}
		.navbar-inverse {
		    background-color: #f3e21d;
		    border-color: #f3e21d;
		}
		.navbar-inverse .navbar-brand {
		    color: #000;
		    font-weight: 700;
		}
		.titu1{
			font-weight: 700;
		}
		.titu2{
			font-weight: 300;
		}
		.form-horizontal .control-label {
			text-align: center;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-inverse">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">
				  <span class="glyphicon glyphicon-stats"></span> <span class="titu1">PILAR</span>	<span class="titu2"> Análisis de Datos</span>
				</a>
			</div>
		</div>
	</nav>
	<div class="container main">
		<div class="page-header">
			<center><h1><small class="titu2"> Vicerrectorado de Investigación</small><br>Análisis de Datos PILAR</h1></center>
		</div>
		<div class="alert alert-warning" role="alert">
		  	<p>Toda Información obtenida por este medio pertenece exclusivamente al <b>Vicerrectorado de Investigación</b>, Por lo que deberá a atenerse a las consecuencias de usarla Incorrectamente.</p>
		</div>
		<div class="col-md-12">
			<form class="form-horizontal" name="frmPilarA" id="frmPilarA" method='POST'>
				<fieldset>
				<!-- Form Name -->
				<legend>PILAR, DATA</legend>

				<!-- Select Basic -->
				<div class="form-group">
					<label class="col-md-2 control-label" for="selectbasic">Escuela Profesional:</label>
					<div class="col-md-3">
						<select id="carrie" name="carrie" class="form-control">
							<?php 
								foreach($carreras->result() as $carrie){
									echo "<option value='$carrie->Id'>$carrie->Nombre</option>";
								}
							 ?>
						</select>
					</div>

					<label class="col-md-2 control-label" for="state">Estado :</label>
					<div class="col-md-3">
						<select id="state" name="state" class="form-control">
							<option value="100">(Todas)</option>
							<option value="201">Proyectos</option>
							<option value="202">Borradores</option>
							<?php 
								foreach($estados->result() as $estad){
									echo "<option value='$estad->Id'>$estad->Nombre</option>";
								}
							 ?>
						</select>
					</div>
					<div class="col-md-2">
						<button class="form-control btn btn-sm btn-warning" type="button" onclick="LoadForm('response','reports/procrep',frmPilarA)"> BUSCAR</button>
					</div>
				</div>
				</fieldset>
			</form>
			<hr>
			<div id="response">
				
			</div>
		</div>
	</div>
</body>
</html>