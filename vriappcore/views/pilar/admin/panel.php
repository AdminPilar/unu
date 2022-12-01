    <!-- menu -->
    <div class="col-md-12 panel-trabajo"  style="background-color: white">

        <!-- Panel de Navegación -->
        <!--
        <ol class="breadcrumb">
            <li><a href="<?=base_url("pilar/admin")?>"> Inicio </a></li>
            <li><a href="#">Tesistas</a></li>
            <li><a href="#">Docentes</a></li>
            <li><a href="#">Reportes</a></li>
        </ol>
        -->
        <div class="admin-title-ws col-wine"> </div>

        <!-- /Panel de Navegación -->
        <div class="col-md-12" id="panelView">
          <div class="col-md-6 workspace">
            Tiempo de carga: <strong> {elapsed_time} s</strong><hr>
            <table class="table table-bordered table-striped">
                <tr>
                    <th> Tramite </th>
                    <th> En curso </th>
                    <th> Aprobados </th>
                </tr>
            <?php // ----------------------------------------------

                $sess = $this->gensession->GetData( PILAR_ADMIN );

                $totproy = $this->dbPilar->getTable("tesTramites")             ->num_rows();
                $pyaprob = $this->dbPilar->getTable("tesTramites","Estado>=6") ->num_rows();
                $curproy = $this->dbPilar->getTable("tesTramites","Tipo=1")    ->num_rows();
                $curborr = $this->dbPilar->getTable("tesTramites","Tipo=2")    ->num_rows();
                $totborr = $this->dbPilar->getTable("tesTramites","Estado>=13")->num_rows();
                $totsust = $this->dbPilar->getTable("tesTramites","Tipo=3")    ->num_rows();

                echo "<tr>";
                echo "<td> Proyectos de Tesis </td>";
                echo "<td> $curproy </td>";
                echo "<td> $pyaprob </td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td> Borradores de Tesis </td>";
                echo "<td> $curborr </td>";
                echo "<td> $totborr </td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td> Sustentaciones </td>";
                echo "<td> * </td>";
                echo "<td> $totsust </td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td> Total trámites </td>";
                echo "<td> <b>" .($curproy+$curborr+$totsust). "</b> <small style='color:gray'>($totproy)</small> </td>";
                echo "<td> </td>";
                echo "</tr>";
            ?>
            </table>


          </div>
        </div> <!-- fin de panel -->
    </div>

	<!-- Menu de Administrador  -->
	<div class="sidenav no-print collapse in" id="mnuFred"> <!-- hidden-print -->
		<div class="admin-title col-wine"> Panel de Opciones</div>
        <?php if( $sess->userLevel < 4 ) { ?>
		<div class="admin-title1 col-wine">Pilar</div>
		<div class="list-group">
          <ul class="nav nav-pills bderecha">
          	<a href="<?=base_url("pilar/admin")?>" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Inicio</a>
            <a onclick="lodPanel('admin/panelProys')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-th-list"></span> Proyectos de Tesis</a>
            <a onclick="lodPanel('admin/panelBorrs')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Borrador de Tesis</a>
            <a onclick="lodPanel('admin/panelSuste')" href="javascript:void(0)" href="#" class="list-group-item"><span class="glyphicon glyphicon glyphicon-calendar"></span> Sustentaciones</a>
			       <a onclick="lodPanel('admin/panelRechz')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Proys/Borr Rechazados </a>
           <!--<a onclick="lodPanel('admin/panelCaduc')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Proys/Borr Archivados </a>-->
             <a onclick="lodPanel('admin/panelBusqa')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-search"></span> <b>Búsquedas</b> </a>
              <a onclick="lodPanel('admin/panelOnBor')" href="javascript:void(0)" class="list-group-item disabled"><span class="glyphicon glyphicon-search"></span> Activar Borrador </a>
          </ul>
        </div>
        <?php } else { ?>
		<!--<div class="list-group">
          <ul class="nav nav-pills bderecha">
          	<a href="<?=base_url("pilar/admin")?>" class="list-group-item"><span class="glyphicon glyphicon-home"></span> Inicio</a>
          </ul>
        </div> -->
        <?php }?>
        <div class="admin-title1 col-wine">Tesista</div>
		<div class="list-group">
          <ul class="nav nav-pills bderecha">
             <?php if( $sess->userLevel < 4 ) { ?>
           
           
            <?php }?>
            <a onclick="lodPanel('admin/panelListaTesista')" class="list-group-item" ><span class="glyphicon glyphicon-sunglasses"></span> Datos Tesista</a> <!--agregado unuv1.0 - Recuperacion de contraseña - tesista -->
            <!--Creado 23/02/2022-->  
            <a onclick="lodPanel('admin/panelListaPosibles')" href="javascript:void(0)" class="list-group-item" ><span class="glyphicon glyphicon-sunglasses"></span> Repositorio Postulantes</a>
            <a onclick="lodPanel('admin/panelLogsT')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Accesos Tesistas </a>

            <a href="#" class="list-group-item disabled" ><span class="glyphicon glyphicon-book"></span> Reporte Tesista</a>
          </ul>
        </div>
        <!-- AREA MENU DOCENTES -->
      
        <div class="admin-title1 col-wine">Docentes </div>
		<div class="list-group">
          <ul class="nav nav-pills bderecha">
              <?php if( $sess->userLevel < 4 ) { ?>
            <a onclick="lodPanel('admin/panelLista')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-sunglasses"></span> Repositorio Docentes </a>
            <!--<a onclick="lodPanel('admin/panelConst')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Constancias </a>
            <a onclick="lodPanel('admin/panelPilar')" href="javascript:void(0)" class="list-group-item disabled"><span class="glyphicon glyphicon-book"></span> Docentes en PILAR </a>-->
             <?php } ?>
            <a onclick="lodPanel('admin/panelLogsD')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Accesos Docentes </a>
            
          </ul>
        </div>
       
        <div class="admin-title1 col-wine">Reportes Admin </div>
		<div class="list-group">
          <ul class="nav nav-pills bderecha">
             <?php if( $sess->userLevel < 4 ) { ?>
            <a onclick="lodPanel('admin/panelGeren')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-tasks"></span> Reportes Administración</a>
             <a onclick="lodPanel('admin/panelRepos')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-align-justify"></span> Reportes P.I. (Filtros) </a>
              <a href="http://vriunap.pe/fedu/report/functTodos" class="list-group-item"><span class="glyphicon glyphicon-align-justify"></span> Reportes FEDU </a>

               <?php } ?>
            <a onclick="lodPanel('admin/panelLinea')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-book"></span> Lineas de Investigación</a>
           
            <a onclick="lodPanel('admin/panelTrafi')" href="javascript:void(0)" class="list-group-item"><span class="glyphicon glyphicon-align-justify"></span> Visitas </a>
           
          </ul>
        </div>
        <!-- END MENU -->
      </div>
	</div>

	<!-- /Menu de Administrador  -->


</body>