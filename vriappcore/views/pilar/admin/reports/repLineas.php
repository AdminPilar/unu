<div class="col-md-12">
    <form id="fsee" class="form-horizontal" onsubmit="">
        <fieldset>
            <!-- Select Basic -->
            <div class="form-group no-print">
                <label class="col-md-2 control-label" for="selectbasic"> Escuela Profesional. </label>
                <div class="col-md-4">
                    <select id="idcar" name="idcar" class="form-control" onchange="sndLoad('admin/panelLinea', new FormData(fsee))" autofocus> <!-- required -->
                        <option value="0">(todos)</option>
                        <?php
                           foreach ($carre->result() as $do) {
                               $sel = $idcar==$do->Id? "selected" : "";
                               echo "<option value ='$do->Id' $sel> $do->Nombre</option>";
                           }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="idlin" id="idlin">
                <hr>
                <blockquote style="font-size: 12px">
                <table class="table table-striped">
                    <tr>
                        <th> Nro </th>
                        <th class="col-md-1"> Opc </th>
                        <th class="col-md-7"> Nombre </th>
                        <th class="col-md-1"> Total </th>
                        <th class="col-md-1"> Proyectos </th>
                        <th class="col-md-1"> Borradores </th>
                        <th class="col-md-1"> Sustentados </th>
                    </tr>
                    <?php
                        $nro = 1;
                        foreach( $lines->result() as $row ){

                            $act = "idlin.value=$row->Id; sndLoad(\"admin/panelLinea\", new FormData(fsee));";
                            $ver = "$row->Id";

                            $table = $this->dbPilar->getTable( "tesTramites", "Tipo>=1 AND IdCarrera=$row->IdCarrera AND IdLinea=$row->Id" );
                            $sumTo = $table->num_rows();

                            $table = $this->dbPilar->getTable( "tesTramites", "Tipo=1 AND IdCarrera=$row->IdCarrera AND IdLinea=$row->Id" );
                            $sumPy = $table->num_rows();

                            $table = $this->dbPilar->getTable( "tesTramites", "Tipo=2 AND IdCarrera=$row->IdCarrera AND IdLinea=$row->Id" );
                            $sumBr = $table->num_rows();

                            $table = $this->dbPilar->getTable( "tesTramites", "Tipo=3 AND IdCarrera=$row->IdCarrera AND IdLinea=$row->Id" );
                            $sumSu = $table->num_rows();

                            echo "<tr>";
                            echo "<td> <b>$nro</b> </td>";
                            echo "<td> <button onclick='$act' type='button' class='btn btn-info btn-xs'> <i class='glyphicon glyphicon-eye-open'></i> </button> | ";
                            echo "     <a target=_blank href='admin/verLinea/$ver' type='button' class='btn btn-warning btn-xs'> <i class='glyphicon glyphicon-list'></i> </a> </td>";
                            echo "<td> $row->Nombre </td> ";
                            echo "<td> <b>$sumTo</b> </td>";
                            echo "<td> $sumPy </td>";
                            echo "<td> $sumBr </td>";
                            echo "<td> $sumSu </td>";
                            echo "<td>  </td>";
                            echo "</tr>";
                            $nro++;
                        }
                    ?>
                </table>
                <hr>
                <table class="table table-striped table-bordered">
                    <tr>
                        <th> Nro </th>
                        <th> Tipo </th>
                        <th class="col-md-12"> Apellidos y Nombres </th>
                        <th> ProyLin </th>
                        <th> BorrLin </th>
                        <th> TotLin </th>
                        <th> NumProy </th>
                        <th> NumBorr </th>
                        <th> Total </th>
                    </tr>
                    <?php
                        $nro = 1;
                        foreach( $profs->result() as $row ){

                            $sumSu = $this->dbPilar->totProys( $row->IdDocente );
                            $sumPy = $this->dbPilar->totProysEx( $row->IdDocente, 1 );
                            $sumBr = $this->dbPilar->totProysEx( $row->IdDocente, 2 );
                            $linPy = $this->dbPilar->totProysEx( $row->IdDocente, 1, $row->IdLinea );
                            $linBr = $this->dbPilar->totProysEx( $row->IdDocente, 2, $row->IdLinea );
                            $totLi = $linPy + $linBr;

                            echo "<tr>";
                            echo "<td> <b>$nro</b> </td>";
                            echo "<td> <b>$row->TipoDoc</b> </td>";
                            echo "<td> $row->DatosPers </td>";
                            echo "<td> $linPy </td>";
                            echo "<td> $linBr </td>";
                            echo "<td> $totLi </td>";
                            echo "<td> $sumPy </td>";
                            echo "<td> $sumBr </td>";
                            echo "<td> $sumSu </td>";
                            echo "</tr>";
                            $nro++;
                        }
                    ?>
                </table>
                </blockquote>
            </div>
        </fieldset>
    </form>
</div>