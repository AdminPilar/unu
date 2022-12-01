<div class="col-md-12 workspace">
    <div class="col-md-10">
        <span class="label label-default mycaption"> Historial de Asignación y Cambio de Jurados </span>
        <table class="table table-striped table-bordered" style="font-size: 12px">
            <tr>
                <th> Nro </th>
                <th> Ver </th>
                <th class="col-md-3"> Fecha </th>
                <th class="col-md-1"> Etapa </th>
                <th class="col-md-2"> Referencia </th>
                <th class="col-md-6"> Motivo </th>
            </tr>
            <?php
            $nro = 1;
            foreach( $camb->result() as $row ){

                $fecha = mlFechaNorm($row->Fecha);
                $etapa = $row->Tipo==1? "Proyecto" : "Borrador";
                $boton = "<button class='btn btn-info btn-xs'> <i class='glyphicon glyphicon-eye-open'></i>  </button>";

                echo "<tr>";
                echo "<td> <b>$nro</b> </td>";
                echo "<td> $boton </td>";
                echo "<td> $fecha </td>";
                echo "<td> $etapa </td>";
                echo "<td> $row->Referens </td>";
                echo "<td> $row->Motivo </td>";
                echo "</tr>";
                $nro++;
            }
            ?>
        </table>
    </div>

    <div class="col-md-2">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#dlgJur"> Cambio de Jurado </button>
    </div>

</div>
 <div class="col-md-2" align="left">
                <button type="button" class="form-control btn btn-danger" onclick='sndLoad("admin/listBusqTesi", new FormData(frmbusq),true)'> <i class="glyphicon glyphicon-save"></i> Cancelar </button>
            </div>   <br>




<!-- Modal -->
<div class="modal fade" id="dlgJur" tabindex="-1" role="dialog" aria-labelledby="dlgJurLab" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!--
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
      <div class="modal-body">
        <span class="label label-default mycaption"> Cambios de Jurado </span>
        <!-- form -->
        <form class="form-horizontal" id="frmjur" method="POST" action='javascript:sndLoad("admin/inSavCamJur", new FormData(frmjur),true)'>
          <fieldset>
              <!-- select areas -->
              <div class="form-group form-group-sm">
                  <label class="col-md-3 control-label"> <small>Linea de Investigación</small> </label>
                  <div class="col-md-9">
                      <input type="text" class="form-control" value="<?=$tram->IdLinea?> - <?=$this->dbRepo->inLineaInv($tram->IdLinea) ?>" readonly>
                      <input name="idtram" type="hidden" class="form-control" value="<?=$tram->Id?>">
                  </div>
              </div>
              <!-- select areas -->
              <div class="form-group form-group-sm">
                  <label class="col-md-3 control-label"> Presidente </label>
                  <div class="col-md-9">
                      <select id="j1" name="jurado1" class="form-control" required onchange="tesRevIgu()">
                          <option value="" disabled selected> Seleccione </option>
                          <?php
                            foreach( $tjur->result() as $row ){
                                $sel = $tram->IdJurado1==$row->IdDocente? "selected" : "";
                                echo "<option value=$row->IdDocente $sel> ($row->TipoDoc) - $row->CategAbrev :: $row->DatosPers </option>";
                            }
                          ?>
                      </select>
                  </div>
                  <!-- <span class="help-block col-md-7">El Asesor de Proyecto deberá ser un docente Nombrado</span> -->
              </div>
              <!-- select areas -->
              <div class="form-group form-group-sm">
                  <label class="col-md-3 control-label"> Primer Miembro </label>
                  <div class="col-md-9">
                      <select id="j2" name="jurado2" class="form-control" required onchange="tesRevIgu()">
                          <option value="" disabled selected> Seleccione </option>
                          <?php
                            foreach( $tjur->result() as $row ){
                                $sel = $tram->IdJurado2==$row->IdDocente? "selected" : "";
                                echo "<option value=$row->IdDocente $sel> ($row->TipoDoc) - $row->CategAbrev :: $row->DatosPers </option>";
                            }
                          ?>
                      </select>
                  </div>
              </div>
              <!-- select areas -->
              <div class="form-group form-group-sm">
                  <label class="col-md-3 control-label"> Segundo Miembro </label>
                  <div class="col-md-9">
                      <select id="j3" name="jurado3" class="form-control" required onchange="tesRevIgu()">
                          <option value="" disabled selected> Seleccione </option>
                          <?php
                            foreach( $tjur->result() as $row ){
                                $sel = $tram->IdJurado3==$row->IdDocente? "selected" : "";
                                echo "<option value=$row->IdDocente $sel> ($row->TipoDoc) - $row->CategAbrev :: $row->DatosPers </option>";
                            }
                          ?>
                      </select>
                  </div>
                  <div class="col-md-1"></div>
              </div>
              <!-- select areas -->
              <div class="form-group form-group-sm">
                  <label class="col-md-3 control-label"> Asesor </label>
                  <div class="col-md-9">
                      <select id="j4" name="jurado4" class="form-control" required onchange="tesRevIgu()">
                          <option value="" disabled selected> Seleccione </option>
                          <?php
                            foreach( $tdir->result() as $row ){
                                $sel = $tram->IdJurado4==$row->IdDocente? "selected" : "";
                                echo "<option value=$row->IdDocente $sel> ($row->TipoDoc) - $row->CategAbrev :: $row->DatosPers </option>";
                            }
                          ?>
                      </select>
                  </div>
                  <div class="col-md-1"></div>
              </div>
              <div class="form-group form-group-sm">
                  <label class="col-md-3 control-label"> Motivo de Cambio </label>
                  <div class="col-md-9">
                      <textarea id="motivo" name="motivo" rows=4 class="form-control" required></textarea>
                  </div>
              </div>
          </fieldset>
        </form>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal"> Cancelar </button>
        <button type="button" class="btn btn-info" onclick='$("#dlgJur").modal("hide");frmjur.submit()'> Grabar Cambios </button>
      </div>
    </div>
  </div>
</div>