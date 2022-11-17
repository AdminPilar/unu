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


<div class="container ">
  <div class="col-md-12 contenido1">
      <!-- begin -->
      <div class="col-md-12 bg-white" style="padding-top: 15px">
          <div class="titulo"> Proyectos de Investigación de Pregrado </div>
          <table class="table table-striped table-hover" style="font-size: 12px">
              <thead>
                  <tr>
                      <th> Nro </th>
                      <th class="col-md-1 hidden-xs"> Código </th>
                      <th class="col-md-2"> Nombre(s) de Tesista(s) </th>
                      <th class="col-md-2"> Linea de Investigación </th>
                      <th class="col-md-5"> Título de Proyecto </th>
                      <th class="col-md-3"> E.P. </th>
                  </tr>
              <?PHP
                $total = $proy->num_rows();

                foreach( $proy->result() as $row ){

                    $dets = $this->dbPilar->inLastTramDet( $row->Id );
                    $nomb = $this->dbPilar->inTesistas( $row->Id );
                    $carr = $this->dbRepo->inCarrera( $row->IdCarrera );
                    $line = $this->dbRepo->inLineaInv( $row->IdLinea );

                    if( $row->Estado >= 14 )
                        $esta = "<button class='btn btn-success btn-xs'> TESIS </button>";

                    elseif( $row->Estado>=10 && $row->Estado<=13 )
                        $esta = "<button class='btn btn-info btn-xs'> En Borrador </button>";

                    elseif( $row->Estado==6 )
                        $esta = "<button class='btn btn-warning btn-xs'> Proyecto </button>";

                    else
                        $esta = "";

                    echo "<tr>";
                    echo "<td> $total </td>";
                    echo "<td> <b>$row->Codigo</b><br>$esta </td>";
                    echo "<td> $nomb </td>";
                    echo "<td> $line </td>";
                    echo "<td> $dets->Titulo </td>";
                    echo "<td> $carr </td>";
                    echo "</tr>";
                    $total--;
                }
              ?>
              </thead>
              <tbody>
              </tbody>
          </table>
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
<!--
<div class="modal" role="dialog" id="pdfDlg">
<div class="modal-dialog modal-lg">
  <div class="modal-content modal-pilar">
    <div class="modal-header modal-pilar-title">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"> Defensa de Tesis </h4>
    </div>
        <div class="modal-body modal-pilar modal-pilar-content">
            <iframe id="iframe" height=610 width=100% frameborder=0 src=""></iframe>
            <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar Aviso </button>
        </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal"> Cerrar Aviso </button>
    </div>
  </div>

</div>
</div>
-->
<!-- /MODAL  -->