<?php 
      $sess  = $this->gensession->GetData();

                $tram = $this->dbPilar->inTramByTesista($sess->userId);
                $autors = $this->dbPilar->inTesistas( $tram->Id );
                $titulo = $this->dbPilar->inLastTramDet( $tram->Id )->Titulo;
                $archivo =$this->dbPilar->inLastTramDet( $tram->Id )->Archivo;
                $link = base_url( "repositor/docs/$archivo" );
                $lineai = $this->dbRepo->inLineaInv( $tram->IdLinea );
                $estados = $this->dbPilar->getTable("dicEstadTram");
                $esta='';
                $plazo='';
                $fech=strtotime($tram->FechModif);
                $fech= date("d/m/Y",$fech);
                $diasp  = mlDiasTranscHoy( $tram->FechModif );
                foreach($estados->result() as $row){
                    if($row->Id ==$tram->Estado){
                        $esta=$row->Descrip;
                        $plazo = $row->Plazo ." ".$row->Tipo;
                    }
                    }

 
?>

<!----------------principal------------------------->
<div class='panel panel-info' id='total'>
  <div class='panel-heading'>
    <h2 class='panel-title'> Estado del Tramite </h2>
  </div>
   <div class='panel-body' >
      <b>Codigo proyecto : </b><?=$tram->Codigo?><br>
      <b>Titulo : </b><?=$titulo?><br>
      <b>Estado : </b><?=$esta?><br>
      <b>Fecha Actualización : </b><?=$fech?><br>
      <b>Plazo: </b><?=$plazo?> <br>
      <b>Tiempo: </b><?=$diasp?> dia(s) <br>
      <b>Archivo PDF: </b><a href='$link' target=_blank title='Proyecto de Tesis' ><span class='glyphicon glyphicon-list-alt'></span> </a> <br> 
      <b>Observacion : </b>Antes de iniciar este procedimiento le recordamos que debe poner en texto
       rojo con fondo blanco los parrafos o elementos que su jurado le ha indicado
       corregir, por ello revise bien su <b>proyecto de tesis</b> asi evitar
       el rechazo del mismo.
<br><br><br>
       <center>
  <p>
    <button class="btn btn-primary" onclick="lodShifs(3)">
            <span class="glyphicon glyphicon-folder-open" ></span>
      &nbsp;&nbsp; Ver mis Observaciones &nbsp;&nbsp;
        </button>

        <?php

            // MEDICINA HUMANA = 32
            //
            //if( $sess->IdCarrera == 32 ):
            if( true ):
        ?>

    <button class="btn btn-warning" onclick="lodShifs(22)">
            <span class="glyphicon glyphicon-upload" ></span>
      &nbsp;&nbsp;&nbsp;&nbsp; Subir PDF con Correcciones
        </button>

         <?php endif ?>
  </p>
  </center>
  
</div>
</div>

<!----------------fin principal------------------------->

<!----------------Visualizar obsrvaciones------------------------->
<div id='Final' style="display: none;" class="panel panel-info">
 <div class="panel-heading">
        <h2 class="panel-title">Observaciones de los miembros del jurado</h2>
    </div><br>
    <div>
      <?php
       $TipoMiembro = array (
        "Presidente",
        "Primer Miembro",
        "Segundo Miembro"
    );
      echo "<ul>"; 
        $count=0;
                $doc = array(
                    1=>$this->dbRepo->inDocenteRow($tram->IdJurado1),
                    2=>$this->dbRepo->inDocenteRow($tram->IdJurado2),
                    3=>$this->dbRepo->inDocenteRow($tram->IdJurado3),
                );
                for ($i=1; $i <=3 ; $i++) { 
                    if($doc[$i]){
                        $status=($doc[$i]->Activo >= 5)?"(Docente Habilitado)":"(<b>OBSERVADO</b>Necesita Cambio)";
                        $kind=($doc[$i]->Activo >= 5)?"success":"danger";
                        echo "<li class='text-$kind'> $status |  ".$TipoMiembro[$i-1] ." | ".$doc[$i]->DatosPers."  </li>";
                    }
                }
         echo "</ul>";

      ?>
    </div>
     <div class="panel-body" id="plops">
      <div id="blq1" class="col-md-12" style="background: #FFFFFA">
    <ul class='nav nav-tabs'>
        <li class='active'><a data-toggle='tab' href='#tab1'> Presidente </a></li>
        <li><a data-toggle='tab' href='#tab2'> Primer Miembro </a></li>
        <li><a data-toggle='tab' href='#tab3'> Segundo Miembro </a></li>
    </ul>
    <div class='tab-content'>
    <?php
    for( $i=1; $i<4; $i++ ) {

      $extra = ($i==1) ? "in active" : "";
      echo "<div id='tab$i' class='tab-pane fade $extra pre-scrollable' style='height: 320px'>";
            //unuv1.0 agregado en caso el docente haya realizo la aprobacion del proyecto
            if($i==1)
            {
                if($detTram->vb1==2)
                {
                    echo "<p><b>Aprobo, sin observaciones</b> </p>";
                }
            }
            if($i==2)
            {
                if($detTram->vb2==2)
                {
                    echo "<p><b>Aprobo, sin observaciones</b> </p>";
                }
            }
            if($i==3)
            {
                if($detTram->vb3==2)
                {
                    echo "<p><b>Aprobo, sin observaciones</b> </p>";
                }
            }

            foreach( $arrCorr[$i]->result() as $row ) {
        $fecha = mlFechaNorm( $row->Fecha );
        echo "<p><b>[ $fecha ]</b> : $row->Mensaje </p>";
      }
      echo "</div>";
    }
    ?><hr>
     <div class="form-group">
                  <div class="col-md-12">
                      <center>
                      <button type="button"class="btn btn-danger" onclick="lodShifs(4)">
                          <span class="glyphicon glyphicon-circle-arrow-left" ></span>
                          &nbsp; Atras
                      </button>  &nbsp; &nbsp;
                                         
                  </div>
              </div>
    </div>
  </div>
     </div>
</div>
<!---------------fin Visualizar obsrvaciones-------------------------->




<!----------------Verificacion de correciones------------------------->

<div id='verifica' style="display: none;" class="panel panel-info">
 <div class="panel-heading">
        <h2 class="panel-title">Proyecto de Tesis Corregido</h2>
    </div>
    <div class='panel-body'  id='bodive'>
      <?php
    // comprobamos que haya correcciones sin VB
    $totCorrs1 = $arrCorr[1]->num_rows();
    $totCorrs2 = $arrCorr[2]->num_rows();
    $totCorrs3 = $arrCorr[3]->num_rows();

    // controlar vb
    if( $detTram->vb1 >0 && $detTram->vb2 >0 && $detTram->vb3 >0 ) {

    } else {

      // si falta de alguno, muestra y termina
      if(  $totCorrs1==0 or $totCorrs2==0 or $totCorrs3==0 )
      {
        echo "";
        echo "Presidente      : <b> " .($detTram->vb1==0? '' : ($detTram->vb1==1? 'Observado' : ($detTram->vb1==2?'Aprobado' : -1))). "</b>";
        echo "<br>Primer Miembro  : <b> " .($detTram->vb2==0? '' : ($detTram->vb2==1? 'Observado' : ($detTram->vb2==2?'Aprobado' : -1)))."</b>";
        echo "<br>Segundo Miembro : <b> " .($detTram->vb3==0? '' : ($detTram->vb3==1? 'Observado' : ($detTram->vb3==2?'Aprobado' : -1)))."</b>";    

      ?>
      <div class="form-group">
                  <div class="col-md-12">
                      <center>
                      <button type="button"class="btn btn-danger" onclick="lodShifs(4)">
                          <span class="glyphicon glyphicon-circle-arrow-left" ></span>
                          &nbsp; Atras
                      </button>  &nbsp; &nbsp;
                      
                      </center>                     
                  </div>
              </div>
               <?php
        return;
      }
    }
    ?>
    <div class="panel-body" id="plops">

      <div id="plock" style="display: none; z-index: 1000; position: fixed; left: 0; top: 0; width:100%; height:100%; padding: 300px; background: rgba(0,0,0,0.5)">
          <div style="margin: 0 auto; width: 320px; height: 80px; background: white; padding: 15px">
            <center> <b> Enviando datos y borrador, espere ... </b> </center>
            <div class="progress progress-striped active" style="margin-bottom:0;">
            <div class="progress-bar" style="width: 100%"></div></div>
          </div>
      </div>

      <!-- form -->
      <form class="form-horizontal" id="frmborr" method="POST" onsubmit="grabaCorr(); return false"
            accept-charset="utf-8" enctype="multipart/form-data">
          <fieldset>
              <?php
                //
                // local Id(s), IdTramite
                //
               
              ?>

              <!-- area de datos a almacenar en la BD -->
              <!-- select areas -->
              <div class="form-group success">
                  <div class="col-md-4" align="left"> 
                  <label class="control-label" style="color:green"> Linea de Investigación </label>
                </div>
                  <div class="col-md-7" style="padding-top:7px"> <?=$lineai?> </div>
              </div>

               <div class="form-group success">
                <div class="col-md-4" align="left"> 
                  <label class=" control-label"> Autor(es) </label>
                </div>
                  <div class="col-md-7" style="padding-top:7px"> <?=$autors?> </div>
                  <div class="col-md-4"></div>
              </div>
              <hr>

               <div class="form-group success">
                <div class="col-md-4" align="left"> 
                  <label class=" control-label"> Proyecto de Tesis (*)</label>
                  <input id="facultadId" name="facultadId" type="hidden" value="<?php //echo $facultadId; ?>" >
                </div>
                  <div class="col-md-7">
                    <input name="nomarch" id="nomarch" type="file" class="file form-control input-md" required>
                    <span id="filemsg" class="help-block"> <center>Puede subir un PDF con un máximo de 10MB</center> </small></span>
                  </div>
              </div>
               <div class="form-group success">
                <div class="col-md-4" align="left"> 
                  <label class="control-label"> Titulo de Proyecto (*) </label>
                </div>
                  <div class="col-md-7">
                      <textarea name="nomproy" type="text" class="form-control" rows="3" style="text-transform: uppercase" required><?=$titulo?></textarea>
                  </div>
              </div>
               <!--<div class="form-group success">
                <div class="col-md-4" align="left"> 
                  <label class="control-label"> Resumen (Abstract) (*) </label>
               
                </div>
                  <div class="col-md-7">
                      <textarea name="resumen" id='resumen' type="text" class="form-control" rows="3" placeholder="acepta varias lineas" required></textarea>
                  </div>
              </div> -->
              <!-- Text area -->
             <!-- <div class="form-group success">
                <div class="col-md-4" align="left"> 
                  <label class="control-label"> Palabras clave (keywords) (*) </label>
                </div>
                  <div class="col-md-7">
                      <input name="pclaves" id='pclaves' type="text" class="form-control input-md" placeholder="separadas por coma y acaba en punto" required>
                      <!-- <span class="help-block">  </span> -->
                  <!--</div>
              </div>-->
               
               <div class="form-group">
                  <div class="col-md-12">
                      <center>
                      <button type="button"class="btn btn-danger" onclick="lodShifs(4)">
                         <span class="glyphicon glyphicon-circle-arrow-left" ></span>
                          &nbsp; Atras
                      </button>  &nbsp; &nbsp;
                      <button type="submit" class="btn btn-primary">
                          <span class="glyphicon glyphicon-save"></span> &nbsp; Enviar Proyecto
                      </button>  
                      </center>                     
                  </div>
              </div>           
          </fieldset>
      </form>
      <!-- form -->
    </div>


    </div>
     

     </div> 
     <!----------------finn Verificacion de correciones------------------------->
























