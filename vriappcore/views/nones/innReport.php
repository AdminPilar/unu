

<div class="row">
    <div class="col-md-2">
        <button onclick="doAsis(1)" class="btn btn-info"> Asistens Nomb </button>
    </div>
    <div class="col-md-2">
        <button onclick="doAsis(2)" class="btn btn-warning"> Asistens Cont </button>
        <!-- <a target="_blank" href="/asistencias/web/doList/3" class="btn btn-success"> Asistencias (A) </a> -->
    </div>
    <div class="col-md-8">
        <input id="obs" type="text" class="form-control" placeholder="Ingrese observaciones">
    </div>
</div>

<hr>

<form onsubmit="return LxPost('#dvBody','adlic',new FormData(this))">
<table class="table table-striped" style="font-size: 12px">
    <tr>
        <th> Nro </th>
        <th class="col-md-4"> Docente </th>
        <th class="col-md-5"> Cargo/Licencia </th>
        <th class="col-md-1"> Puede<br>Firmar? </th>
        <th class="col-md-1"> Opciones </th>
    </tr>

    <?php
        $nro = 1;
        $tbl = $this->dbRepo->getTable("dicPartesLic", "IdCarr=$sess->IdCarrera and On=1");

        foreach( $tbl->result() as $row ){

            $doc = $this->dbRepo->inDocente( $row->IdDoc );
            $btn = "<button onclick=\"if(confirm('Desea eliminar este registro?')) LxPost('#dvBody','rmlic/'+$row->Id)\" class='btn btn-xs btn-danger'> <i class='glyphicon glyphicon-trash'></i> </button>";

            echo "<tr>";
            echo "<td> $nro </td>";
            echo "<td> $doc </td>";
            echo "<td> $row->Descrip </td>";
            echo "<td> $row->Firma </td>";
            echo "<td> $btn </td>";
            echo "</tr>";

            $nro++;
        }
    ?>

    <tr>
        <td> <small>(nuevo)</small> </td>
        <td> <input type="text" name="nom" placeholder="Ejm. Tumi Figueroa" class="form-control" required> </td>
        <td> <input type="text" name="car" placeholder="Ejm. Licencia por Viaje de..." class="form-control" required> </td>
        <td> <input type="checkbox" name="fir" checked="checked"> </td>
        <td> <input value="Agregar" type="submit" class="btn btn-info form-control"> </td>
    </tr>
</table>
</form>

<div id="reloj" style="display: none"></div>
