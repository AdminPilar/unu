  
  <table class="table table-hover" width="100%" style="display: block; overflow-x: auto;">
    <thead>
      <tr>
        <th> Nro </th>
        <th class="col-md-1"> Participacion</th>
        <th class="col-md-1"> Codigo </th>
        <th class="col-md-6"> Titulo </th>
        <th class="col-md-1"> Fecha </th>
        <th class="col-md-1"> Tiempo </th>
        <th class="col-md-2"> Opciones </th>
        <th class="col-md-2"> Archivo</th>
      </tr>
    </thead>
    <tbody>
<?php

    $nro = $tproys->num_rows();

    $procesos = array (
        "Proyecto nuevo",
        "Asesoria",
        "Para Sorteo",
        "En Revisión (1)",//agregado unuv1.0
        "En Revisión (2)", //agregado unuv1.0
        "En Revisión (3)",//agregado unuv1.0
        "En Dictamen",
        "Proy. Aprobado", 
        "Presentacion Grado de Bach.",      // 10
        "Revisión de Formato de Borrador",     // 11
        "Revision Borrador (1)",
        "Revision Borrador (2)",
        "Revision Borrador (3)",
        "Dictamen",
        "Revision Presencial",
        "Borrador de Tesis Final",
        "Otros"
    );

    $proceclr = array(
        "btn-default",//1
        "btn-primary",
        "btn-success",
        "btn-success",
        "btn-success",
        "btn-success",
        "btn-warning",
        "btn-success", //8
        "btn-success",
        "btn-success",
        "btn-success",
        "btn-warning",
        "btn-success",
        "btn-success",
        "btn-success",
        "btn-success"

    );

    $posjurado = array(
        "Presidente",
        "Primer miembro",
        "Segundo miembro",
        "Asesor"
    );



    foreach( $tproys->result() as $row ) {

        echo "<tr>";

		$aut = "";
        $pos = $this->dbPilar->inPosJurado( $row, $sess->userId ); // tesTramite row
        $det = $this->dbPilar->inLastTramDet( $row->Id );

        $fecha = mlFechaNorm( $row->FechModif );


        // popUp con Id Tipo
        $archi = base_url("/repositor/docs/$det->Archivo"); //Agregado unuv1.0
        $archivo= "<a href='$archi' target='_blank' class='btn btn-xs btn-info'> Archivo</a>";
        //$archivo= "<a href='http://vriunap.pe/repositor/docs/$det->Archivo' target='_blank' class='btn btn-xs btn-info'> Archivo</a>";
        $menus = "";
        $estado = "";
        if($row->IdTesista2!=0){
            $aut="<p style='font-size:9.5px;font-weight:bold; margin-bottom: 0px'>"
                 . "Este proyecto tiene 2 Tesistas</p>";
        }
		//-----------------------------------------------------------------------------------------------------
        if( $row->Estado >= 1 && $row->Estado <= 8 ) {
            $btnclr = $proceclr[ $row->Estado-1 ];
            $estado = $procesos[ $row->Estado-1 ];
            $estado = "<button class='btn btn-xs $btnclr'> $estado </button>";
        }

		//-----------------------------------------------------------------------------------------------------
		if( $row->Tipo == 1 ) {

			// Asesor
			if( $row->Estado == 2 && $pos==4 ) { //Modificacion unuv1.0 - Estado aprobacion y/o rechazo proyecto
				// OJO: controlar Jurado no dejar al miembro elegido
				$menus = "<button onclick=\"loadCorrs('docentes/corrProys',$row->Id)\" class='btn btn-sm btn-success'> Aprobación </button>";
			}

			// revision de proyectos
            //unuv1.0 - Estado revision 1 
			if( $row->Estado == 4 ) {
				$menus = "<button onclick=\"loadCorrs('docentes/corrProys',$row->Id)\" class='btn btn-sm btn-info'> Revisar PDF </button>"; 
                if($pos==4){
                    $menus = "";
                }
			}
            //agregado unuv1.0 - estado revision 2
            if( $row->Estado == 5 ) {
                $menus = "<button onclick=\"loadCorrs('docentes/corrProys',$row->Id)\" class='btn btn-sm btn-warning'> Revisar PDF </button>";
                if($pos==4){
                    $menus = "";
                }
            }

            if( $row->Estado == 6 ) {
                $menus = "<button onclick=\"loadCorrs('docentes/corrProys',$row->Id)\" class='btn btn-sm btn-warning'> Revisar PDF </button>";
                if($pos==4){
                    $menus = "";
                }
            }

			// dictaminación de proyecto
			if( $row->Estado == 7 ) {

				$calif = 0;
				if( $pos == 1 ) $calif = $det->vb1;
				if( $pos == 2 ) $calif = $det->vb2;
				if( $pos == 3 ) $calif = $det->vb3;
				if( $pos == 4 ) $calif = $det->vb4;

				if( $calif < 0 ) $tipo = "Proyecto Desaprobado";
				elseif( $calif > 0 ) $tipo = "Proyecto Aprobado";

				if( $calif == 0 )
                {
                    if($pos!=4)
                        {
                            $menus = "<button onclick=\"loadCorrs('docentes/corrProys',$row->Id)\" class='btn btn-sm btn-warning'> Dictaminar </button>";
                        }
                }
				else
					$menus = "<button class='btn btn-sm btn-default'> $tipo </button>";
			}

			// visualizacion de Acta
			if( $row->Estado >= 8 ) {
				$menus .= "<a href='".base_url("pilar/tesistas/actaProy/$row->Id")."' target=_blank class='btn btn-success btn-xs'>Acta de Aprobación</a>";
			}
		}


		// mostrar los autores
		if( $row->Estado >= 9 )
			$aut = "<p style='font-size:9.5px;font-weight:bold; margin-bottom: 0px'>"
				 . "TESISTA(S): ".$this->dbPilar->inTesistas($row->Id)."</p>";

		//-----------------------------------------------------------------------------------------------------
		if( $row->Tipo == 2 ) {

			//$estado = $posjurado[ $pos - 1 ];
            $btnclr = $proceclr[ $row->Estado-1 ];
            $estado = $procesos[ $row->Estado-1 ];
            $estado = "<button class='btn btn-xs $btnclr'> $estado </button>";

			if( $row->Estado == 9 ) {
				$menus = ($row->Estado==9)? "<i>(trámite en proceso)</i>":"";
                $archivo="";
				//$estado = "<button class='btn btn-xs btn-default'> $estado </button>"; // tipo jurado
				//$btnclr = $proceclr[ $row->Estado-10 ];
				//$estado .= "<button class='btn btn-xs $btnclr'>  </button>";
			}

			if( $row->Estado == 10 ) {
				//$estado = "<button class='btn btn-xs btn-default'> $estado </button>"; // tipo jurado
				$menus = "<small><b><button class='btn btn-xs btn-default'> Revisión de Formato de Borrador </button></b></small>";
                $archivo='';
			}

			/*if( $row->Estado == 11 ) {
                
                    $menus = "<button onclick=\"loadCorrs('docentes/corrBorras',$row->Id)\" class='btn btn-sm btn-info'> Corregir Borrador </button>";
                
				
				//$estado = "<button class='btn btn-xs btn-success'> $estado </button>"; // tipo jurado
			}*/
            if( $row->Estado == 11  || $row->Estado == 12 || $row->Estado == 13) {
                if($pos==4){
                    $menus = "";
                }else{
                $menus = "<button onclick=\"loadCorrs('docentes/corrBorras',$row->Id)\" class='btn btn-sm btn-info'> Corregir Borrador </button>";
                }
                //$estado = "<button class='btn btn-xs btn-success'> $estado </button>"; // tipo jurado
            }

			if( $row->Estado == 14 ) {
				//$estado = "<button class='btn btn-xs btn-warning'> $estado </button>"; // tipo jurado
				$menus = "<small><b><button class='btn btn-xs btn-default'> Dictamen </button></b></small>";
			}
            if( $row->Estado == 15 ) {
                //$estado = "<button class='btn btn-xs btn-warning'> $estado </button>"; // tipo jurado
                $menus = "<small><b>Revisión Presencial</b></small>";
            }


		}

		//-----------------------------------------------------------------------------------------------------
        // si ha sustentado poner fecha de sustentacion.
		//-----------------------------------------------------------------------------------------------------
        if( $row->Tipo == 3 ) {
            $fecha = $this->dbPilar->getOneField( 'tesSustens', 'Fecha', "IdTramite=$row->Id" );
            $fecha = "Sustentación<br>" .  mlFechaNorm($fecha);
			$menus = "<button onclick=\"loadCorrs('docentes/constJurado',$row->Id)\" class='btn btn-sm btn-info'> Ver Constancia </button>";
			$virtu = $this->dbPilar->getOneField('tesSustensSolic','Estado',"IdTramite=$row->Id");
			
			if ($virtu==2) {
				$calif = 0;
				if( $pos == 1 ) $calif = $det->vb1;
				if( $pos == 2 ) $calif = $det->vb2;
				if( $pos == 3 ) $calif = $det->vb3;
				if( $pos == 4 ) $calif = $det->vb4;

				if( $calif == 0 ) $tipo = "Desaprobado";
				elseif( $calif == 1 ) $tipo = "Aprobado";
				elseif( $calif == 2 )  $tipo = "Aprobado con Distinción";

				if( $calif == -1 )
						$menus = "<button onclick=\"loadCorrs('docentes/corrProys',$row->Id)\" class='btn btn-sm btn-danger'> Dictamen Sust </button>";
				else
					$menus = "<button class='btn btn-sm btn-default'> $tipo </button>";
				$estado = "<button class='btn btn-xs btn-warning'> Virtual </button>";
			}
			if($virtu==3){
				$menus = "<a target=_blank href='../pilar/tesistas/actaDeliberacion/$row->Id' class='btn btn-sm btn-default'><span class='glyphicon glyphicon-list-alt'></span>Acta</a>";
				$estado = "<button class='btn btn-xs btn-warning'> Virtual </button>";
			}

        }

        $dias='';
        if( $row->Estado==7 ){
            $diasRes=15-mlDiasTranscHoy($row->FechModif);
            $dias = ($diasRes<0)?"<p class='text-danger'>Fuera de Plazo de Dictamen</p>":"<p class='text-success'> $diasRes Días Restantes</p>";
        }

        if( $row->Estado==6 ){
            $dias = "";
        }
        if( $row->Estado==2 ){
            // 
            $diasRes=3-mlDiasTranscHoy($row->FechModif);
            $dias = ($diasRes<0)?"<p class='text-danger'>Fuera de Plazo de Asesoria</p>":"<p class='text-success'> $diasRes Días Restantes</p>";
        }

        if($row->Estado==11 OR $row->Estado==4 or $row->Estado==5 or $row->Estado==6){

            $c1 = $this->dbPilar->inNCorrecs($row->Id,$sess->userId,1);
            $posis = $this->dbPilar->inPosJurado( $row, $sess->userId ); //Agregado unuv1.0
            $c4 = $this->dbPilar->inNCorrecs($row->Id,$sess->userId,4);
            $corre=$this->dbPilar->getOneField("tesTramsDet","vb$posis","IdTramite=$row->Id ORDER BY Iteracion DESC"); //agregado revision 1

            $diasRes = 15 - mlDiasTranscHoy($row->FechModif);
            //$dias = ($diasRes<0)?"<p class='text-danger'> Fuera de Plazo</p>":"<p class='text-success'> $diasRes Días Restantes</p>";
            $dias = ($diasRes<0)? "":"<p class='text-success'> $diasRes Días Restantes</p>";

            if( $row->Estado==4  && $corre>0 ) $dias = "<span class='text-success'> Observaciones realizadas </span>";            
            if( $row->Estado==5  && $corre>0 ) $dias = "<span class='text-success'> Observaciones realizadas </span>"; //agregado unuv1.0
            if( $row->Estado==6 && $corre>0 ) $dias = "<span class='text-success'> Observaciones realizadas </span>";
            if( $row->Estado==11 && $corre>0 ) $dias = "<span class='text-success'> Observaciones realizadas </span>";
             if( $row->Estado==12 && $corre>0 ) $dias = "<span class='text-success'> Observaciones realizadas </span>";
              if( $row->Estado==13 && $corre>0 ) $dias = "<span class='text-success'> Observaciones realizadas </span>";

            $dias .= (($det->vb1 + $det->vb2 + $det->vb3)==3)? "<small>" : "";
        }
        // Para tener mejor vizualización y Control del Docente
        $escuela = $this->dbRepo->inCarrera("$row->IdCarrera");

        echo "<td> $nro </td>";
        echo "<td style='font-size:12px;'>".$posjurado[$pos-1]." </td>";
        echo "<td> <b>$row->Codigo</b> <br> $estado </td>";
        echo "<td> $aut <small>$det->Titulo <br><b><i style='font-size:10px;'> $escuela </i><b></small> </td>";
        echo "<td> $fecha </td>";
        echo "<td> <b>$dias</b></td>";
        echo "<td> $menus </td>";
        echo "<td> $archivo </td>";

        echo "</tr>";
        $nro--;
    }

?>
    </tbody>
  </table>

<br><bR>

  <!-- MODAL  -->
   <div id="dlgCorrs" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 99%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"> Revisión Electrónica </h4>
        </div>
        <div class="modal-body" style="padding: 0px 0px 0px 3px">
			<div class="row" id="vwCorrs">
				<!--
				<div class="col-md-9">
					<iframe id="frmpdf" name="frmpdf" src="" frameborder="0" width="100%"></iframe>
				</div>
				<div class="col-md-3">
					<button type="button" class="btn btn-success"> Aceptar </button>
					<button type="button" class="btn btn-danger"> Rechazar </button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
				</div>
				-->
			</div>
        </div>
		<!--
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
        </div> -->
      </div>
    </div>
  </div>
  <!-- /MODAL  -->

