
<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" rel="home" href="<?=base_url("pilar")?>" title="Universidad Nacional de Ucayali | Vicerrectorado de Investigación">
                <img class="img-responsive" style="max-width:160px; margin-top: -15px;"
                     src="<?=base_url("vriadds/pilar/imag/logos-u-v-p.png");?>">
            </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?=base_url("pilar")?>">Inicio</a></li>
              <!--  <li><a href="<?=base_url("pilar/docentes")?>">Docentes</a></li>
                <li><a href="<?=base_url("pilar/tesistas")?>">Tesistas</a></li>
                <li><a href="<?=base_url("pilar/cordinads")?>">Coordinadores</a></li>
                <li><a href="<?=base_url("pilar/sustentas")?>">Sustentaciones</a></li> comentado unuv1.0-->
            </ul>
        </div>
    </div>
</div>

<div class="container info-pilar margin">
  <img class="img-responsive logo-pilar3" src="<?=base_url("vriadds/pilar/imag/pilar-n.png");?>">
  <h3 id="name-pilar">Plataforma de Investigación Universitaria <br>Integrada a la Labor Académica con Responsabilidad </h3><h4><i>Universidad Nacional de Ucayali</i></h4>
</div>

<div class="container ">
  <div class="col-md-12 contenido1">
      <div class="col-md-9 bg-white margin">
        <div class="titulo">Preguntas Frecuentes</div>
        <div class="row">
           
               <!-- Acordeon--> 
               <div class="panel-group" id="accordion">
          
              <?php 
              foreach ($faqs->result() as $columna) 
              {
                  # code... "#collapse3"
              ?>
              <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="<?php echo "#collapse$columna->idpregunta"; ?>" >
                       <span   class="glyphicon glyphicon-plus gi-1x"> </span>
                       <?php echo $columna->pregunta;?>
                      </a>
                    </h4>
                  </div>
                  <div id= "<?php echo "collapse$columna->idpregunta"; ?>" class="panel-collapse collapse">
                    <div class="panel-body">  
                    <?php echo $columna->respuesta;?>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>
             </div> 
              <!-- Fin acordeón -->
    
         

       
        </div>
      </div>
      <div class="col-md-3 bg-white margin-bd">
        <div class="titulo">Reglamentos y Manuales</div>
        <div class="list-group">
          <ul class="nav nav-pills bderecha"> 
            <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/Reglam-de-ProyTesis-2016.pdf");?>" class="list-group-item blink"><span class="glyphicon glyphicon-book"></span> Reglamento Proyectos</a>
           <!-- <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/resReglaBorrador.pdf");?>" class="list-group-item blink"><span class="glyphicon glyphicon-book"></span> Reglamento de Borrador</a> comentado unuv1-0 -->
            <hr>

          <a onclick="formatoProyecto()" class="list-group-item blink"><span class="glyphicon glyphicon-bookmark"></span> Formato de Proyecto</a>
            
            
            <!-- <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/Formato-Borrador-Tesis-2017.docx");?>"  class="list-group-item blink"><span class="glyphicon glyphicon-bookmark"></span> Formato de Borrador <span class="label label-warning"> Nuevo </span>  </a>comentado unuv1-0 -->
             <hr>
            <!--<a href="#" class="list-group-item blink"><span class="glyphicon glyphicon-th-list"></span> Manual para Docentes</a> comentado unuv1.0-->
            <a target="_blank" href="<?php echo base_url("vriadds/pilar/doc/manual_tesistav3.pdf");?>" class="list-group-item blink"><span class="glyphicon glyphicon-th-list"></span> Manual para Tesistas </a>
            <!--<a href="#" class="list-group-item blink"><span class="glyphicon glyphicon-th-list"></span> Manual para Coordinadores</a> comentado unuv1-0-->
            <hr>
            <a target="_blank" href="<?php echo base_url("/pilar/web/preguntas");?>" class="list-group-item blink"><span class="glyphicon glyphicon-th-list"></span> Preguntas frecuentes </a>
          </ul>
        </div>
      </div>
 

      <div class="col-md-12 bg-vino footer">
        Universidad Nacional de Ucayali<br>
        Rectorado<br>        
        <b> Contactenos : soporte_pilar@unu.edu.pe </b> <!--agregado unuv1-0-->
   <!--     Dirección General de Investigación<br>
        &copy; Plataforma de Investigación y Desarrollo</a> comentado unuv1.0-->
      </div>
  </div>
</div>
</body>
<!-- modal de formato de proyecto - Agregado unuv1.0 -->
<div class="modal fade bs-example-modal-lg" tabindex="-4" role="dialog"  id="formatoProyecto" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
          <!-- Wrapper for slides -->
          <div class="carousel-inner">
              <div class="item active">
                  <img class="img-responsive" src="<?=base_url("vriadds/pilar/imag/formatos/AvisoFormato.jpg");?>" alt="...">
              </div>              
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- FIN MODAL FORMATO DE PROYECTO -->
<script>

// agregado unuv1.0
function formatoProyecto()
{
  $("#formatoProyecto").modal('show');
}


</script>

</html>
