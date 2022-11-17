<div class=row>
    <div class="col-md-3">
        <a href="/siraa/web/trabajos" class="btn btn-block btn-info"> Salir </a>
        <a href="/siraa/web/reportes" class="btn btn-block btn-success"> Reporte </a>
    </div>

    <!-- <div class="col-md-1" style="background: #E0F0E0"></div> -->

    <div class="col-md-9">
        <h4> Reporte: trabajos de investigación </h4>

        <table class="table table-striped table-bordered" style="font-size: 13px">
            <tr>
                <th> Nro </th>
                <th> Estado </th>
                <th> Responsable </th>
                <th> Correo </th>
                <th> Archivo </th>
            </tr>
            <?php

            $nro = 1;
            foreach( $table->result() as $row ){

                $lista = array("En espera", "Corregir", "Aprobado");
                $linkx = "<a class='btn btn-sm btn-info' href='/vriadds/siraa/files/$row->Arch'> Ver Archivo </a>";

                echo "<tr>";
                echo "<td> $nro </td>";
                echo "<td> " .$lista[$row->Calific]. " <br> $row->FechaReg </td>";
                echo "<td> $row->Datos<br><small>$row->Proced</small> </td>";
                echo "<td> $row->Correo </td>";
                echo "<td> $linkx </td>";
                echo "</tr>";

                $nro++;
            }

            ?>
        </table>
    </div>
</div>
