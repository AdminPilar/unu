<!DOCTYPE html>
<html>
<head>
	<title> Acceso a Reportes</title>

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
    			background-color: #525252;
			}
			.centered-form{
				margin-top: 70px;
			}

			.centered-form .panel{
				/*background: rgba(255, 255, 255, 0.2);*/
				background-color: #fff;
				box-shadow: rgba(0, 0, 0, 0.5) 20px 20px 20px;
			}
			.panel-heading{
				text-align: center;
				background-color: red;
			}
			legend{
				font-size: 12px;
				margin-bottom: 0px;
				border:0px;
			}
			.titu1{
			font-weight: 700;
		}
		.titu2{
			font-weight: 300;
		}
			.panel-default>.panel-heading {
			    color: #000;
			    background-color: #f3e21d;
			    border-color: #f3e21d;
			}
			.btn-warning, .btn-warning:hover {
			    color: #000;
			    background-color: #f3e21d;
			    border-color: #f3e21d;
			}
	</style>
</head>
<body>

<div class="container">
	<div class="row centered-form">
		<div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="glyphicon glyphicon-stats"></span> <span class="titu1">PILAR</span>	<span class="titu2"> Análisis de Datos</span>
			</div>
		<div class="panel-body">
		<form role="form" method="POST" action="<?= base_url("pilar/reports/login")?>">
			<legend class="titu1"> Correo Electrónico</legend>
			<div class="form-group">
				<input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email Address">
			</div>
			<legend class="titu1"> Password</legend>
			<div class="form-group">
				<input type="password" name="pass" id="pass" class="form-control input-sm" placeholder="******">
			</div>
			<input type="submit" value="Entrar" class="btn btn-warning btn-block titu1">
		</form>
		</div>
		</div>
	</div>
</div>

</body>
</html>