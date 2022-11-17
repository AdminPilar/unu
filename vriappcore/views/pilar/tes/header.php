<!DOCTYPE html>
<html lang="es">
<head>
    <title>TESISTA - PILAR | Universidad Nacional de Ucayali</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url("vriadds/vri/fileinput.min.css") ?>">
    <link rel="stylesheet" href="<?=base_url("vriadds/pilar/css/style_tesista.css") ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?=base_url("vriadds/lightajax.js")?>"></script>
    <script src="<?=base_url('vriadds/pilar/js/tesista.js');?>"></script>
    <script src="<?=base_url('vriadds/pilar/js/js_tesista.js');?>"></script>
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
	<!-- Tesista NavBar  -->
	<div class="navbar navbar-default navbar-fixed-top">
	    	<div class="container">
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	                <span class="sr-only">Menu</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	              <a class="navbar-brand" href="#">
		            <div class="navbar-brand-pilar">
		                <img class="img-responsive" src="<?php echo base_url("vriadds/pilar/imag/pilar-tes.png");?>"/> TESISTA PILAR
		            </div>
	        	</a>
	        </div>

	        <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
	            <ul class="nav navbar-nav navbar-right">

	                <!-- <li><a class="nav-link" href="#">Proyecto de Tesis <i class="glyphicon glyphicon-envelope"></i></a></li> -->
	                <!--<li><a href="#" onclick='alert("Recopilando Formatos de Artículos ")' class="nav-link">Trabajo para Bachiller<i class="glyphicon glyphicon-book"></i></a></li> -->
	                <li><a href="<?= base_url("pilar/tesistas/logout");?>"><span class="glyphicon glyphicon-log-out"></span> Cerrar Sesión (Salir)</a></li>
	            </ul>
			</div>
			</div>
	</div>

	<!-- /Tesista NavBar  -->
	<div class="container tesista-inicio">
	<!-- timeline -->
