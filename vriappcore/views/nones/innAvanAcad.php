        <div class="col-md-6 col-md-offset-3">
            <div id="reloj" class="alert alert-info" style="font-size: 25px; font-weight: bold; text-align: center">
                --:--:--
            </div>
            <form onsubmit="alert('en desarrollo');return false">
                <div class="form-group">
                    <input name="curso" type="text" class="form-control" placeholder="Nombre del Curso" autofocus required>
                </div>
                <div class="form-group">
                    <input name="semes" type="number" class="form-control" placeholder="Nro de semestre" autofocus required>
                </div>
                <div class="form-group">
                    <input name="numdni" type="text" class="form-control" placeholder="Horario (Ejm. 09:00 - 11:00)" required>
                </div>
                <div class="form-group">
                    <select name="carrer" class="form-control">
                        <option value="">(Seleccione en que carrera)</option>
                        <?php
                            $tblcarr = $this->dbRepo->getTable( "dicCarreras", "1 ORDER BY Nombre" );
                            foreach( $tblcarr->result() as $row ){
                                echo "<option value='$row->Id'> $row->Nombre </option>";
                            }
                        ?>
                    </select>
                </div>
                <button type="submit" class="form-control btn-warning"> <span class="glyphicon glyphicon-search"></span> MARCAR / FIRMAR </button>
            </form>
        </div>
