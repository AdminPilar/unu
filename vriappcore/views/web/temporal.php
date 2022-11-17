<div class="modal fade" id="msgPosterX" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: gray">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Actividades</h4>
            </div>
            <div class="modal-body" style="padding: 0px">

                <div id="myCarousel" class="carousel slide" data-ride="carousel">

                    <div class="carousel-inner">

                    <div class="item active">
                        <a href="#" target="_blank">
                            <img src="/vriadds/vri/web/convocatorias/escuelacontraloria.jpg">
                        </a>
                    </div>

                        <div class="item">
                            <a href="/vriadds/vri/Convocatorias/reglamentoPropiedadIntelectual.pdf" target="_blank">
                                <img src="vriadds/vri/img/reglamentoindecopi.jpg">
                            </a>
                        </div>

                        <div class="item">
                            <a>
                                <img src="vriadds/vri/web/convocatorias/COMUNICADOsabatico.jpg">
                            </a>
                        </div>
                    </div>

                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only"> </span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only"> </span>
                    </a>
                </div>
            </div>

            <div class="modal-footer">
                <a class="btn btn-danger" href="<?=base_url()?>pilar" role="button">Ir a la Plataforma Pilar</a>
                <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar </button>
            </div>

        </div>
    </div>
</div>


<!--  fin mensaje a la nación-->

<div class="navbar navbar-default navbar-fixed-top navbar-home">
    <div class="container">

        <div class="navbar-header">
            <button button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" rel="home" href="#" title="Universidad Nacional de Ucayali | Vicerrectorado de Investigación">
            </a>
        </div>


        <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?=base_url()?>pilar">PLATAFORMA PILAR</a></li>
                <li><a href="<?=base_url()?>fedu">FEDU</a></li>
                <li><a href="<?=base_url()?>web/institutos">INSTITUTOS</a></li>
                <li><a href="<?=base_url()?>cursos">CURSOS Y EVENTOS</a></li>
                <li><a href="http://repositorio.unap.edu.pe/">REPOSITORIO</a></li>
                <li><a href="<?=base_url()?>pilar/sustentas">SUSTENTACIONES</a></li>
                <li><a href="#"><span class="icon-search gi-15x"> </span></a></li>
            </ul>
        </div>

    </div>
</div>

<div class="container ">
    <div class="container-fluid vri-info" id="video-cabeza">
      <div class="tv">
       <div id="tv"></div>
   </div>
   <img class="img-responsive pull-left vri-logo" src= "<?php echo base_url("vriadds/vri/web/logo-vri2.png");?>" >
	 	<!--
	 	<img class="img-responsive pull-right vri-anio" src= "<?php echo base_url("vriadds/vri/web/primeranio.png");?>" >
     -->
     <div class="col-md-1">
     </div>
</div>
    
    
    <div class="container-fluid vri-panel">
        <div class ="row">
            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="<?=base_url()?>pilar" class="btn vri-panel-btn">
                    <span class="icon-vri-pilar gi-3x"></span> 
                    <br>Plataforma Pilar</a>
            </div>

            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="<?=base_url()?>fedu" class="btn vri-panel-btn">
                    <span class="icon-codeopen gi-3x"></span> 
                    <br>FEDU</a>
            </div>

            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="<?=base_url()?>cursos" class="btn vri-panel-btn">
                    <span class="icon-library gi-3x"></span>
                    <br>Cursos y eventos</a>
            </div>
            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="http://repositorio.unap.edu.pe/" class="btn vri-panel-btn">
                    <span class="icon-layers gi-3x"></span>
                    <br>Repositorio</a>
            </div>
            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="<?=base_url()?>pilar/sustentas" class="btn vri-panel-btn">
                    <span class="icon-graduation-cap gi-3x"></span>
                    <br>Sustentaciones</a>
            </div>

            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="<?=base_url()?>sabatico" class="btn vri-panel-btn">
                    <span class="icon-feather gi-3x"></span>
                    <br>Año sabático</a>
            </div>

            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="<?=base_url("comunicados")?>" class="btn vri-panel-btn">
                    <span class="icon-feather gi-3x"></span>
                    <br>COMUNICADOS</a>
            </div>            

            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="http://huajsapata.unap.edu.pe/ria/index.php/ria" class="btn vri-panel-btn">
                    <span class="glyphicon glyphicon-asterisk gi-3x"></span>
                    <div style="height: 12px"></div>REVISTAS</a>
            </div>


            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="http://vriunap.pe/urkund" class="btn vri-panel-btn">
                    <span class="icon-feather gi-3x"></span>
                    <br>Urkund</a>
            </div>


            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="/vriadds/vri/Convocatorias/reglamentoPropiedadIntelectual.pdf" class="btn vri-panel-btn">
                    <span class="icon-feather gi-3x"></span>
                    <br>Propiedad Intelectual</a>
            </div>

            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="<?=base_url()?>proacie" class="btn vri-panel-btn">
                    <span class="icon-feather gi-3x"></span>
                    <br>Incubadora de Negocios</a>
            </div>

            <div class="col-xs-12 col-md-2 vri-btn-acceso">
                <a href="<?=base_url()?>web/institutos" class="btn vri-panel-btn">
                    <span class="icon-layers gi-3x"></span>
                    <br>Institutos</a>
            </div>

        </div>
    </div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-6 vri-seccion bg-0">
                <div class="seccion-bloque paddington30">
                    <center>
                        <h1 class="seccion-titulo">Vicerrectorado de Investigación</h1>
                    </center><br><br>
                    <p>
                        El Vicerrectorado de Investigación, es el organismo de más alto nivel en la universidad en el ámbito de la investigación. Está encargado de orientar, coordinar y organizar los proyectos y actividades que se desarrollan a través de las diversas unidades académicas. Organiza la difusión del conocimiento y promueve la aplicación de los resultados de las investigaciones, así como la transferencia tecnológica y el uso de las fuentes de investigación, integrando fundamentalmente a la universidad, la empresa y las entidades del Estado.
                    </p>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 vri-seccion curso">
                <div class="titulo-curso">PRÓXIMOS EVENTOS</div>
                <div class ="contenedor-curso">
                    <div class="col-sm-12 texto-curso">
                        Jornada EMPRENDE UNA 2019
                        <div class="pull-right">
                            <a href="<?=base_url()?>emprendeuna" class="boton-curso">MUY PRONTO</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
