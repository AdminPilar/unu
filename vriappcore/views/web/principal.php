<body class="bg-1">

  <!-- Dialog : DINA Docentes -->
  --
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
                            <img src="/var/www/html/vriadds/vri/web/convocatorias/escuelacontraloria.jpg">
                        </a>
                    </div>


                    <div class="item">
                        <a href="/vriadds/vri/Convocatorias/reglamentoPropiedadIntelectual.pdf" target="_blank">
                            <img src="vriadds/vri/img/reglamentoindecopi.jpg">
                        </a>
                    </div>


                    <div class="item">
                        <a href="/pilar" target="_blank">
                             <img class="img-responsive" src="/vriadds/vri/img/comunicadoCOVID.jpeg">
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
           <br>Plataforma Pilar1</a>
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
                       <a href="<?=base_url()?>web/etica" class="btn vri-panel-btn">
                           <span class="icon-feather gi-3x"></span>
                           <br>Ética en investigación</a>
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
                    <!--
					<center>
                        <h1 class="seccion-titulo">CONSULTA RESULTADOS:</h1>
                        <img class="img-responsive vri-logo-small" src= "<?php echo base_url("vriadds/vri/web/laspau.png");?>" >
                    </center><br><br>
                    <section class="panel-body">

                        <form action="<?php echo base_url("web/laspauConsulta");?>" method="post" accept-charset="utf-8">
                            <label class="text-success">Digite su DNI :</label>
                            <input class="text-info" type='number' name='dni'>
                            <button class="btn btn-success" type='submit'> <i class="glyphicon glyphicon-search"></i> Buscar</button>
                        </form>
                    </section>
                    <a href="<?=base_url()?>web/programalaspau" class="seccion-link">Conocer más <i class="icon-right-open-1"></i></a>
                -->
            </div>
        </div>

            <!--
            <div class="col-xs-12 col-md-6 vri-seccion curso">
				<div class="titulo-curso">PRÓXIMA CONVOCATORIA</div>
						<div class ="contenedor-curso">
								<div class="col-sm-5 numero-curso">
								20
								</div>

								<div class="col-sm-7 texto-curso">
                                   Beca Docente Universitario 2017
                                </div>
							<div class="col-sm-5 mes-curso">AGOSTO
                                </div>				<div class="col-sm-7 text-left">
								<a href="<?=base_url()?>web/convocatoriabeca2017" class="boton-curso">DETALLES
							</a>
							</div>
						</div>
			</div>
        -->

        <div class="col-xs-12 col-md-6 vri-seccion curso">
            <div class="titulo-curso">PRÓXIMOS EVENTOS</div>
            <div class ="contenedor-curso">

                <div class="col-sm-12 texto-curso">
                    Jornada EMPRENDE UNA 2019
                    <div class="pull-right">
                        <a href="<?=base_url()?>emprendeuna" class="boton-curso">MUY PRONTO</a>
                    </div>

                </div>
                            <!--
                                <div class="col-sm-12 texto-curso">
                                    Mi Tesis en un póster
                                   <br>
                                    <div class="pull-right">
                                        <a href="<?=base_url()?>poster" class="boton-curso">INFORMACIÓN
                                    </a>
                                    </div>
                                </div>
                            -->
                        </div>
                    </div>

                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 col-md-6 vri-seccion noticias">
                        <div class="content-slider-container">
                            <div class="content-slider" data-animation="fade">
                                <div class="slides-container">

                                    <div class="slide">
                                        <!--<img class="img-responsive" src="<?php echo base_url("vriadds/vri/web/fade-noticias/fade7.jpg");?>" alt="">//-->
                                        <img class="img-responsive" src="http://www.antropologiapuno.com/sites/default/files/una_antro.jpg" alt="">



                                        <div class="description">
                                            <div class="inner">
                                                <p class="title"> ¿Que es un investigador REGINA? </p>

                                                <p>
                                                   La categoria de investigador REGINA es otorgada por el CONCYTEC (Concejo Nacional de Ciencia y Tecnología), mediante una calificación a los docentes registrados en DINA (Directorio Nacional de Investigadores)
                                                   <br><br>
                                                   <strong>¿Como se logra la calificación?</strong>
                                                   <br><br>
                                                   Debe completar los formularios DINA con sus datos personales y sobre todo con publicaciones indexadas y trabajos de investigación
                                                   <br>
                                                   Solicitar que CONCYTEC realice la evaluación y en un plazo de 2 meses le llegan las correcciones y/o las disculpas al no calificar.
                                                   <br><br>
                                                   OJO: Ninguna Universidad otorga esa calificación solo la otorga el <b>CONCYTEC</b>.
                                               </p>
                                           </div>
                                       </div>
                                   </div>

                                   <div class="slide">
                                    <img class="img-responsive" src="<?php echo base_url("vriadds/vri/web/fade-noticias/fade3.jpg");?>" alt="Docentes REGINA">

                                    <div class="description">
                                        <div class="inner">
                                            <p class="title">Docentes registrados en REGINA</p>

                                            <p>
                                                El Vicerrector de Investigación, el Dr. Wenceslao Medina, dio a conocer que esta casa de estudios cuenta con 14 docentes incorporados al Registro de Investigadores en Ciencia y Tecnología del Sistema Nacional de Ciencia y Tecnología e Innovación Tecnológica.
                                            </p>

                                            <a href="#" class="boton-curso">Detalles</a>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-xs-12 col-md-6 vri-seccion">
                    <div class="row rowfix">
                        <!--aqui-->
                        <div class="col-md-4 paddington30 footer-item bg-2">  
                         <h5>
                            CIFRAS DE INVESTIGACIÓN
                            <span class="icon-signal fondo-bottom"></span>
                        </h5>
                    </div>

                    <div class="col-md-8 fill paddington30 bg-0">
                        <div class="col-sm-12 col-md-12 text-center">
                            <div class="numero-cifras-tejecucion">
                                <h1>
                                    <?php
                                    echo $this->dbFedu->proyectosExe();

                                    ?>
                                </h1>Proyectos de investigación docente  en ejecución
                            </div>
                        </div>
                        <div class="row paddington030">	

                            <div class="col-md-6 numero-cifras-tejecucion">
                                <h1>
                                 <?php
                                 /* Número de proyectos de tesis estado 6 */
                                 echo $this->dbPilar->tesisSTD6();
                                 ?>

                             </h1> 
                             Proyectos de tesis en ejecución en PILAR

                         </div>

                         <div class="col-md-6 numero-cifras-tejecucion">
                            <h1>
                                <?php
                                /* Número de tesis sustentadas reales */

                                echo  $this->dbPilar->tesisSTDreal();
                                ?>

                            </h1> 
                            Sustentaciones de tesis de pregrado

                        </div>
                    </div>
                </div>
            </div>

            <div class="row fill paddington30 bg-8">
                <div class="col-md-12 fill">
                    <div class="boxes">
                        <?php

                        $table = $this->dbPilar->getSnapView( "vxSustens", null, "ORDER BY Fecha DESC LIMIT 5" );

                        foreach( $table->result() as $row ) 
                        {
                            $tram = $this->dbPilar->inLastTramDet( $row->IdTramite );
                            $autor = $this->dbPilar->InTesistas( $row->IdTramite );

                            echo "<div class='box'>";
                            echo "<div class='texto-proxima-sustentacion'>
                            PRÓXIMAS SUSTENTACIONES </div>"; 

                            echo "<a href='pilar/sustentas'>"; 

                            echo "<div class='texto-sustentacion'> $tram->Titulo </div>";
                            echo "<div class='texto-autor'> <i> $autor </i> </div>";

                            echo "</a>";

                            echo "</div>";
                        }
                        ?>
                        <!-- Box -->
                    </div> <!-- caja BOXES -->
                </div>
            </div>
        </div>
    </div>

</div>





<script>
    $(document).ready(function(){
	//$("#msgPoster").modal('show');
});
</script>
