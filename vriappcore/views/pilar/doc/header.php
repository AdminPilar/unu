<!DOCTYPE html>
<html lang="es">
<head>
    <title>DOCENTE - PILAR | Universidad Nacional de Ucayali</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="<?=base_url("vriadds/pilar/css/style_docente.css") ?>" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?=base_url("vriadds/lightajax.js")?>"></script>
    <script src="<?=base_url('vriadds/pilar/js/docentus.js');?>"></script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-97516323-1', 'auto');
        ga('send', 'pageview');
    </script>
</head>
<body>
	<!-- Docente NavBar  -->
	<div class="navbar navbar-default navbar-fixed-top">
	    <div class="col-md-12">

	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	                <span class="sr-only">Menu</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a class="navbar-brand" href="#">
		            <div class="navbar-brand-pilar"> 
		            <img class="img-responsive" src="<?php echo base_url("vriadds/pilar/imag/unap.png");?>"/> DOCENTE PILAR
		            <!-- <img class="img-responsive text-center" src="<?php echo base_url("vriadds/pilar/imag/unap.png");?>"/> -->
		            </div>
	        	</a>
	        </div>

	        <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
	            <ul class="nav navbar-nav navbar-right">
	                <!--<li><a class="nav-link" href="#"> Borradores <i class="glyphicon glyphicon-envelope"></i><span class="badge badge-mail">0</span></a></li>
	                <li><a href="#" class="nav-link"> Proyectos <i class="glyphicon glyphicon-book"></i> <span class="badge badge-mail">0</span></a></li> -->
	                <li><a href="<?= base_url("pilar/docentes/logout");?>"> <span class="glyphicon glyphicon-log-out"></span> Cerrar Sessión (Salir) </a></li>
	            </ul>
			</div>

	    </div>
	</div>
	<!-- /Docente NavBar  -->
	<div class="col-md-12 main">

		


