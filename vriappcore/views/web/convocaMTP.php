<body class="bg-1">

<!-- PS-->

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe. 
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. 
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>

<!-- fin PS -->



<!-- TUTORIAL-->

<script type="text/javascript">
    function mostrartutorial(){
        $('#tutorial').modal('show');
    };
</script>

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"  id="tutorial" >
 

  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

  

                      <!-- Wrapper for slides -->
                      <div class="carousel-inner">

                        <div class="item active">
                          <img class="img-responsive" src="<?=base_url("vriadds/pilar/imag/img3mt/paso0.jpg");?>" alt="...">
                        
                        </div>

                        <div class="item">
                          <img class="img-responsive" src="<?=base_url("vriadds/pilar/imag/img3mt/paso1.jpg");?>" alt="...">
                       
                        </div>

                         <div class="item">
                          <img class="img-responsive" src="<?=base_url("vriadds/pilar/imag/img3mt/paso2.jpg");?>" alt="...">
                         
                        </div>

                        <div class="item">
                          <img class="img-responsive" src="<?=base_url("vriadds/pilar/imag/img3mt/paso3.jpg");?>" alt="...">
                        
                        </div>

                      <div class="item">
                          <img class="img-responsive" src="<?=base_url("vriadds/pilar/imag/img3mt/paso4.jpg");?>" alt="...">
                     
                        </div>



                      </div>

            <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
</div>
    </div>
  </div>



</div>


<!-- FIN TUTORIAL  -->

<div class="container ">

    <div class="container-fluid Aligner paddington30 vri-header">
     
        <img class="img-responsive pull-left vri-logo-small" src= "<?php echo base_url("vriadds/vri/web/logo-vri2.png");?>" >
       <span class="icon-award  izquierda gi-15x">Convocatorias y concursos</span>    
    
    </div>



  
        
 <div class="navbar navbar-default navbar-paginas">
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
        
            <ul class="nav navbar-nav navbar-left">
                <li><a href="http://vriunap.pe/"> <span class="glyphicon glyphicon-home gi-15x"></span>  </a></li>
                               
            </ul>



            <ul class="nav navbar-nav navbar-right">
              <li class="actual"><a href="<?php echo base_url("web/convocatoriaMTP");?>">CONCURSO MI TESIS EN UN POSTER</a></li>
              
           

                <li><a href="<?php echo base_url("web/inscritosMTP");?>">POSTULANTES APTOS</a></li>
              


              <?php 
               /*
                <li><a href="<?php echo base_url("web/inscritos3mt");?>">INSCRITOS MTP</a></li>
                <li><a href="<?php echo base_url("web/resultados");?>">RESULTADOS</a></li>
                */
              ?>
                
            </ul>
      </div>
    

    </div>
</div>
   
        
  


  <div class="container-fluid">
    <div class="row rowfixtop">
      <div class="col-xs-12 col-md-6  paddington30 bg-0">
        
         <?php 
         /*
         <img class ="img-responsive" src="<?php echo base_url("vriadds/vri/web/convocatorias/curso1-3mt.jpg");?>"> 
         */
         ?>
                 
      <img class="img-responsive" src="<?=base_url("vriadds/vri/web/convocatorias/MTP-POSTER-2018.jpg");?>" alt="...">        
        
         

      </div>

        <div class="col-xs-12 col-md-6 paddington30 bg-0">
         <div class ="contenido">

                 <h1>MI PROYECTO DE TESIS EN UN PÓSTER</h1>
                 <section>
                El concurso "Mi proyecto de tesis en un póster" es un concurso de comunicación de la investigación desarrollado por el Vicerrectorado de Investigación de la Universidad Nacional de Ucayali, en
el que los participantes deberán consolidar las ideas de su tesis y presentarlas de manera gráfica y creativa en un póster.
                 </section>

                <h2>Requisitos</h2>
                
                <section>
              <li>Constancia de Matrícula en el Semestre 2018-II (Estudiantes)</li>
               <li>Resumen del póster en texto considerando todas las secciones (Máximo una hoja papel A-4, tipo de letra Arial, tamaño 12)</li>
              <li>Asistir al Curso de capacitación “Cómo elaborar un Póster” en las fechas programadas por el VRI. </li>

                </section>

                <h2>Inscripción</h2>
                
                <section>
                <ul>
                   <li>Postulación:Vía Plataforma PILAR</li>
                   
                </ul>   

                </section>

                <h2>Premios</h2>
                
                <section>
                 
                      <table style="text-align: center;">
                      <tbody>
                        <tr>
                          <td rowspan="2">INVESTIGACIÓN</td>
                          <td colspan="3"><b>Puesto</b></td>
                        </tr>
                        <tr>
                          <td><b>Primero </b></td>
                          <td><b>Segundo</b> </td>
                          <td><b>Tercero</b> </td>
                        </tr>
                        <tr>
                          <td>Área de  Ingeniería </td>
                          <td><small>S/.3.500.00   </small> </td>
                          <td><small>S/.2,500.00   </small></td>
                          <td><small>S/.1,500.00   </small></td>
                        </tr>
                        <tr>
                          <td>Área de Ciencias Sociales </td>
                          <td><small>S/.3.500.00</small> </td>
                          <td><small>S/.2,500.00 </small></td>
                          <td><small>S/.1,500.00 </small></td>
                        </tr>
                        <tr>
                          <td>Área de Biomédicas </td>
                          <td><small>S/.3.500.00</small> </td>
                          <td><small>S/.2,500.00 </small></td>
                          <td><small>S/.1,500.00 </small></td>
                        </tr>
                        <tr>
                          <td>Área de Ciencias Económicas  y Empresariales </td>
                          <td><small>S/.3.500.00</small> </td>
                          <td><small>S/.2,500.00 </small></td>
                          <td><small>S/.1,500.00 </small></td>
                        </tr>
                      </tbody>
                    </table>


                </section>



               <h2>Calendario</h2>
                
                <section>
                     <ul>
                       <li>Postulaciones: Del 11  al 31 de octubre del 2018</li>
                       <li> Fecha/lugar: 8:30-13:00 hrs/ Vicerrectorado de investigación</li>
                       <li> Exposición de posters y resultados: 19 de noviembre del 2018</li>
                       <li> Fecha/lugar: 8:00 hrs / Centro de Investigación Continua </li>

                    </ul>
                </section>


               <h2>Información</h2>
                
                <section >
                  <span class="glyphicon glyphicon-info-sign"> </span> Oficina de Vicerrectorado de Investigación, Ciudad Universitaria<br> 
                  <span class="glyphicon glyphicon-envelope"> </span> viceinvestigacion@unap.edu.pe<br>
                  <span class="glyphicon glyphicon-earphone"> </span>  +51-51-365054
                </section>
                
                <br>
                
              <section class="descargas paddington30">

                     <a  target ="_blank" href="http://www.vriunap.pe/pilar"><span class="glyphicon glyphicon glyphicon-pencil gi-15x text-center" > </span> Ir a la Plataforma PILAR e inscribirme</a> 

                </section>


                <section class="descargas paddington30">

                     <a  target ="_blank" href="<?php echo base_url("vriadds/vri/web/convocatorias/BASESMTP.pdf");?>"><span class="glyphicon glyphicon-cloud-download gi-15x text-center" > </span> Descargar bases del concurso (PDF)</a> 

                </section>


                <section class="descargas paddington30">

                     <a  target ="_blank" href="<?php echo base_url("vriadds/vri/web/convocatorias/diapositivaPOSTER.pdf");?>"><span class="glyphicon glyphicon-cloud-download gi-15x text-center" > </span> Diapositivas capacitación (PDF)</a> 

                </section>


        </div> <!-- Div contenido -->
         


  
        </div>
    
    </div>
  </div>

