<body class="bg-1">



<!-- TUTORIAL-->

<script type="text/javascript">
    function mostrartutorial(){
        $('#tutorial').modal('show');
    };
</script>


<!-- FIN TUTORIAL  -->

<div class="container ">

    <div class="container-fluid Aligner paddington30 vri-header">
     
      <img class="img-responsive pull-left vri-logo-small" src= "<?php echo base_url("vriadds/vri/web/logo-vri2.png");?>" >
      <img class="img-responsive pull-left vri-logo-small" src= "<?php echo base_url("vriadds/vri/web/laspau.png");?>" >
       <center><span class="icon-award  izquierda gi-15x"><br>PROGRAMA DE ENSEÑANZA Y APRENDIZAJE PARA LA INNOVACIÓN Y LA INVESTIGACIÓN EN LA UNA PUNO</span></center>   
    
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
              <li class="actual"><a href="<?php echo base_url("web/programalaspau");?>">INFORMACIÓN</a></li>
               
                <li><a href="<?php echo base_url("web/programalaspau");?>">LISTA DE POSTULANTES</a></li>
              
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
         */
         ?>
         <!-- <img class ="img-responsive" src="<?php echo base_url("vriadds/vri/web/convocatorias/curso1-3mt.jpg");?>">  -->
                 
      <img class="img-responsive" src="<?=base_url("vriadds/vri/web/convocatorias/reprog.JPG");?>" alt="...">        
      <img class="img-responsive" src="<?php echo  base_url("vriadds/vri/web/convocatorias/blaspau2.jpg");?>" alt="...">        
      <img class="img-responsive" src="<?php echo  base_url("vriadds/vri/web/convocatorias/blaspau1.jpg");?>" alt="...">  

   

      </div>

        <div class="col-xs-12 col-md-6 paddington30 bg-0">
          
         <div class ="contenido">

        <h2>Comunicado</h2>

        <section>
       <div class="text-secundary">
           El primer cohorte se reprogramará para la primera semana de Octubre y el segundo cohorte para la tercera semana de Octubre.
           
           <p>
           </div>   
     
        <h2>Resultados de la convocatoria </h2>
                <div class="panel panel-success">
                  <div class="panel-heading">
                    
                    
                  </div>
                 <section class="panel-body">
                      <form action="<?php echo base_url("web/laspauConsulta");?>" method="post" accept-charset="utf-8">
                        <label>Digite su DNI :</label>
                        <input type='number' name='dni'>
                        <button class="btn btn-success" type='submit'> <i class="glyphicon glyphicon-search"></i> Buscar</button>
                      </form>
                 </section>

                </div>
          </section>


               <h2>Información</h2>
                
                <section >
                  <span class="glyphicon glyphicon-info-sign"> </span> Oficina de Vicerrectorado de Investigación, Ciudad Universitaria<br> 
                  <span class="glyphicon glyphicon-envelope"> </span> viceinvestigacion@unap.edu.pe<br>
                  <span class="glyphicon glyphicon-earphone"> </span>  +51-51-365054
                </section>
                
                <br>
                     
<!-- 

                  <section class="descargas paddington30">

                         <a  target ="_blank" href="http://www.vriunap.pe/pilar"><span class="glyphicon glyphicon glyphicon-pencil gi-15x text-center" > </span> Ir a la Plataforma PILAR e inscribirme</a> 

                    </section>
                 
-->

<!-- 
                <section class="descargas paddington30">

                     <a  target ="_blank" href="<?php echo base_url("vriadds/vri/web/convocatorias/BASESMTP.pdf");?>"><span class="glyphicon glyphicon-cloud-download gi-15x text-center" > </span> Descargar bases del concurso (PDF)</a> 

                </section>
 -->



        </div> <!-- Div contenido -->
         


  
        </div>
    
    </div>
  </div>

