<?php
    $IdCarrera=mlGetGlobalVar("IdCarrera");
    $Carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$IdCarrera");
    if (!$IdCarrera) {
        $Carrera="No se ha seleccionado ninguna escuela profesional.";
    }
?>
<h3>Solicitud de Exposición y Defensa NO PRESENCIAL:: <small><?php  echo $Carrera; ?></small></h3>
<p><b>NOTA : </b> Coordinar con los jurados de tesis antes de la publicación, para confirmar la hora y fecha de solicitud, puede realizarlo haciendo click en el código del trámite para obtener sus teléfonos.</p>
<div class="col-md-2" ></div>
<div class="col-md-8" id='postSusten' >
    <h4>Listado de Solicitudes</h4>
    <hr>
    <table class="table table-bordered table-stripped">
        <thead>
            <tr>
                <th>N°</th>
                <th>Fecha</th>
                <th> Codigo </th>
                <th>Tesista</th>
                <th>Celular</th>
                <th align="center">Estado</th>
                <th align="center">Opciones</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $solic=$this->dbPilar->getTable("tesSustensSolic","IdCarrera='$IdCarrera' ORDER BY Estado ASC , DateSolic ASC");
            // if ($solic) {
                
            $i=1; 
            foreach($solic->result() as $row){
                $rowi=$this->dbPilar->getSnapRow("tesTramsDet","IdTramite=$row->IdTramite");
                $tram=$this->dbPilar->getSnapRow("tesTramites","Id=$row->IdTramite");
                $tesista=$this->dbPilar->inTesistas("$row->IdTramite");
                $estado=(($row->Estado==0)?"SOLICITUD RECHAZADA":($row->Estado==1))?"NUEVA SOLICITUD":"PROGRAMADO";
                $colsta=($row->Estado==0)?"danger":(($row->Estado==1)?"warning":"info");
                $opt="";
                if ($row->Estado==1) {
                    $opt="<a href='javascript:void(0)' class='btn btn-success' onclick=\" lodPanel('postSusten','cordinads/evaluaSusten/$tram->Codigo') \"> ENLACE </a>";
                }

                echo "<tr>
                        <td align='center'>$i</td>
                        <td align='center'>$row->DateModif </td>
                        <td align='center' style='font-size:18px;'><a href='javascript:void(0)' 
                        onclick=\"jsLoadModalCord($tram->Id,'cordinads/vwInfo/')\">$tram->Codigo</a> </td>
                        <td align='center'>$tesista</td>
                        <td align='center'>".$this->dbPilar->inCelTesista("$tram->IdTesista1")."</td>
                        <td align='center' class='text-$colsta'>$estado </td>
                        <td align='center'>
                        $opt
                        </td>
                      </tr>";
                $i++;
            }  

        // }else{
        //     echo "Aún no tienes solictudes .... ";
        // }
        ?>
        </tbody>
    </table>
</div>
