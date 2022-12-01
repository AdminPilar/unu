<!-- == Column Two == -->
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable();
    } );
</script>
<div class="col-md-12">
     <center><h3> Top de Revisores </h3></center> 
     <table id="example" class="display" cellspacing="0" width="100%" style="font-size: 13px">
                <thead>
                        <tr>
                            <th> Nro </th>
                            <th> E.P. </th>
                            <th> DNI</th>
                            <th> Datos Personales </th>
                            <th> Edad </th>
                            <th> Correo </th>
                            <th> Estado </th>
                            <th> Opciones </th>
                        </tr>
                </thead>
                <tbody>
                    <tr>
                        
                    </tr>
                <tbody>
                </table>    
    <section class="no-ponga esto-content">
        <!-- inicio tab group -->
       <!-- <ul class="nav nav-pills">
          <li class="active"> <a data-toggle="tab" href="#dtab1"> Ultimos Accesos </a> </li>
            <li> <a data-toggle="tab" class="active" href="#dtab2"> Top Ten de Revisores </a> </li>
           <li> <a data-toggle="tab" href="#dtab3"> Accesos Tesistas </a> </li>
        </ul>-->

        <!-- inicio tab slides -->
        <div class="tab-content nav-pills">
            <!-- tab1 xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->
           <!--<div id="dtab1" class="tab-pane fade in active" style="border: 1px solid #C0C0FF; padding: 30px">
                <form class="form-horizontal" name="frmbusq" method=post onsubmit="listDocRepo(); return false;">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-2 col-md-2 control-label"> Datos de Docente </label>
                            <div class="col-sm-7 col-md-7">
                                <input name="tipo" type="hidden" value="3">
                                <input name="expr" type="text" class="form-control input-md" placeholder="Ingrese DNI o Apellidos" required autofocus>
                            </div>
                            <div class="col-sm-3 col-md-3">
                                <button type="submit" class="btn btn-success" onclick="listDocRepo()">
                                    <span class="glyphicon glyphicon-search"></span> Buscar </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <div id="tblist">
                    <table class="table table-stripped">
                        <tr>
                            <th> Nro </th>
                            <th> Id </th>
                            <th> Apellidos y Nombres </th>
                            <th> Fecha </th>
                            <th> Acción </th>
                            <th> OS / Browser </th>
                            <th> IP de Acceso </th>
                        </tr>
                   <?php
                        $nro = $tlogIns->num_rows();
                        foreach( $tlogIns->result() as $row ) {

                            ///$datos = "<small>($row->IdUser)</small> : " . $this->dbRepo->inDocente($row->IdUser);
                            echo "<tr>";
                            echo "<td> $nro </td>";
                            echo "<td> <small>$row->IdUser</small> </td>";
                            echo "<td> $row->DatosPers </td>";
                            echo "<td> $row->Fecha </td>";
                            echo "<td> $row->Accion </td>";
                            echo "<td> | $row->OS | $row->Browser | </td>";
                            echo "<td> $row->IP </td>";
                            echo "</tr>"; $nro--;
                        }
                    ?>
                    </table>
                </div>
            </div> -->

            <!-- tab2 xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->
           
                    <!-- <table id="example" class="display" cellspacing="0" width="100%" style="font-size: 13px">
                        <tr>
                            <th> Nro </th>
                            <th> Id </th>
                            <th> Apellidos y Nombres </th>
                            <th> Nro Celular </th>
                            <th> Accesos </th>
                            <th> Herrados </th>
                            <th> Total </th>
                        </tr>
                   <?php
                        $nro = $tlogSum->num_rows();
                        foreach( $tlogSum->result() as $row ) {

                            echo "<tr>";
                            echo "<td> $nro </td>";
                            echo "<td> <small>$row->IdUser</small> </td>";
                            echo "<td> $row->DatosPers </td>";
                            echo "<td> ... </td>";
                            echo "<td> $row->A1 </td>";
                            echo "<td> $row->A2 </td>";
                            echo "<td> $row->Total </td>";
                            echo "</tr>"; $nro--;
                        }
                    ?> 
                    </table>-->
            <!-- -------------------------------------------------------------- -->
           <!-- <div id="dtab3" class="tab-pane fade" style="border: 1px solid #C0C0FF; padding: 30px">
                <div class="row">
                    <div id="tblist">
                    <table class="table table-stripped">
                        <tr>
                            <th> Nro </th>
                            <th> Id </th>
                            <th> Apellidos y Nombres </th>
                            <th> Fecha </th>
                            <th> Acción </th>
                            <th> OS / Browser </th>
                            <th> IP de Acceso </th>
                        </tr>
                   <?php
                        $nro = $tlogTes->num_rows();
                        foreach( $tlogTes->result() as $row ) {

                            echo "<tr>";
                            echo "<td> $nro </td>";
                            echo "<td> <small>$row->IdUser</small> </td>";
                            echo "<td> $row->DatosPers </td>";
                            echo "<td> $row->Fecha </td>";
                            echo "<td> $row->Accion </td>";
                            echo "<td> | $row->OS | $row->Browser | </td>";
                            echo "<td> $row->IP </td>";
                            echo "</tr>"; $nro--;
                        }
                    ?>
                    </table>
                    </div>
                </div>
            </div>-->
            <!-- -------------------------------------------------------------- -->
        </div>
    </section>

</div> <!--  fin:  div class="col-md-12" -->
