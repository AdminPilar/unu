                echo "<link href='".base_url()."includefile/css/bootstrap.css' rel='stylesheet'type='text/css'>";
                echo "<h3 style='color: rgba(15,81,117,1);'> <center><b>REPORTE CONSOLIDADO DE PROYECTOS</b></center><small><h3>";
                echo "<div class='table-responsive' style='font-size:11px'><table class='table table-striped'>"
                . "<tr><th>ORD</th>"
                    . "<th>COD</th>"
                       . "<th>TIP</th>"
                       . "<th>NOMBRES</th>"
                       . "<th>Presi</th><th>J1</th><th>J2</th><th>D</th><th>Tot</th></tr>";
                $table = $this->dbRepo->getSnapView( "vwDocentes", "IdCarrera=$carre AND Activo >=3 " );
                $nro=1;
                foreach ( $table->result() as $row ){
                    $conteo=$this->conteoDoc($row->Id);  
                    //echo "<tr><td> Direc.</td><td>$categoria</td><td>$row->CategAbrev</td><td>$row->Grado / $row->DatosNom</td><td>$row->Antiguedad</td><td>$row->Carrera</td>$conteo</tr>";
                    echo "<tr>";
                    echo "<td> <b>$nro</b> </td>";
                    echo "<td> $row->Codigo     </td>"; ;
                    echo "<td> $row->CategAbrev </td>";
                    echo "<td> $row->DatosPers  </td>";
                    echo " $conteo";
                    echo "</tr>"; 
                    $nro++;
                }
                echo "</table>";