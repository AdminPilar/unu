<div class="col-md-12">
    <div class="col-md-12 workspace">

        <table class="table table-bordered table-striped" style="font-size: 11px">
            <tr>
                <th> Nro </th>
                <th> Acción </th>
                <th> Fecha </th>
            </tr>
        <?php
            $nro = 1;
            foreach( $histo->result() as $row ){

                $fecha = mlFechaNorm( $row->Fecha );

                echo "<tr>";
                echo "<td> <b>$nro</b> </td>";
                echo "<td> $row->Accion </td>";
                echo "<td> $fecha </td>";
                echo "</tr>";
                $nro++;
            }
        ?>
        </table>

    </div>
</div>