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
                <li><a href="<?=base_url("pilar/docentes")?>">Docentes</a></li>
                <li><a href="<?=base_url("pilar/tesistas")?>">Tesistas</a></li>
                <li><a href="<?=base_url("pilar/cordinads")?>">Coordinadores</a></li>
                <li><a href="<?=base_url("pilar/sustentas")?>">Sustentaciones</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container info-pilar margin" style="height: 23%">
  <img class="img-responsive logo-pilar3" src="<?=base_url("vriadds/pilar/imag/pilar-n.png");?>">
</div>
<div class="" style="height: 3px; background:rgb(149,0,68)"> </div>


<div class="container">
  <div class="col-md-12 contenido1">
      <!-- begin -->
      <div class="col-md-12 bg-white" style="padding-top: 15px">
          <div class="titulo"> Listado de Sustentaciones </div>
          <table class="table table-striped table-hover">
              <thead>
                  <tr>
                      <th> Nro </th>
                      <th class="col-md-2"> Trámite </th>
                      <th class="col-md-2"> Fecha </th>
                      <th class="col-md-5 hidden-xs"> Título de Proyecto </th>
                      <th class="col-md-2"> E.P. </th>
                      <th class="col-md-2"> Info </th>
                  </tr>
              <?PHP

                // just for counting...
                $table = $this->dbPilar->getSnapView( "vxSustens" );
                $total = $table->num_rows();

                // shortest list
                $table = $this->dbPilar->getSnapView( "vxSustens", null, "ORDER BY Fecha DESC LIMIT 250" );

                foreach( $table->result() as $row ){

                    $btpdf = ($row->Pendiente)?
                             "<button onclick='loadAviso($row->Id)' class='btn btn-info'> Aviso </button>" :
                             "<button onclick='loadAviso($row->Id)' class='btn btn-default'> Aviso </button>" ;
                    $btest = ($row->Pendiente)?
                             "<button class='btn btn-xs btn-success'> Nuevo </button>"     :
                             "<button class='btn btn-xs btn-default'> Realizado </button>" ;
                    $class = ($row->Pendiente)? "warning" : "";

                    if( $row->Activo == 0 )
                        $btpdf = "<button class='btn btn-danger'> Reprogramar </button>";

                    $tram = $this->dbPilar->inProyTram( $row->IdTramite );
                    $nomb = $this->dbPilar->inTesistas( $row->IdTramite );
                    $dets = $this->dbPilar->inLastTramDet( $row->IdTramite );
                    $fech = mlFechaNorm( $row->Fecha );

                    echo "<tr class='$class''>";
                    echo "<td> $total </td>";
                    echo "<td> <b>$row->Codigo</b> <br> $btest </td>";
                    //echo "<td> $fech<br> <small>(E) $tram->Tipo : $tram->Estado</small> </td>";
                    echo "<td> $fech </td>";
                    echo "<td  class='hidden-xs'> <small>$dets->Titulo</small> </td>";
                    echo "<td> <small>$row->Carrera</small> </td>";
                    echo "<td> $btpdf </td>";
                    echo "</tr>";

                    $total--;

                    //if( $total < 800 )
                    //    break;
                }
              ?>
              </thead>
              <tbody>
              </tbody>
          </table>
      </div>

      <div class="col-md-4 col-md-offset-4">
          <button class='btn btn-success btn-block'> >> Ver listado completo &lt;&lt; </button>
          <hr>
      </div>
      <!-- end -->

      <div class="col-md-12 bg-vino footer">
        Universidad Nacional de Ucayali<br>
        Vicerrectorado de Investigación<br>
        Dirección General de Invesatigación<br>
        &copy; Plataforma de Investigación y Desarrollo</a>
      </div>
  </div>
</div>



<!-- MODAL  -->
<div class="modal" role="dialog" id="pdfDlg">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content modal-pilar">
            <div class="modal-header modal-pilar-title">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> Defensa de Tesis </h4>
            </div>
            <div class="modal-body modal-pilar modal-pilar-content">
                <iframe id="iframe" height=610 width=100% frameborder=0 src=""></iframe>
                <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar Aviso </button>
            </div>
            <!--
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar Aviso </button>
            </div>
            -->
        </div>

    </div>
</div>
<!-- /MODAL  -->
