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
              <button onclick="Modificar(<?php echo $row->Id; ?>);" id ='bet' type="submit" title="Editar"><i class="glyphicon glyphicon-pencil"></i></button>
               <button onclick="Acceso(<?php echo $row->Id; ?>);" id ='bet2' type="submit" title="Log de Accesos"><i class="glyphicon glyphicon-user"></i></button>
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
  function Modificar(Id)
  {
    
    document.getElementById("id").value=Id;
    jVRI.ajax({
      url  : "admin/BuscarTesista/"+Id,
      DataType: 'json',
      success: function( data )
      {
        data = JSON.parse(data);
        $('#modalModificar').modal('show');
        document.getElementById("codigo").value = data.DatosTesista[0].Codigo;
       document.getElementById("dni").value = data.DatosTesista[0].DNI;
        document.getElementById("nombres").value = data.DatosTesista[0].Nombres;
         document.getElementById("apellidos").value = data.DatosTesista[0].Apellidos;
          document.getElementById("correo").value = data.DatosTesista[0].Correo;
           document.getElementById("direccion").value = data.DatosTesista[0].Direccion;
            document.getElementById("celular").value = data.DatosTesista[0].NroCelular;
       // alert(data);
      }
    }); 
  }

  //agregadp unuv1.0 - recuperacion de contraseña tesista
  function ModificarDatos()
{	  
  if(document.getElementById("motivo").value.trim() === "")
   {
     $nombre = document.querySelector("#motivo");
     $nombre.focus();
   }
   else 
   {
     datita = new FormData(corazon);
     jVRI("#popis").html( "Enviando...");      
     jVRI.ajax({
        url  : "admin/ModificarTesista/",
        data: datita,
        success: function( arg )
        {
          jVRI("#popis").html( arg );
          document.getElementById('idpro').disabled = true;
        }
      });
   }  
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

  function cerrar()
  {
   // $("#modalModificar .close").click();
    $('#modalModificar').modal('hide');
    lodPanel('admin/panelListaTesista');
  }
  </script>

<!--modal cambiar contraseña- agregado unuv1.0 - cambio de contraseña tesista -->
  <div class="modal" id="modalModificar"  role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: Pink">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 id='mensaje' class="modal-title">Modificar Datos</h3>
            </div>
            <div class ='modal-body' id='popis'>
              <br>
            <form id='corazon' method='POST'>
              <div class="form-group row">
                <label class="col-md-4 control-label"> Codigo </label>
                <div class="col-md-7">
                  <input id ="contra" name="contra" type="text" class="form-control input-md" value="TesistaUNU" readonly>
                  
                </div>
                <div class="col-md-1">
                </div>
              </div>
               <div class="form-group row">
                <label class="col-sm-3 col-form-label">Codigo</label>
                <div class="col-sm-9">
                  <input id="codigo" name="codigo" class="form-control input-md" >
                  <input id ="id" name="id" type="hidden" class="form-control input-md">
                </div>
              </div>
               <div class="form-group row">
                <label class="col-sm-3 col-form-label">DNI</label>
                <div class="col-sm-9">
                  <input name="dni" class="form-control" id="dni" >
                </div>
              </div>
               <div class="form-group row">
                <label class="col-sm-3 col-form-label">Nombres</label>
                <div class="col-sm-9">
                  <input name="nombres" class="form-control" id="nombres" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Apellidos</label>
                <div class="col-sm-9">
                  <input name="apellidos" class="form-control" id="apellidos" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Correo</label>
                <div class="col-sm-9">
                  <input name="correo" class="form-control" id="correo" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Direccion</label>
                <div class="col-sm-9">
                  <input name="direccion" class="form-control" id="direccion" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Celular</label>
                <div class="col-sm-9">
                  <input name="celular" class="form-control" id="celular" >
                </div>
              </div>
              <div class="form-group row">
                <label class="col-sm-3 col-form-label">Motivo</label>
                <div class="col-sm-9">
                  <textarea name="motivo" class="form-control" id="motivo"> </textarea>
                </div>
              </div>            
            </form>
            </div>
            <br>
            <div class="modal-footer">
               <button id='idpro' type="button" onclick='ModificarDatos()' class="btn btn-success" >Procesar</button>
              <button onclick="cerrar();"  class="btn btn-danger" data-dismiss="modal" >Close</button>            
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
