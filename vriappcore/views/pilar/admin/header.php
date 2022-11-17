<!DOCTYPE html>
<html lang="es">
<head>
    <title> Administrador PILAR </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="<?=base_url("vriadds/pilar/css/admin.css") ?>" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?=base_url('vriadds/lightajax.js');?>"></script>
    <script src="<?=base_url('vriadds/pilar/manager.js');?>"></script>

	<!-- agregado 04/10/2021-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> 
</head>

<body>
	<!-- Admin NavBar  -->
	<div class="navbar navbar-default navbar-fixed-top">
	    <div class="col-md-12">

	        <div class="navbar-header">
	            <button button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	                <span class="sr-only">Menu</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
                <!-- ocultar menu -->
	            <a class="navbar-brand" data-toggle="collapse" data-target="#mnuFred" href="javascript:void(0)">
		            <div class="navbar-brand-pilar">
		                <img class="img-responsive" src="<?=base_url("vriadds/pilar/imag/pilar-n.png");?>"/>
		                    ADMIN PILAR
		            </div>
	        	</a>
                <!-- end menu bar -->
	        </div>

	        <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
	            <ul class="nav navbar-nav navbar-right">
                    <?php
                        $sess = $this->gensession->GetData( PILAR_ADMIN );
                        $user = ($sess)? " - [ $sess->userName ]" : "";
                    ?>
	                <li><a style="font-size: 14px" href="<?=base_url("pilar/admin/logout")?>"><span class="glyphicon glyphicon-user"></span> &nbsp; Logout <?=$user?> </a></li>
	            </ul>
			</div>

	    </div>
	</div>
	<!-- /Admin NavBar  -->


