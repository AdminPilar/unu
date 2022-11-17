<br>
<div class="col-md-12 panel-trabajo">

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

                $sess = $this->gensession->GetData( PILAR_CORDIS );

                $totproy = $this->dbPilar->getTable("tesTramites","IdCarrera=$IdCarrera")             ->num_rows();
                $pyaprob = $this->dbPilar->getTable("tesTramites","IdCarrera=$IdCarrera AND Estado>=6") ->num_rows();
                $curproy = $this->dbPilar->getTable("tesTramites","IdCarrera=$IdCarrera AND Tipo=1")    ->num_rows();
                $curborr = $this->dbPilar->getTable("tesTramites","IdCarrera=$IdCarrera AND Tipo=2")    ->num_rows();
                $totborr = $this->dbPilar->getTable("tesTramites","IdCarrera=$IdCarrera AND Estado>=13")->num_rows();
                $totsust = $this->dbPilar->getTable("tesTramites","IdCarrera=$IdCarrera AND Tipo=3")    ->num_rows();

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
