<!DOCTYPE html>
<head>
    <title>COMISION GyT - PILAR | Universidad Nacional de Ucayali</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="<?=base_url("vriadds/pilar/css/style_cord.css") ?>" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?=base_url("vriadds/lightajax.js")?>"></script>
    <script src="<?=base_url('vriadds/pilar/manager.js');?>"></script>
    <script src="<?=base_url('vriadds/pilar/js/js_corda.js');?>"></script>
    <script src="<?=base_url('vriadds/pilar/js/js_directis.js');?>"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-97516323-1', 'auto');
        ga('send', 'pageview');
    </script>
</head>
<?php 
    $varCarrera=mlGetGlobalVar("IdCarrera");
    if (!$varCarrera) {
	    $carrAct=$this->dbRepo->getSnapRow("dicCarreras","IdFacultad='$sess->IdFacultad'");
	    // mlSetGlobalVar('IdCarrera',$carrAct->Id);
	}

 ?>
<body>
	<!-- Coordinador NavBar  -->
	<div class="navbar navbar-default navbar-fixed-top">
	    	<div class="container">
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle collapsed" id='sidebarCollapse' data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	                <span class="sr-only">Menu</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	              <a class="navbar-brand" href="#">
		            <div class="navbar-brand-pilar">
		                <img class="img-responsive" src="<?php echo base_url("vriadds/pilar/imag/pilar.png");?>"/>COMISION GyT
		            </div>
	        	</a>
	        </div>

	        <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
	            <ul class="nav navbar-nav navbar-right">
				  	<li><a href="" class="nav-link">Escuela Profesional:</a></li>
	            	<li> 
						<div class="nav-link " style="width: 100%;padding-top:10px;"> 
							<select class="form-control"  onchange="jsCarrer(this.value)">
								<option value="0">Seleccionar Escuela Profesional</option>
								<?php 
									foreach ($escuelas->result() as $row) {
										echo "<option value='$row->Id'>$row->Nombre</option>";
									}
								 ?>
								 <div id="sel1"></div>
							</select>
						</div>
	            	</li>
	                <li><a href="<?= base_url("pilar/cordinads/logout");?>"class="nav-link">Salir <span class="glyphicon glyphicon-log-out"></span></a></li>
	            </ul>
			</div>
			</div>
	</div>


<div class="row" id="panelCarrer">
		
	<!-- timeline -->


