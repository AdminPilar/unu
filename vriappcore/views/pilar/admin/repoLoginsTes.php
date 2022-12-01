<?php 
    //echo "string----".$facul;

?>

<script>
  //agregaod unuv1.0
$(document).ready(function() {
    $('#example').DataTable( {
        "order": [[ 0, "desc" ]],
        'pageLength': 25
    } );
} );
  

  </script>
<div class="col-md-12">
  <div id="tblist"> </div>
  <div class="tab-content nav-pills">
    <!-- ..................Repositorio de Docentes................... -->
    <div id="dtab1" class="tab-pane fade in active" style="">
      <center><h3> Top Accesos Tesistas </h3></center>
          <div class="col-md-2">
           </div>
            <div class="col-md-10">      
          </div><br><br>
              <table id="example" class="display" cellspacing="0" width="100%" style="font-size: 13px">
                        <thead>
                             <tr>
                            <th> Nro </th>
                            <th> Apellidos y Nombres </th>
                            <th> Nro Celular </th>
                            <th> Accesos </th>
                            <th> Herrados </th>
                            <th> Total </th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                        $nro = $tlogSum->num_rows();
                        foreach( $tlogSum->result() as $row ) {

                            echo "<tr>";
                            echo "<td> $nro </td>";
                            echo "<td> $row->DatosPers </td>";
                            echo "<td> ... </td>";
                            echo "<td> $row->A1 </td>";
                            echo "<td> $row->A2 </td>";
                            echo "<td> $row->Total </td>";
                            echo "</tr>"; $nro--;
                        }
                    ?> 
                        </tbody>
                </table>
        </div>
</div>
</div>

    <!-- ..................Fin repositorio................... -->

    <!-- ..................Nuevo Docente................... -->
   