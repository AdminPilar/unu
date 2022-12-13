<?php 
    //echo "string----".$facul;

?>

<script>
  //agregaod unuv1.0
$(document).ready(function() {
    $('#example').DataTable();
    } );

    //agregado unuv1.0
  function listDocGrad(iddocen)
{    
    $("#dlg").modal();
    $("#doc").val( iddocen );
    $("#tres").empty();
    cargarGrados(iddocen);
}

 //agregado unuv1.0
  function listDocLineas(iddocen)
{    
    $("#dlg1").modal();
    $("#doc1").val( iddocen );
    $("#cuatro").empty();
    cargarLineas(iddocen);
}
function AccesoDoc(codigo){
    jVRI.ajax({
        type:'GET', 
        url: "admin/AccesoDoc/"+codigo,
        DataType: 'json',
        success: function(data) {
          $('#modal_acceso').modal('show');
          data = JSON.parse(data);
       // console.log(data);
        var valor = '';
        data.logindocentes.forEach(logdoc => {
          valor += logdoc.Tipo;
           valor += "<tr>"+
             "<td>" + logdoc.Fecha + "</td>"+
             "<td>" + logdoc.Accion + "</td>"+ 
             "<td>" + logdoc.OS + "</td>"+
             "<td>"+ logdoc.Browser+"</td>"+
             "<td>"+ logdoc.IP+"</td>"+
             "<tr>";
         
        });
         $("#tbodyProducto").html(valor);
        }
      });
  }

  </script>
<div class="col-md-12">
  <div id="tblist"> </div>
  <div class="tab-content nav-pills">
    <!-- ..................Repositorio de Docentes................... -->
    <div id="dtab1" class="tab-pane fade in active" style="">
      <center><h3> Repositorio Docentes</h3></center>
      <div class="col-md-2">
         <a href="#dtab2" data-toggle="tab" class="btn btn-success btn-block">Agregar <i class="glyphicon glyphicon-plus"></i></a>
      </div>
      <div class="col-md-10">      
      </div><br><br>
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
                <?PHP
                    foreach( $tdocen->result() as $row ){
                    ?>
                    <tr>
                        <td><?php echo $row->Id; ?></td>
                        <td><?php echo $row->Carrera; ?></td>
                        <td><?php echo $row->DNI; ?></td>
                        <td><?php echo $row->DatosPers; ?></td>
                        <td><?php echo $row->Edad; ?> </td>
                        <td><?php echo $row->Correo; ?> </td>
                        <td><?php echo $row->Activo; ?></td>
                        <td>
                       <!-- <button onclick="RestaurarContrase(<?php echo $row->Id; ?>,2);" id ='bet' type="submit" title="Enviar Cuenta"><i class="glyphicon glyphicon-wrench"></i></button>
                        <button onclick="RestaurarContrase(<?php echo $row->Id; ?>,1);" id ='bet' type="submit" title="Restaurar Contraseña"><i class="glyphicon glyphicon-wrench"></i></button> -->
                         | <button onclick="listDocRepo(<?php echo $row->Id; ?>);" type="button" title="Modificar"><i class="glyphicon glyphicon-pencil"></i></button>
                         |  <button onclick="listDocGrad(<?php echo $row->Id; ?>);" type="button" title="Grados/Titulos"><i class="glyphicon glyphicon-education"></i></button>
                         |  <button onclick="listDocLineas(<?php echo $row->Id; ?>);" type="button" title="Lineas Investigación"><i class="glyphicon glyphicon-tasks"></i></button>
                        |   <button onclick="AccesoDoc(<?php echo $row->Id; ?>);" id ='bet' type="submit" title="Log de Accesos"><i class="glyphicon glyphicon-user"></i></button>
                        </td>
                    </tr>
                    <?PHP    
                    } ?>
                    <tbody>
                </table>
    </div>
    <!-- ..................Fin repositorio................... -->

    <!-- ..................Nuevo Docente................... -->
    <div id="dtab2" class="tab-pane fade" >
      <center><h3> Nuevo Docente</h3></center>
      <div class="col-md-12" style="border: 1px solid #C0C0FF; padding: 30px">
        <form class="form-horizontal" name="frmnovo" method=post onsubmit="sndLoad('admin/execNewDocRepo',new FormData(frmnovo))">
          <div class="col-md-6">
            <div class="form-group">
              <label class="col-md-3 control-label"> <i>Tipo de Docente</i> </label>
              <div class="col-md-8">
                <select name="tipod" class="form-control" onchange="">
                  <option value=2> CONTRATADO </option>
                </select>
              </div>
              <div class="col-md-1"></div>
            </div>
             <div class="form-group">
              <label class="col-md-3 control-label"> <i>Categoria</i> </label>
              <div class="col-md-8">
                <select name="categ" class="form-control" onchange="" required>
                  <option value="" disabled selected> seleccione </option>
                  <?php
                  foreach( $tcateg->result() as $row )
                  {
                    echo "<option value=$row->Id> $row->Nombre </option>";
                  }
                  ?>
                </select>
              </div>
              <div class="col-md-1"></div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> <i>Facultad</i> </label>
              <div class="col-md-8">
                <select id="facul" name="facul" class="form-control" onchange="listCboCarrs()" required>
                  <option value="" disabled selected> seleccione </option>
                    <?php
                    foreach( $tfacus->result() as $row )
                    {
                      echo "<option value=$row->Id> $row->Nombre </option>";
                    }
                    ?>
                </select>
              </div>
              <div class="col-md-1"></div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> <i>Escuela Profesional</i> </label>
              <div class="col-md-8">
                <select name="carre" id="carre" class="form-control" required>
                </select>
              </div>
              <div class="col-md-1"></div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Fecha de Ingreso </label>
              <div class="col-md-8">
                <input name="fechaIn" type="date" class="form-control input-md" value="2017-04-21">
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Fecha de Ascenso </label>
              <div class="col-md-8">
                <input name="fechaAsc" type="date" class="form-control input-md" value="">
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Resolución de Ascenso </label>
              <div class="col-md-8">
                <input name="resolAsc" type="text" class="form-control input-md" value="">
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Fecha de CONTRATO </label>
              <div class="col-md-8">
                <input name="fechaCon" type="date" class="form-control input-md" value="2017-04-21">
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Resol. de CONTRATO </label>
              <div class="col-md-8">
                <input name="resolCon" type="text" class="form-control input-md" value="R.R. Nro 1225-2017-R-UNA">
              </div>
              <div class="col-md-1">
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <script>
              function cargaDotax()
              {
                $.ajax({
                  url : "admin/getLeData/"+dni.value,
                  dataType: "json",
                  success : function( res ){
                    console.log( res );
                    fechaNac.value=res.FechaNac;
                    nacim.value = res.FechaNac;
                    apels.value = res.ApPaterno +" "+ res.ApMaterno;
                    nomes.value = res.Nombres;
                    }
                });
              }
            </script>
            <div class="form-group">
              <label class="col-md-3 control-label"> DNI </label>
              <div class="col-md-5">
                <input id="dni" name="dni" type="number" class="form-control input-md" value="cargaDotax()" required>
              </div>
              <div class="col-md-3">
                <button type="button" class="form-control btn btn-info" onclick="cargaDotax()"> <i class="glyphicon glyphicon-search"></i> BUSCAR </button>
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Codigo </label>
              <div class="col-md-8">
                <input name="codigo" type="text" class="form-control input-md" value="" required>
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Apellidos </label>
              <div class="col-md-8">
                <input id="apels" name="apels" type="text" class="form-control input-md" value="" required>
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Nombres </label>
              <div class="col-md-8">
                <input id="nomes" name="nomes" type="text" class="form-control input-md" value="" required>
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Nacimiento </label>
              <div class="col-md-6">
                <input id ='fechaNac' name="fechaNac" type="date" class="form-control input-md" value="" required>
              </div>
              <div class="col-md-2">
                <input id="nacim" name="nacim" type="text" class="form-control input-md" value="" required>
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Dirección </label>
              <div class="col-md-8">
                <input name="direcc" type="text" class="form-control input-md" value="" required>
              </div>
              <div class="col-md-1">
                </div>
              </div>
              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-3 control-label"> Correo </label>
                <div class="col-md-8">
                  <input name="mail" type="email" class="form-control input-md" value="" required>
                </div>
                <div class="col-md-1">
                </div>
              </div>
              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-3 control-label"> Celular </label>
                <div class="col-md-8">
                  <input name="celu" type="number" class="form-control input-md" value="" required>
                </div>
                <div class="col-md-1">
                </div>
              </div>
              <!-- Text input-->
              <div class="form-group">
                <label class="col-md-3 control-label"> Contraseña </label>
                <div class="col-md-8">
                  <input name="clave" type="password" class="form-control input-md" value="">
                </div>
                <div class="col-md-1">
                </div>
              </div>
              <!-- submit -->
              <div class="form-group">
                <label class="col-md-5 control-label"></label>
                <div class="col-md-3">
                   <a href="#dtab1" data-toggle="tab" class="btn btn-danger btn-block">Atras <i class="glyphicon glyphicon-repeat"></i></a>
                </div>
                <!-- Button (Double) -->
                <div class="col-md-3">
                  
                  <input type="submit" class="btn btn-success col-xs-12" value="Nuevo Docentes">
                </div>
                <div class="col-md-1"></div>
              </div>
          </div>        
        </form>
      </div>         
  </div>
  <!-- ..................Fin Docente................... --> 
</div>









<!-- ..................Grados y Titulos................... -->
<div class="modal fade" id="dlg" tabindex="-1" role="dialog" aria-labelledby="dlgJurLab" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <span class="label label-default mycaption"> Listado de Grado y Títulos </span>
        <!-- form -->
          <form class="form-horizontal" id="frmDatos" name="frmDatos" novalidate="novalidate" method="POST">
          <fieldset>
              <!-- select areas -->
              <div class="form-group form-group-sm">
                    <label class="col-sm-2 col-md-2 control-label"> Universidad </label>
                    <div class="col-md-8">
                        <select id="universidad" name="universidad" class="form-control" required>
                            <option value="" disabled selected> seleccione </option>
                            <?php
                                foreach( $tuniversidad->result() as $row )
                                {                                   
                                    echo "<option value=$row->Id> $row->Nombre </option>";
                                }
                            ?>
                        </select>
                         <input name="doc" id="doc" type="hidden" class="form-control">
                    </div>
              </div>
              <div class="form-group form-group-sm">
                    <label class="col-sm-2 col-md-2 control-label"> Abrev. </label>
                    <div class="col-md-3" >
                       <select name="abrev" id="abrev" class="form-control">
                            <option value="" disabled selected> seleccione </option>
                            <option value="4">BACH.</option>
                            <option value="3">ING.</option>
                            <option value="2">MG.</option>
                            <option value="1">DR.</option>
                      </select>
                    </div>
                    <label class="col-sm-2 col-md-2 control-label"> Fecha </label>
                    <div class="col-md-5">
                        <input type="text" id="fecha" name="fecha" class="form-control" value="">                    
                  </div>
              </div>              <!-- select areas -->
              <div class="form-group">
                  <label class="col-md-2 control-label"> Mención </label>
                  <div class="col-md-10">
                        <input type="text" id="mencion" name="mencion" class="form-control" value="">
                  </div>
              </div>
              <div class="form-group">
                  <label class="col-md-2 control-label"> Archivo </label>
                  <div class="col-md-6">
                     <input name="nomarch" id="nomarch" type="file" class="file form-control input-md" >
                  </div>
                  <div class="col-md-2">
                      <button type="button" class="btn btn-success btn-block" onclick="AgregarGrado()"> <i class="glyphicon glyphicon-plus"></i> </button>
                  </div>
              </div>
              <hr>
              <!-- select areas -->
              <div class="form-group form-group-sm">
                  <div class="col-md-12">
                      <table class="table table-bordered table-striped" style="font-size: 10px">
                          <th> Abrev. </th>
                          <th> Mención </th>
                          <th> Institucion </th>
                          <th> Fecha </th>
                          <th> Archivo </th>
                          <tbody id="tres"></tbody>
                      </table>
                  </div>
              </div>

          </fieldset>
        </form>

      </div>
      <div class="modal-footer">
        <button  type="button" class="btn btn-warning" data-dismiss="modal"> Salir </button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="dlg1" tabindex="-1" role="dialog" aria-labelledby="dlgJurLab" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <span class="label label-default mycaption"> Listado de Linea de Investigacion </span>
        <!-- form -->
          <form class="form-horizontal" id="frmDatos1" name="frmDatos1" novalidate="novalidate" method="POST">
          <fieldset>
              <!-- select areas -->
              <!--<div class="form-group form-group-sm">
                    <label class="col-sm-2 col-md-2 control-label"> Linea </label>
                    <div class="col-md-8">
                        <select id="linea1" name="linea1" class="form-control" required>
                            <option value="" disabled selected> seleccione </option>
                            <?php
                                foreach( $tlinea->result() as $row )
                                {                                   
                                    echo "<option value=$row->Id> $row->Nombre </option>";
                                }
                            ?>
                        </select>
                         <input name="doc1" id="doc1" type="hidden" class="form-control">
                    </div>
                     <div class="col-md-2">
                      <button type="button" class="btn btn-success btn-block" onclick="AgregarLinea()"> <i class="glyphicon glyphicon-plus"></i> </button>
                  </div>  
                  <label class="col-md-12 control-label" style="display:block" id=''>betxy  </label>                
              </div>
              <div class="form-group form-group-sm" align="rigth">
                  <label class="col-md-12 control-label" style="display:none" id='MsgError'>  </label> 
                 
              </div>-->
               <div class="form-group row">
                <label class="col-sm-2 col-md-2 control-label"> Linea </label>
                <div class="col-sm-8">
                  <select id="linea1" name="linea1" class="form-control" required>
                            <option value="" disabled selected> seleccione </option>
                            <?php
                                foreach( $tlinea->result() as $row )
                                {                                   
                                    echo "<option value=$row->Id> $row->Nombre </option>";
                                }
                            ?>
                        </select>
                         <input name="doc1" id="doc1" type="hidden" class="form-control">
                </div>
                <div class="col-md-2">
                      <button type="button" class="btn btn-success btn-block" onclick="AgregarLinea()"> <i class="glyphicon glyphicon-plus"></i> </button>
                  </div> 
              </div> 
              <div class="form-group row">
                <label class="col-sm-2 col-md-2 control-label">  </label>
                <div class="col-sm-8">
                   <label style="display:none;color:red;font-size: 10px" id='MsgError'>  </label> 
                </div>
                <div class="col-md-2">
                </div> 
              </div>                       
              <hr>
              <!-- select areas -->
              <div class="form-group form-group-sm">
                  <div class="col-md-12">
                      <table class="table table-bordered table-striped" style="font-size: 10px">
                          <th> Id </th>
                          <th> Nombre Linea </th>
                          <th> Estado </th>
                          <th> Fecha </th>
                          <tbody id="cuatro"></tbody>
                      </table>
                  </div>
              </div>

          </fieldset>
        </form>

      </div>
      <div class="modal-footer">
        <button  type="button" class="btn btn-warning" data-dismiss="modal"> Salir </button>
      </div>
    </div>
  </div>
</div>
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
                <input id ="contra" name="contra" type="text" class="form-control input-md" value="Usu@rioUNU" readonly>
                <input id ="codigo" name="codigo" type="text" class="form-control input-md"  >
                <input id ="num" name="num" type="hidden" class="form-control input-md"  >

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

<script>

 //agregadp unuv1.0 - recuperacion de contraseña Docente
 function RestaurarContrase(codigo,num)
  {
    $('#modalcambio').modal('show');
    document.getElementById("num").value=num;
    document.getElementById("codigo").value=codigo;
  }

   //agregadp unuv1.0 - recuperacion de contraseña Docente
   function EnviarContrase()
{	  var num=document.getElementById("num").value;
  if (num==1) {
	 datita = new FormData(corazon);
   val = document.getElementById("codigo").value;
		jVRI("#popis").html( "Enviando...");
		$('#idpro').prop('disabled', true);
		jVRI.ajax({
			url  : "admin/RestaurarContraseDocente/"+val,
			data :  datita ,
			success: function( arg )
			{
				jVRI("#popis").html( arg );
			}
		});	

    }
    else{
       datita = new FormData(corazon);
   val = document.getElementById("codigo").value;
    jVRI("#popis").html( "Enviando...");
    $('#idpro').prop('disabled', true);
    jVRI.ajax({
      url  : "admin/EnviarCuenta/"+val,
      data :  datita ,
      success: function( arg )
      {
        jVRI("#popis").html( arg );
      }
    }); 

    }
}

//agregado unuv1.0 - Mantenimiento Docente  
function cargarGrados(iddoce){

    $("#tres").empty();    

     $.ajax({
        url  : 'admin/DocenteGrados/'+iddoce,       
        success: function( arg )
        {
            $("#tres").html( arg );
        }
    });
}

//agregado unuv1.0 - Mantenimiento Docente  
function cargarLineas(iddoce){

    $("#cuatro").empty();    

     $.ajax({
        url  : 'admin/DocenteLineas/'+iddoce,       
        success: function( arg )
        {
            $("#cuatro").html( arg );
        }
    });
}

 function AgregarLinea(){

   $("#linea").val( "" ); 
   document.getElementById('MsgError').innerHTML=''; 
    var id = document.getElementById('doc1').value;
     $.ajax({                        
       type: "POST",                 
       url:'admin/AgregarLinea/',                   
       data: $('#frmDatos1').serialize(),
       success: function(arg)            
       { 
        //alert(arg);       
          if(arg.trim()==="no")
          {
             document.getElementById('MsgError').style.display = 'block';
              document.getElementById('MsgError').innerHTML = 'Esa linea ya esta asociada al docente';

          }
          else
          {
            $("#cuatro").empty();
            cargarLineas(arg);
          }

         
       }
     });   
}

 function AgregarGrado(){

    $("#tres").empty();

     $.ajax({                        
       type: "POST",                 
       url:'admin/AgregarGrados/',                   
       data: $('#frmDatos').serialize(),
       success: function(arg)            
       {        
         cargarGrados(arg);
         $("#universidad").val( "" );
         $("#abrev").val( "" ); 
         $("#fecha").val( "" ); 
         $("#mencion").val( "" ); 
         $("#archivo").val( "" );
         //document.getElementById("doc").value = arg;
         $("#doc").val( arg );
         //document.getElementById('mencion').value='hola '+document.getElementById('doc').value ;

       }
     });   
}

//Agregado por bet, lo saque de manager.js
function listDocRepo(id)
{
   $("#tblist").load( "admin/listDocRepo/"+id);
   document.getElementById("dtab1").style.display = "none";
}

</script>

<div class="modal fade" id="modal_acceso" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <CENTER><h3 id='mensaje' class="modal-title">LISTA DE LOGEOS DEL DOCENTE</h3></CENTER>
            </div>
            <div class="box-body">
              <div class="table table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>FECHA</th>
                      <th>ACCION</th>
                      <th>S.O</th>
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

<!-- tab2 xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx -->
 