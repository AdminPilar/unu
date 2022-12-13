<?php
    $idtram = ($ttram)? $idtram = $ttram->Id : 0;
?>
<div class="col-md-12"> 
    <div class="col-md-12 workspace">
       <fieldset>
    <legend>Opciones</legend>
        <?php if($ttram->Tipo!=0 && $ttram->Estado!=0 ) {?>
        <button onclick='sndLoad("admin/tesRenunc/<?=$idtram?>", null,true)' class="btn btn-warning" style="font-size: 12px; font-weight: bold"> <i class="glyphicon glyphicon-cog"></i> Renunciar </button>
         <button onclick='sndLoad("admin/tesEdiTitu/<?=$idtram?>", null,true);' class="btn btn-primary" style="font-size: 12px; font-weight: bold"> <i class="glyphicon glyphicon-edit"></i> Cambiar Titulo </button>
        <?php } ?>
        <?php if($ttram->Estado!=0 && $ttram->Tipo==0) {?>
        <button onclick='sndLoad("admin/habilitarProyecto/<?=$idtram?>", null,true)' class="btn btn-success" style="font-size: 12px; font-weight: bold"> <i class="glyphicon glyphicon-cog"></i> Habilitar </button>
        <!--<button onclick='sndLoad("admin/tesHabili/<?=$idtram?>", null,true)' class="btn btn-success" style="font-size: 12px; font-weight: bold"> <i class="glyphicon glyphicon-cog"></i> Habilitar </button>-->
        |
       
      <?php } ?>        
        <!--<button onclick='sndLoad("admin/tesEdiPass/<?=$idtes?>", null,true)' class="btn btn-primary" style="font-size: 12px; font-weight: bold"> <i class="glyphicon glyphicon-cog"></i> Cambiar Datos </button>-->
       
        <button onclick='sndLoad("admin/tesHistory/<?=$idtram?>", null,true);' class="btn btn-primary" style="font-size: 12px; font-weight: bold"> <i class="glyphicon glyphicon-list"></i> Log de Trámite </button>
        <?php if( $ttram AND $ttram->Estado >= 4): ?>
            <button onclick='sndLoad("admin/tesCambios/<?=$idtram?>", null,true);' class="btn btn-primary" style="font-size: 12px; font-weight: bold"> <i class="glyphicon glyphicon-user"></i> Log de Cambios </button>
        <?php endif; ?>
        <?php
        // determinar si ya tiene 3 iteraciones
        if( $ttram->Estado==8 ) {
            echo '| <a target="_blank" href="tesistas/actaProy/'.$ttram->Id.'" class="btn btn-default" style="font-size: 12px; font-weight: bold"> <i class="glyphicon glyphicon-qrcode"></i> Acta Proy </a>';
           // echo '| <a target="_blank" href="tesistas/actaBorr/'.$ttram->Id.'" class="btn btn-default" style="font-size: 12px; font-weight: bold"> <i class="glyphicon glyphicon-qrcode"></i> Acta Borr </a>';
        }

        ?>
        </fieldset>
    </div>
  
</div>

<div class="col-md-12" id="panelView">
  <div class="row col-md-12 workspace">  
      <!-- datos de tesista ini -->
      <div class="col-md-12">
          <!--	Tiempo de carga: <strong> {elapsed_time} s</strong><hr>//-->
           <fieldset>
    <legend>Datos Tesista</legend> 
          <table class="table table-bordered table-striped" style="font-size: 13px">
              <tr>
                  <th> DNI </th>
                  <th> Codigo </th>
                  <th> Datos Personales </th>
                  <th> Carrera </th>
                  <th> Celular </th>
                  <th> e-mail </th>
                  <th> Registro </th>
              </tr>
              <tr>
                  <td> <?=$tdata->DNI?> </td>
                  <td> <?=$tdata->Codigo?> </td>
                  <td> <?=$idtram? $this->dbPilar->inTesistas($idtram) : $tdata->DatosPers?>  <small>(<?=$tdata->Id?>)</small> </td>
                  <td> <?=$tdata->Carrera?> </td>
                  <td> <?=$tdata->NroCelular?> <!--<input onclick="num.value=<?=$tdata->NroCelular?>" data-toggle="modal" data-target="#dlgSms" class="btn btn-info btn-xs" type="button" value="..."> </td>-->
                  <td> <?=$tdata->Correo?> </td>
                  <td> <?=mlFechaNorm($tdata->FechaReg)?> </td>
              </tr>
          </table>
        </fieldset>
         <fieldset>
    <legend>Datos Proyecto</legend> 
          <table class="table table-bordered table-striped" style="font-size: 13px">
              <tr>
                  <th> Cod de Proy </th>
                  <th> Año </th>
                  <th> Tipo</th>
                  <th> Estado </th>
                  <th> Linea </th>
                  <th> Fecha Ini Proy </th>
                  <th> Fecha Ini Borr </th>
                  <th> Ultima Fecha </th>
                  <th> Dias </th>
                  <!--<th> Ejecución </th>-->
              </tr>
              <?php if( $ttram ) 
              {   if($ttram->Estado == 0)
                  {
                    $esatdo = "ANULADO";
                  } 
                  else{
                    $Rowdicestatramite = $this->dbPilar->getSnapRow( "dicestadtram", "Id=$ttram->Estado");
                    $esatdo = $Rowdicestatramite->Nombre;
                  }             

                ?>
              <tr>
                  <td> <?php echo "$ttram->Codigo :: <small>(Id:$ttram->Id)</small>" ?> </td>
                  <td> <?=$ttram->Anio?> </td>
                  <td> <?php if($ttram->Tipo!=0){ echo "ACTIVO";} else {echo "INACTIVO";} ?> </td>
                  <td> <?=$esatdo ?> </td>
                  <td> <small><?php echo $this->dbRepo->inLineaInv($ttram->IdLinea); ?> </small> </td>
                  <td> <?=mlFechaNorm($ttram->FechRegProy)?> </td>
                  <td> <?php if(mlFechaSolo($ttram->FechActBorr) =='30/11/-0001'){ echo "";} else {echo mlFechaNorm($ttram->FechActBorr);} ?> </td>
                  <td> <?=mlFechaNorm($ttram->FechModif)?> </td>
                  <td> <b><?=mlDiasTranscHoy($ttram->FechModif)?></b> </td>
                  <!--<td> <b><?=$proyA? mlDiasTranscHoy($proyA->Fecha) : "(trámite)"?></b> </td>-->
              </tr>
              <?php } ?>
          </table>
        </fieldset>
      </div>
      <!-- datos de tesista fin -->
  </div>
</div>


<?php if( $tamps ): ?>
<div class="col-md-12" id="panelView">
  <div class="col-md-12 workspace">
    <h5> Ampliación de ejecución </h5>
	<table class="table table-bordered table-striped" style="font-size: 13px">
		<tr>
            <th> ID </th>
			<th> F. Aprobación </th>
			<th> F. de Solicitud </th>
			<th> Tiempo Concedido </th>
            <th> Transcurrido </th>
		</tr>
        <tr>
            <td> <?=$tamps->Id."<br><small>".$tamps->Doc?> </td>
            <td> <?=mlFechaNorm($tamps->FechaApro)?> </td>
            <td> <?=mlFechaNorm($tamps->FechaPre)?> </td>
            <td> <?=$tamps->Dias/30?> Meses - <b> <?=$tamps->Dias?> dias</b> </td>
            <td> <b><?=mlDiasTranscHoy($tamps->FechaApro)-730?></b> </td>
        </tr>
    </table>

  </div>
</div>
<?php endif; ?>


<div class="col-md-12" id="panelView">
  <div class="col-md-12 workspace">
     <fieldset>
    <legend>Revisiones del Jurado</legend> 
	<table class="table table-bordered table-striped" style="font-size: 12px">
		<tr>
			<th> Iteracion </th>
			<th> Fecha de Proceso </th>
			<th> Revision_de_Js </th>
			<th> Titulo  </th>
			<th> Archivo </th>
		</tr>
		<?php
			if( $tdets )
			foreach( $tdets->result() as $row ) {

				$lnk = base_url("/repositor/docs/$row->Archivo");
				//		2016-2453

				echo "<tr>";
				echo "<td> $row->Iteracion </td>";
				echo "<td>" .mlFechaNorm($row->Fecha). "</td>";
				echo "<td> [ $row->vb1 / $row->vb2 / $row->vb3 ] </td>";
				echo "<td> <small>$row->Titulo</small> </td>";
				echo "<td> <a class='btn btn-xs btn-info' target=_blank href='$lnk'> VER </a> </td>";
				echo "</tr>";
			}
		?>
	</table>
</fieldset>
  </div>
</div>




<!-- Modal -->
<div class="modal fade" id="dlgSms" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">

      <div class="modal-body" style="padding: 5px; margin-bottom: 0px">
      <!-- ini body -->

          <div class="row">
              <div class="col-md-12" >

                  <div class="panel panel-primary" class="form-horizontal">

                      <div class="panel-heading">
                          <div class="panel-title">
                              CONTENIDO DE SMS
                          </div>
                      </div>

                      <script>
                          function sendSms()
                          {
                              $("#disp").html( 'Procesando...' );
                              $("#disp").load( 'admin/sendMySms', {"num":num.value,"sms":sms.value} );
                              return false;
                          }
                          function countSms()
                          {
                              var sms = $("#sms").val();
                              $("#disp").html( sms.length + " - caracteres de 60" );
                          }
                      </script>

                      <div class="panel-body">
                          <form method="POST" onsubmit="return sendSms()">
                              <div class="form-group">
                                  <label for="num"> Nro Celular: </label>
                                  <input type="number" class="form-control" id="num" name="num">
                              </div>
                              <div class="form-group">
                                  <label for="msg"> Mensaje: </label>
                                  <textarea onkeyup="countSms()" class="form-control" rows="4" id="sms" name="sms" placeholder="Hasta 60 Caracteres"></textarea>
                              </div>
                              <div class="checkbox">
                                  <label><input type="checkbox"> <small>Recuerdame</small> </label>
                              </div>
                              <div id="disp" class="alert alert-info" role="alert" style="height: 36px; padding: 7px"> Esperando... </div>
                              <div class="form-group">
                                  <div class="col-md-6">
                                      <input type="button" class="btn btn-md btn-info btn-block" value="Borrar Texto" onclick="sms.value=null">
                                  </div>
                                  <div class="col-md-6">
                                      <input type="submit" class="btn btn-md btn-success btn-block" value="Enviar SMS">
                                  </div>
                              </div>
                          </form>
                      </div>
                  </div>

              </div>
          </div>


      <!-- fin body -->
      </div>
      <!--
      <div class="modal-footer">
         <button type="button" class="btn btn-warning" data-dismiss="modal"> Cancelar </button>
         <button type="button" class="btn btn-info" onclick='$("#dlgJur").modal("hide");frmjur.submit()'> Grabar Cambios </button>
      </div>
      -->
    </div>
  </div>
</div>
