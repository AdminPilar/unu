<div class="row">
    <?php
        $evSubm = "sndLoad('admin/panelRepos', new FormData(fsee) ); return false";
    ?>
    <div class="col-md-11 workspace">
        <form id="fsee" class="form-horizontal" onsubmit="<?=$evSubm?>">
            <fieldset>
                <!-- Select Basic -->
                <div class="form-group no-print">

                    <label class="col-md-2 control-label" for="selectbasic"> Carrera Profesional </label>
                    <div class="col-md-3">
                        <select id="carre" name="carre" class="form-control" onchange="progs.value=0;espec.value=0;<?=$evSubm?>"> <!-- this.form.submit() -->
                            <option value="0">( seleccione )</option>
                            <?php
                            foreach( $tcarr->result() as $row ) {
                                $sel = ($row->Id==$carre)? "selected" : "";
                                echo "<option value=$row->Id $sel> $row->Nombre </option>";
                            }
                            ?>
                        </select>
                    </div>

                    <label class="col-md-2 control-label" for="selectbasic"> Especialidad </label>
                    <div class="col-md-3">
                        <select id="espec" name="espec" class="form-control" onchange="<?=$evSubm?>"> <!-- required -->
                            <option value="0">(Carrera Pura)</option>
                            <?php
                            foreach( $tespe->result() as $row ) {
                                $sel = ($row->Id==$espec)? "selected" : "";
                                echo "<option value=$row->Id $sel> $row->Nombre </option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success btn-block"> <span class="glyphicon glyphicon-search"></span> Buscar </button>
                    </div>
                </div>
                <!-- ------------------------ -->
                <div class="form-group no-print">

                    <label class="col-md-2 control-label" for="selectbasic"> Programa Profesional </label>
                    <div class="col-md-3">
                        <select id="progs" name="progs" class="form-control" onchange="carre.value=0; <?=$evSubm?>"> <!-- this.form.submit() -->
                            <option value="0">( seleccione )</option>
                            <?php
                            foreach( $tprog->result() as $row ) {
                                $sel = ($row->Id==$progs)? "selected" : "";
                                echo "<option value=$row->Id $sel> $row->Cod - $row->Nombre </option>";
                            }
                            ?>
                        </select>
                    </div>

                    <label class="col-md-2 control-label" for="selectbasic"> Datos Personales </label>
                    <div class="col-md-3">
                        <input id="datos" name="datos" type="text" class="form-control input-md" placeholder="DNI o Apellidos">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block"> <span class="glyphicon glyphicon-search"></span> Buscar </button>
                    </div>
                </div>
            </fieldset>
        </form>

        <div class="row">
            <div class="col-md-12">

<!--                <h4> Listado de Proyectos de Tesis Aprobados </h4>//-->

                <?php


/*
                    echo "carr: $carre : $espec <br>";
                    echo "prog: $progs : str: $datos ";
*/

                    $nro = 1;
                    echo "<table class='table table-striped table-bordered' style='font-size: 12px'>";

                    echo "<tr>";
                    echo "<th> Nro </th>";
                    echo "<th class='col-md-1'> Codigo </th>";
                    echo "<th> (E) </th>";
                    echo "<th> Integrantes </th>";
                    echo "<th> Titulo del Proyecto </th>";
                    echo "<th class='col-md-2'> Fecha de Aprobacion </th>";
                    echo "</tr>";

                    if( $tproy )
                    foreach( $tproy->result() as $row ) {

                        $tesistas = $this->dbPilar->inTesistas( $row->Id );
                        $trams = $this->dbPilar->inTramDetIter( $row->Id, 3 ); // Acta +
                        $fecha = mlFechaNorm( $row->FechModif );

                        if( $row->Estado >= 6 && $row->Estado <= 10 )
                            $estad = "En ejecución";
                        elseif( $row->Estado >= 11 && $row->Estado <= 13 )
                            $estad = "Revisión Borrador";
                        elseif( $row->Estado >= 14 )
                            $estad = "Sustentado";
                        else
                            $estad = "";

                        echo "<tr>";
                        echo "<td> $nro </td>";
                        echo "<td> <b>$row->Codigo</b> </td>";
                        echo "<td> $estad </td>";
                        echo "<td> $tesistas </td>";
                        echo "<td> $trams->Titulo </td>";
                        echo "<td> $fecha </td>";
                        echo "</tr>";

                        $nro++;
                    }
                    echo "</table>";
                ?>
            </div>
        </div>
    </div>
</div>
