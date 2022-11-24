
<?php

    $onsubm = "sndLoad('admin/innerTrams/$tipo', new FormData(fsee) )";
?>

<div class="col-md-12">
    <form id="fsee" class="form-horizontal" onsubmit="<?=$onsubm?>; return false">
        <fieldset>
            <!-- Select Basic -->
            <div class="form-group no-print">
                <input type="hidden" name="tipo" value="<?=$tipo?>"> <!-- Kind of view -->
                <label class="col-md-1 control-label" for="selectbasic"> ESTADO </label>
                <div class="col-md-2">
                    <select id="estado" name="estado" class="form-control" onchange="<?=$onsubm?>" autofocus> <!-- required -->
                        <option value="0">(todos)</option>
                        <?php
                        foreach ($tEstadotip->result() as  $value) {
                            $issel = ($value->Id==$estado)? "selected" : "";
                             echo "<option value='$value->Id' $issel>$value->Nombre </option>";
                        }
                        ?>
                    </select>
                </div>
                <label class="col-md-1 control-label" for="selectbasic"> CARRERA </label>
                <div class="col-md-3">
                    <select id="carrer" name="carrer" class="form-control" onchange="<?=$onsubm?>"> <!-- this.form.submit() -->
                        <option value="0">( todos )</option>
                        <?php
                        foreach( $tcarrs->result() as $row)
                        {
                            $issel = ($row->Id==$carrer)? "selected" : "";
                            echo "<option value=$row->Id $issel> $row->Nombre </option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input id="codigo" name="codigo" value="<?=$codigo?>" type="text" class="form-control input-md" placeholder="Codigo Proyecto">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success btn-block"> <span class="glyphicon glyphicon-search"></span> Buscar </button>
                </div>
            </div>
        </fieldset>
    </form>
</div>

<!-- ============================================================================ -->
<table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Nro</th>
        <th class="col-md-1"> Codigo </th>
        <th class="col-md-2"> Tesista </th>
        <th class="col-md-5"> Titulo </th>
        <th class="col-md-2"> Fecha Ult. Mod. </th>
        <th class="col-md-2"> Opciones </th>
      </tr>
    </thead>
    <tbody>
    <?php

    $nro = $tproys->num_rows();

     $procesos = array (
        0 => "",
        1 => "Revisión de Formato",
        2 => "Para Asesor",
        3 => "Para Sorteo",
        4 => "Para Revisión (1)",
        5 => "Para Revisión (2)",     // 05
        6 => "Para Revisión (3)", 
        7 => "Dictaminacion",
        8 => "Proy. Aprobado",      // 06
        9 => "Presentacion Grado de Bach.",      // 10
        10 => "Revisión de Formato de Borrador",     // 11
        11 => "Revision Borrador (1)",
        12 => "Revision Borrador (2)",
        13 => "Revision Borrador (3)",
        14 => "Gen Memos",
        15 => "Susten"
    );

    $proceclr = array(
        0 => "",
        1 => "btn-success",
        2 => "btn-primary",
        3 => "btn-danger",
        4 => "btn-success",
        5 => "btn-warning",
        6 => "btn-default",
        7 => "btn-default",
        8 => "btn-success",
        9 => "btn-default",
        10 => "btn-danger",
        11 => "btn-warning",
        12 => "btn-success",
        13 => "btn-info",
        14 => "btn-default",
        15 => "btn-default",
        16 => "btn-default"
    );

    //-------------------------------------------------------------------
    // filtrado de acciones dependiento del tipo de tramite
    //-------------------------------------------------------------------
    foreach( $tproys->result() as $row ) {

        echo "<tr id='nr$nro'>";
        $rowgrado=$this->dbPilar->getOneField("testramsbach","File","IdTramite=$row->Id and IdTesista=$row->IdTesista1");    
        $Rowdicestatramite = $this->dbPilar->getSnapRow( "dicestadtram", "Id=$row->Estado");    
        $det    = $this->dbPilar->inLastTramDet( $row->Id );
		if( ! $det ){ echo "Error detail ($row->Id)"; continue; }

        $fecha  = mlFechaNorm( $row->FechModif );
        $diasp  = mlDiasTranscHoy( $row->FechModif );
        $autors = $this->dbPilar->inTesistas( $row->Id );
        $carrer = $this->dbRepo->inCarrera( $row->IdCarrera );

        $estado = "";
		$archi = base_url("/repositor/docs/$det->Archivo");
		$actap = base_url("pilar/tesistas/actaProy/$row->Id");
        $grado = base_url("/repositor/bach/$rowgrado");
		$menus = "<a href='$archi' class='btn btn-xs btn-info no-print' target=_blank> ver PDF </a>";

        // Tipo 1 = Proyecto de tesis
        if( $row->Tipo == 1 ) {

            $btnclr = $proceclr[ ($row->Estado>15 or $row->Estado<0)? 0:$row->Estado ];
            $estado = $procesos[ ($row->Estado>15 or $row->Estado<0)? 0:$row->Estado ];
            $estado = "<button class='btn btn-xs $Rowdicestatramite->TipoBoton'> $estado </button>";
            $estado = $estado . " <br><small> (E: $row->Estado) </small> ";

			// rechazar proyecto y grabar historia
			if( $row->Estado == 1 AND $diasp>=0 )
				///$menus .= "<br> <button onclick='pyRetorna($nro,$row->Id)' class='btn btn-xs btn-danger'> Devolver </button> "
                $menus .= "<br> <button onclick='popLoad(\"admin/execRechaza/$row->Id\",$nro)' class='btn btn-xs btn-danger'> Rechazar </button> "
				        . "<button onclick='pyDirect($nro,$row->Id)' class='btn btn-xs btn-warning'> Al Asesor </button>" ;

			if( $row->Estado == 2 )
				$menus .= " | <button onclick='popLoad(\"admin/execNoDirec/$row->Id\",$nro)' class='btn btn-xs btn-danger'> Rechazar </button>";

			if( $row->Estado == 3 AND $diasp>=0 ){
				$menus .= " | <button onclick='popLoad(\"admin/execSorteo/$row->Id\",$nro)' class='btn btn-xs btn-warning'> Sorteo </button>";
			}

            // revisiones
			if( $row->Estado == 4 ) {

                $menus .= " | <button onclick='popLoad(\"admin/execCorrec/$row->Id\",$nro)' class='btn btn-xs btn-primary'> Correcs </button>";
                $menus .=  "<button onclick='popLoad(\"admin/execCancelPy/$row->Id\",$nro)' class='btn btn-xs btn-danger'> Archivar </button>" ;
                if( $diasp > 60 ) {

                    $menus .= " | <button onclick='popLoad(\"admin/execRech4/$row->Id\",$nro)' class='btn btn-xs btn-danger'> Cancelar </button>";
                    $menus .= "<br> <p style='color:red'> <b>Exceso de tiempo</b> <br> [ $det->vb1 / $det->vb2 / $det->vb3 ] </p>";
                }
                else {
				    $menus .= "<br>[ $det->vb1 / $det->vb2 / $det->vb3 ]";
                }
            }

			// dictaminaciones
			if( $row->Estado == 5  ) {

                $cance = ($det->vb1 + $det->vb2 + $det->vb3)<0? "<button onclick='popLoad(\"admin/execCancelPy/$row->Id\",$nro)' class='btn btn-xs btn-danger'> Cancelar </button>" : "";

                $menus .= " | <button onclick='popLoad(\"admin/execAprobPy/$row->Id\",$nro)' class='btn btn-xs btn-warning'> Aprobar </button> $cance";
                 $menus .= " | <button onclick='popLoad(\"admin/execCorrec/$row->Id\",$nro)' class='btn btn-xs btn-primary'> Correcs </button>";
				$menus .= "<br>[ $det->vb1 / $det->vb2 / $det->vb3 ]";

            }

			// ver Actas
			if( $row->Estado == 8 ) {
				$menus .= " | <a href='$actap' class='btn btn-xs btn-primary no-print' target=_blank> ACTA </a>";
            }


            $cont = 0;
            if( $row->IdJurado1 == $row->IdJurado2 ) $cont++;
            if( $row->IdJurado1 == $row->IdJurado3 ) $cont++;
            if( $row->IdJurado1 == $row->IdJurado4 ) $cont++;

            if( $row->IdJurado2 == $row->IdJurado1 ) $cont++;
            if( $row->IdJurado2 == $row->IdJurado3 ) $cont++;
            if( $row->IdJurado2 == $row->IdJurado4 ) $cont++;

            if( $row->IdJurado3 == $row->IdJurado1 ) $cont++;
            if( $row->IdJurado3 == $row->IdJurado2 ) $cont++;
            if( $row->IdJurado3 == $row->IdJurado4 ) $cont++;

            // alerta jurados repetidos
            if( $cont >= 1 && $row->Estado >= 4 ) {
                $menus .= "<br> <p style='color:red'> <b>Alerta: Jurado Repite</b> </p>";
            }
        }

        // Estado >= 10 && <= 14 : Borradores
        if( $row->Tipo == 2 ) {

            $btnclr = $proceclr[ $row->Estado ];
            $estado = "<button class='btn btn-xs $btnclr'> Borr (E: $row->Estado) </button>";

             if( $row->Estado==9 ) {
            $menus .= " | <a href='$actap' class='btn btn-xs btn-primary no-print' target=_blank> ACTA </a>";
            $menus .=" | <a href='$grado' target=_blank class='btn btn-success btn-xs' title='Grado de Bachiller - Tesista 1'><span class='glyphicon glyphicon-list-alt'></span></a>";
            if($row->IdTesista2!=0)
            {
                $rowgrado=$this->dbPilar->getOneField("testramsbach","File","IdTramite=$row->Id and IdTesista=$row->IdTesista2");
                $grado2 =base_url("/repositor/bach/$rowgrado");
                $menus.=" | <a href='$grado2' target=_blank class='btn btn-success btn-xs' title='Grado de Bachiller -Tesista 2'><span class='glyphicon glyphicon-list-alt'></span> </a>";
            }   
            }

			if( $row->Estado==10 ) {
				$fecha = mlFechaNorm( $row->FechActBorr );
				$menus = ($row->Estado==10)? "<i>(trámite pendiente)</i>":"";
				$diasp = mlDiasTranscHoy( $row->FechActBorr );
			}

			if( $row->Estado==11 ) {
				$menus .= " | <button onclick='borDirect($nro,$row->Id)' class='btn btn-xs btn-warning'>Envia a Revisión</button>";
			}
        }

        // Con programacion de sustent y pasados
        if( $row->Tipo == 3 ) {

            // fecha de susten.
            $fechSu = $this->dbPilar->inFechSustent( $row->Id );

            $estado = ($row->Estado==13)? "Programado" : "Concluido";
            $btnclr = $proceclr[ $row->Estado ];
            $estado = "<button class='btn btn-xs $btnclr'> $estado </button>";

            $fecha =  "<small><b>Sustentación: ".mlFechaNorm($fechSu)."</b></small>";
        }

        echo "<td>$nro</td>";
        echo "<td> <b>$row->Codigo</b> <br> $estado </td>";
        echo "<td> <span style='color:blue;font-size:9px'>$carrer<br></span> <small>$autors</small> </td>";
        echo "<td> <small> $det->Titulo </small> </td>";
        echo "<td> $fecha <br> <b>$diasp dia(s)</b> </td>";
        ///if( $row->Id == 5142 ) $menus = " <small>Error xD o.O  </small>";
        echo "<td> $menus </td>";

        echo "</tr>";
        $nro--;
    }

    ?>
    </tbody>
</table>


<!-- ============================================================================ -->
<!-- End of Rendering area -->
<!-- ============================================================================ -->

<!-- MODAL  -->
<div id="dlgPan" class="modal" role="dialog">
<div class="modal-dialog modal-md">
  <br><br><br><br><br>
  <div class="modal-content">
	<div class="modal-header" style="background: #920738; color:white">
	  <button class="close" data-dismiss="modal" style="color:white">&times;</button>
	  <h4 class="modal-title"> MiniVentana Admin </h4>
	</div>
  <form name="fX" id="fX" method="post">
	<div class="modal-body" id="vwCorrs" style="font-size:13px">
		<!-- <div class="row"></div> -->
	</div>
  </form>
	<div class="modal-footer">
		<button class="btn btn-success" id="popOk" onclick="popProcede('admin/popExec',new FormData(fX))"> Procesar en Tiempo Record, OK ! </button>
		<button class="btn btn-danger" data-dismiss="modal"> Cerrad la Ventana, tio ! </button>
	</div>
  </div>
</div>
</div>
<!-- /MODAL  -->

