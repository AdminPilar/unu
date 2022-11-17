
<table class="table table-bordered table-hover">
    <tr>
        <th> Nro </th>
        <th> Fecha </th>
        <th> Datos Personales </th>
        <th> Asistencia </th>
    </tr>
    <?php

    $nro = 1;

    if( $part )
    foreach( $part->result() as $row ){

        $doc = $this->dbRepo->getSnapRow( "vwDocentes", "Id=$row->IdDoc" );

        echo "<tr>";
        echo "<td> $nro </td>";
        echo "<td> $row->Marco </td>";
        echo "<td> $doc->DatosNom </td>";
        echo "<td> Firmó </td>";
        echo "</tr>";

        $nro++;
    }

    ?>
</table>