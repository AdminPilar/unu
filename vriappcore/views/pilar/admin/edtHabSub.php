<div class="col-md-12 workspace">
    <div class="col-md-10">
        <span class="label label-default mycaption"> Historial de Habilitación y Jurados que no respondieron </span>

        <?PHP if( $habs->num_rows() >= 1 ): ?>
            <table class="table table-striped table-bordered" style="font-size: 11px">
                <tr>
                    <th> Nro </th>
                    <th class="col-md-4"> Docente </th>
                    <th class="col-md-2"> Jurado </th>
                    <th class="col-md-2"> Fecha </th>
                    <th class="col-md-4"> Motivo </th>
                </tr>
        <?PHP
            // desplegar los habilitados
            $nro = 1;
            foreach( $habs->result() as $row ){

                $arr = array( "PRESIDENTE", "PRIMER MIEMBRO", "SEGUNDO MIEMBRO" );

                $jur = $arr[ $row->PosJurado-1 ];
                $doc = $this->dbRepo->inDocente($row->IdDocente);
                $fec = "Sorteado: " .mlShortDate($row->FechSort)."<br>Activado: ". mlShortDate($row->Fecha);

                echo "<tr>";
                echo "<td> $nro </td>";
                echo "<td> ($row->IdDocente) - $doc </td>";
                echo "<td> ($row->PosJurado) - $jur </td>";
                echo "<td> $fec </td>";
                echo "<td> $row->Motivo </td>";
                echo "</tr>";

                $nro++;
            }
        ?>
            </table>

        <?PHP else: ?>

            <div class="col-md-12">
                <!-- form -->
                <form class="form-horizontal" id="frmjur" method="POST" onsubmit='sndLoad("admin/inSaveHabil", new FormData(this),true)'>
                <fieldset>
                    <!-- select areas -->
                    <div class="form-group form-group-sm">
                        <label class="col-md-2 control-label"> <small>Linea de Investigación</small> </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="<?=$tram->IdLinea?> - <?=$this->dbRepo->inLineaInv($tram->IdLinea) ?>" readonly>
                            <input name="idtram" type="hidden" class="form-control" value="<?=$tram->Id?>">
                            <input name="codigo" type="hidden" class="form-control" value="<?=$tram->Codigo?>">
                            <input name="fechso" type="hidden" class="form-control" value="<?=$tram->FechModif?>">
                        </div>
                    </div>
                    <!-- select areas -->
                    <div class="form-group form-group-sm">
                        <label class="col-md-2 control-label"> Presidente </label>
                        <div class="col-md-2">
                            <?PHP
                                $numc1 = $this->dbPilar->inCorrecs( $tram->Id, 1 )->num_rows();
                            ?>
                            <input name="jurad1" type="hidden" class="form-control" value="<?=$tram->IdJurado1?>">
                            <select name="estad1" class="form-control" required>
                                <option value="0" <?=$dets->vb1? "" : "selected"?>> Sin correcciones </option>
                                <option value="1" <?=$dets->vb1? "selected" : ""?>> Completado </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <input type="text" class="form-control" value="<?=$numc1?>" readonly>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" value="<?=$this->dbRepo->inDocenteEx($tram->IdJurado1)?>" readonly>
                        </div>
                        <!-- <span class="help-block col-md-7">El Asesor de Proyecto deberá ser un docente Nombrado</span> -->
                    </div>
                    <!-- select areas -->
                    <div class="form-group form-group-sm">
                        <label class="col-md-2 control-label"> Primer Miembro </label>
                        <div class="col-md-2">
                            <?PHP
                                $numc2 = $this->dbPilar->inCorrecs( $tram->Id, 2 )->num_rows();
                            ?>
                            <input name="jurad2" type="hidden" class="form-control" value="<?=$tram->IdJurado2?>">
                            <select name="estad2" class="form-control" required>
                                <option value="0" <?=$dets->vb2? "" : "selected"?>> Sin correcciones </option>
                                <option value="1" <?=$dets->vb2? "selected" : ""?>> Completado </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <input type="text" class="form-control" value="<?=$numc2?>" readonly>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" value="<?=$this->dbRepo->inDocenteEx($tram->IdJurado2)?>" readonly>
                        </div>
                    </div>
                    <!-- select areas -->
                    <div class="form-group form-group-sm">
                        <label class="col-md-2 control-label"> Segundo Miembro </label>
                        <div class="col-md-2">
                            <?PHP
                                $numc3 = $this->dbPilar->inCorrecs( $tram->Id, 3 )->num_rows();
                            ?>
                            <input name="jurad3" type="hidden" class="form-control" value="<?=$tram->IdJurado3?>">
                            <select name="estad3" class="form-control" required>
                                <option value="0" <?=$dets->vb3? "" : "selected"?>> Sin correcciones </option>
                                <option value="1" <?=$dets->vb3? "selected" : ""?>> Completado </option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <input type="text" class="form-control" value="<?=$numc3?>" readonly>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" value="<?=$this->dbRepo->inDocenteEx($tram->IdJurado3)?>" readonly>
                        </div>
                    </div>
                    <!-- select areas -->
                    <div class="form-group form-group-sm">
                        <?PHP
                            $omis = "";
                            $omis .= (!$dets->vb1)? "\n<br>El Presidente no ha realizado correcciones" : "";
                            $omis .= (!$dets->vb2)? "\n<br>El Primer miembro no ha realizado correcciones" : "";
                            $omis .= (!$dets->vb3)? "\n<br>El Segundo miembro no ha realizado correcciones" : "";

                            $diasx = mlDiasTranscHoy($tram->FechModif);
                            $motif = "Habilitación por exceso de tiempo en revisión, ($diasx) dias transcurridos. $omis";
                        ?>
                        <label class="col-md-2 control-label"> Motivo de Cambio </label>
                        <div class="col-md-10">
                            <textarea name="motivo" rows=4 class="form-control" required><?=$motif?></textarea>
                        </div>
                    </div>
                    <!-- end -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" onclick='sndLoad("admin/listBusqTesi", new FormData(frmbusq),true)'> Cancelar </button>
                        <button type="submit" class="btn btn-success"> Habilitar Subida de Proy </button>
                    </div>
                </fieldset>
                </form>
            </div>

        <?PHP endif; ?>

    </div>
    <!--
    <div class="col-md-2">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dlgJur">
            Habilitar Subida
        </button>
    </div>
    -->
</div>
