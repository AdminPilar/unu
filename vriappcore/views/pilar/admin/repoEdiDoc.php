<center><h3> Modificar Docente </h3></center>
<div class="row" style="border: 1px solid #C0C0FF; padding: 10px">

    <div class="col-md-12">
        <div class="col-md-11">
            <b> <?php echo $estado . " - [ $rowDoc->Apellidos $rowDoc->Nombres ]"; ?> </b>
            - <small>( <?php echo $rowDoc->Edad>150? "Ingrese FechNacim" : $rowDoc->Edad ?> ) - [Id:<?=$rowDoc->Id?>] </small>
        </div>
        <div class="col-md-1">
            <a onclick="lodPanel('admin/panelLista')" class="btn btn-danger btn-block" href="javascript:void(0)" ><span class="glyphicon glyphicon-off"></span> Salir </a>           
        </div>
    </div>
    <div class="col-md-12">
        <hr>
        <form class="form-horizontal" name="fomdoc" method=post onsubmit="sndLoad('admin/execEditDocRepo',new FormData(fomdoc))">
        <fieldset>
            <div class="col-md-6">
            <!-- Select input-->
            <div class="form-group">
              <label class="col-md-3 control-label"> <i>Categoria</i> </label>
              <div class="col-md-9">
                  <select name="categ" class="form-control" onchange="" required>
                      <option value="" disabled selected> seleccione </option>
                      <?php
                        foreach( $tcateg->result() as $rop )
                        {
                            $isel = ($rop->Id==$rowDoc->IdCategoria)?"selected":"";
                            echo "<option value=$rop->Id $isel> $rop->Nombre </option>";
                        }
                      ?>
                  </select>
              </div>

            </div>

            <!-- Select input-->
            <div class="form-group">
              <label class="col-md-3 control-label"> <i>Facultad</i> </label>
              <div class="col-md-9">
                  <select id="facul" name="facul" class="form-control" onchange="admCarres()" required>
                      <?php
                        foreach( $tfacus->result() as $rop )
                        {
                            $isel = ($rop->Id==$rowDoc->IdFacultad)?"selected":"";
                            echo "<option value=$rop->Id $isel> $rop->Nombre </option>";
                        }
                      ?>
                  </select>
              </div>

            </div>

            <!-- Select input-->
            <div class="form-group">
              <label class="col-md-3 control-label"> <i>Escuela Profesional</i> </label>
              <div class="col-md-9">
                  <select name="carre" class="form-control" id="cbocarre1" required>
                    <?php
                        foreach( $tcarre->result() as $rop )
                        {
                            $isel = ($rop->Id==$rowDoc->IdCarrera)?"selected":"";
                            echo "<option value=$rop->Id $isel> $rop->Nombre </option>";
                        }
                    ?>
                  </select>
              </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Fecha de Ingreso </label>
                <div class="col-md-9">
                    <input name="id" id="id" type="hidden" class="form-control input-md" value="<?=$rowDoc->Id?>">
                    <input name="fechaIn" type="date" class="form-control input-md" value="<?=$rowDoc->FechaIn?>">
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Fecha de Ascenso </label>
                <div class="col-md-9">
                    <input name="fechaAsc" type="date" class="form-control input-md" value="<?=$rowDoc->FechaAsc?>">
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Resolución de Ascenso </label>
                <div class="col-md-9">
                    <input name="resolAsc" type="text" class="form-control input-md" value="<?=$rowDoc->ResolAsc?>">
                </div>
            </div>

            <hr>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Fecha de CONTRATO </label>
                <div class="col-md-9">
                    <input name="fechaCon" id="fechaCon" type="date" class="form-control input-md" value="<?=$rowDoc->FechaCon?>">
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Resol. de CONTRATO </label>
                <div class="col-md-9">
                    <input name="resolCon" id="resolCon" type="text" class="form-control input-md" value="<?=$rowDoc->ResolCon?>">
                </div>
            </div>            
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> DNI </label>
                <div class="col-md-9">
                    <input id="dnx" name="dni" type="text" class="form-control input-md" value="<?=$rowDoc->DNI?>">
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Codigo </label>
                <div class="col-md-9">
                    <input name="codigo" type="text" class="form-control input-md" value="<?=$rowDoc->Codigo?>">
                </div>
            </div>
            </div>
            <div class="col-md-6">
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Apellidos </label>
                <div class="col-md-9">
                    <input name="apels" type="text" class="form-control input-md" value="<?=$rowDoc->Apellidos?>">
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Nombres </label>
                <div class="col-md-9">
                    <input name="nomes" type="text" class="form-control input-md" value="<?=$rowDoc->Nombres?>">
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Nacimiento </label>
                <div class="col-md-9">
                    <input name="fechaNac" type="date" class="form-control input-md" value="<?=$rowDoc->FechaNac?>">
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Dirección </label>
                <div class="col-md-9">
                    <input name="direcc" type="text" class="form-control input-md" value="<?=$rowDoc->Direccion?>">
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Correo </label>
                <div class="col-md-9">
                    <input name="mail" type="email" class="form-control input-md" value="<?=$rowDoc->Correo?>">
                </div>
            </div>
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Celular </label>
                <div class="col-md-9">
                    <input name="celu" type="number" class="form-control input-md" value="<?=$rowDoc->NroCelular?>">
                </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label"> Contraseña </label>
                <div class="col-md-9">
                    <input name="clave" type="password" class="form-control input-md" value="">
                </div>
            </div>

			<!-- Text input-->
            <div class="form-group">
                <label class="col-md-3 control-label">  </label>
                <div class="col-md-9">
                    <input type="checkbox" name="cambest" id="cambest" value="si"> Agregar Cambio de Estado? <br>
                </div>
            </div>

            <!-- Select input-->
            <div class="form-group">
              <label class="col-md-3 control-label"> Estado de Docentes </label>
              <div class="col-md-9">
                  <select name="nesta" id="nesta" class="form-control" id="cbocarre1">
                    <?php
                        foreach( $testdc->result() as $rop )
                        {
                            $isel = ($rop->Id==$rowDoc->Activo)?"selected":"";
                            echo "<option value=$rop->Id $isel> $rop->Nombre </option>";
                        }
                    ?>
                  </select>
              </div>
            </div>

			<div class="form-group">
                <label class="col-md-3 control-label"> Documento de Ref. </label>
                <div class="col-md-9">
					<input name="docu" id="docu" type="text" class="form-control input-md" value="">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label"> Descripción </label>
                <div class="col-md-9">
					<textarea name="desc" id="desc" type="text" class="form-control" rows="3"></textarea>
                </div>
            </div>



            <!-- submit -->
            <div class="form-group">
                <label class="col-md-3 control-label"></label>
				<div class="col-md-3">
                    <input type="button" onclick="fillItms()" class="btn btn-danger col-xs-12" value="Contrato.2018">
                </div>
				<div class="col-md-3"></div>
                <!-- Button (Double) -->
                <div class="col-md-3">
                    <input type="submit" class="btn btn-success col-xs-12" value="Grabar Edición">
                </div>
            </div>
        </div>
        </fieldset>
        </form>
    </div>

    <!--<div class="col-md-1">
        <b>DATOS PARTICULARES</b><hr>
        <?PHP
            if( !$rowDoc->DNI ){
                echo ">>Sin DNI registrado";
                exit;
            }

            // Gen.Api Media
            if( $media ){
                echo "<img width=100% src='$media->foto' class='img-responsive'>";
                echo "<img width=100% src='$media->firma' class='img-responsive'>";
                echo "<img width=100% src='$media->huella' class='img-responsive'>";
            }
        ?>
    </div>-->

</div>

