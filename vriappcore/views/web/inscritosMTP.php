<body class="bg-1">
<body class="bg-1">




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
                <li><a href="<?php echo base_url("poster");?>">CONCURSO MI TESIS EN UN PÓSTER</a></li>
               
                <li class="actual"><a href="<?php echo base_url("web/inscritosmtp");?>">POSTULANTES APTOS</a></li>
               <?php 
                
                /*
                <li><a href="<?php echo base_url("web/resultados");?>">RESULTADOS</a></li>
                */
              ?>
                
            </ul>
      </div>
    
 
    </div>
</div>
   
        
  


<div class="container-fluid">
  <div class="row rowfixtop">

    <div class="col-xs-12 col-md-12 paddington30 bg-0">
    <div class ="contenido">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>N°</th>
              <th width="30%">Tesista</th>
              <th>Escuela Profesional</th>  
              <th>Título de Proyecto</th>
              <th>Cod</th>
            </tr>
          </thead>
          <tbody>
        <?php 
          $insq=$this->dbPilar->getTable("2posTer", "apto=1");



          $flag=1;
          foreach ($insq->result() as $row) 
          {
            $tes=$this->dbPilar->inTesistas($row->IdProyecto);


            
          echo "<tr>
                  <td>$flag</td>
                <td>".$tes."</td>
                <td><b>".$this->dbRepo->inCarrera($row->IdCarrera)."</b></td>
                  <td>$row->Titulo</td>
                  <td>$row->Codigo</td>
                </tr> ";
                $flag++;
          }
        ?>
          </tbody>
        </table>
      </div>
    </div> <!-- Div contenido -->
    </div>

  </div>
</div>
