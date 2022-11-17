<?php
echo date('H:i:s Y-m-d');
//echo '<script type="text/JavaScript"> location.reload(); </script>';
?>
<script type="text/javascript" language="javascript" class="init">
 // document.getElementById("pmsg").style.display= "none";
  
  $(document).ready(function() {
    $('#example').DataTable();
    } );
  
  function cerra()
  {
    $("#modalcambio").on('hide', function () {
        window.location.reload();
    });
  }
</script>

<div class="col-md-12">
  <div id="tblist"> </div>
    <div class="tab-content nav-pills">
    <!-- ..................Repositorio de Tesistas................... -->
      <div id="dtab1" class="tab-pane fade in active" style="">
        <center><h3> Repositorio Tesistas </h3></center>        
        
        <table id="example" class="display" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Escuela Profesional</th>
              <th>Codigo</th>
              <th>DNI</th>
              <th>Datos Personales</th>
              <th>Correo</th>
              <th>Celular</th>
              <th>Fecha de Registro</th>
              <th>Opciones</th>
            </tr>
          </thead>    
          <tbody>
            <?PHP
              foreach( $tdocen->result() as $row ){
            ?>
            <tr style="font-size:85%;">
              <td><?php echo $row->Id; ?></td>
              <td><?php echo $row->Carrera; ?></td>
              <td><?php echo $row->Codigo; ?></td>
              <td><?php echo $row->DNI; ?></td>
              <td><?php echo $row->DatosPers; ?></td>
              <td><?php echo $row->Correo; ?> </td>
              <td><?php echo $row->NroCelular; ?> </td>
              <td><?php echo $row->FechaReg; ?></td>
              <td>
               <button onclick="RestaurarContrase(<?php echo $row->Id; ?>);" id ='bet' type="submit" title="Restaurar Contraseña"><i class="glyphicon glyphicon-wrench"></i></button>
               <button onclick="Acceso(<?php echo $row->Id; ?>);" id ='bet2' type="submit" title="Log de Accesos"><i class="glyphicon glyphicon-user"></i></button>
               <button onclick="modificar(<?php echo $row->Id; ?>);" id ='editar' type="submit" title="Editar"><i class="glyphicon glyphicon-pencil"></i></button>
                 &nbsp; &nbsp; &nbsp; &nbsp;
                <!--<input type="button" value="Abrir modal éxito" name="registrar"  id="btnExito" class="registrar" tabindex="8" />
               <button onclick="listDocRepo(<?php echo $row->Id; ?>);" type="button" title="Modificar"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button onclick="listDocRepo(<?php echo $row->Id; ?>);" type="button" title="Detalles"><i class="glyphicon glyphicon-pencil"></i></button>-->
              </td>
            </tr>
            <?PHP           
            } 
            ?>
          </tbody>
        </table>     
      </div>
    </div>
  </div>    
</div>
<script>
  //agregadp unuv1.0 - recuperacion de contraseña tesista
  function RestaurarContrase(codigo)
  {
    $('#modalcambio').modal('show');
    document.getElementById("codigo").value=codigo;
  }

  //agregadp unuv1.0 - recuperacion de contraseña tesista
  function EnviarContrase()
{	  
	 datita = new FormData(corazon);
   val = document.getElementById("codigo").value;
		jVRI("#popis").html( "Enviando...");
		$('#idpro').prop('disabled', true);
		jVRI.ajax({
			url  : "admin/RestaurarContrase/"+val,
			data :  datita ,
			success: function( arg )
			{
				jVRI("#popis").html( arg );
			}
		});	
}

function Acceso(codigo){
    jVRI.ajax({
        type:'GET', 
        url: "admin/Acceso/"+codigo,
        DataType: 'json',
        success: function(data) {
          $('#modal_acceso').modal('show');
          data = JSON.parse(data);
       // console.log(data);
        var valor = '';
        data.logintesistas.forEach(logtes => {
          valor += logtes.Tipo;
           valor += "<tr>"+
             "<td>" + logtes.Id + "</td>"+
             "<td>" + logtes.Fecha + "</td>"+
             "<td>" + logtes.Accion + "</td>"+ 
             "<td>" + logtes.OS + "</td>"+
             "<td>"+ logtes.Browser+"</td>"+
             "<td>"+ logtes.IP+"</td>"+
             "<tr>";
         
        });
         $("#tbodyProducto").html(valor);
        }
      });
  }
  </script>

<!--modal cambiar contraseña- agregado unuv1.0 - cambio de contraseña tesista -->
  <div class="modal fade" id="modalcambio"  role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: Pink">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 id='mensaje' class="modal-title">Recuperacion de Contraseña</h3>
            </div>
            <div class ='modal-body' id='popis'>
              <br>
            <form id='corazon' method='POST'>
            <div class="form-group">
              <label class="col-md-4 control-label"> Contraseña por defecto </label>
              <div class="col-md-7">
                <input id ="contra" name="contra" type="text" class="form-control input-md" value="TesistaUNU" readonly>
                <input id ="codigo" name="codigo" type="hidden" class="form-control input-md"  >
              </div>
              <div class="col-md-1">
              </div>
            </div>
            </form>
            </div>
            <br>
            <div class="modal-footer">
              <button id='idpro' type="button" onclick='EnviarContrase()' class="btn btn-success" >Procesar</button>
              <button onclick="" type="button"  class="btn btn-danger" data-dismiss="modal" data-backdrop="false">Close</button>              
            </div>
        </div>

      </div>
    </div>

<!-- /MODAL  -->

<!--...................Modal acceso ---------------------------->
<div class="modal fade" id="modal_acceso" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <CENTER><h3 id='mensaje' class="modal-title">LISTA DE LOGEOS DEL TESISTAS</h3></CENTER>
            </div>
            <div class="box-body">
              <div class="table table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>FECHA</th>
                      <th>ACCION</th>
                      <th>SISTEMA OPERATIVO</th>
                      <th>NAVEGADOR</th>
                      <th>IP</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyProducto">

                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
