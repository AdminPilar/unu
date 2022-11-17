<?php 
    //echo "string----".$facul;

?>
<script type="text/javascript" language="javascript" class="init">


$(document).ready(function() {
    $('#example').DataTable();
} );


    </script>
<div class="col-md-12">
  <div id="tblist"> </div>
  <div class="tab-content nav-pills">
    <!-- ..................Repositorio de Docentes................... -->
    <div id="dtab1" class="tab-pane fade in active" style="">
      <center><h3> Repositorio Posibles Tesistas </h3></center>     

      
      <div class="col-md-2" style="margin: 5px;">
         <a href="#dtab2" data-toggle="tab" class="btn btn-success btn-block">Agregar <i class="glyphicon glyphicon-plus"></i></a>
      </div>
      <div class="col-md-1">        
      </div>
  
      <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
                    <tr>
                        <th>Nro</th>
                        <th>Codigo</th>
                        <th>DNI</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Escuela</th>
                        <th>Opciones</th>
                    </tr>
        </thead>
        <tfoot>
         
                    <tr>
                         <th>Nro</th>
                        <th>Codigo</th>
                        <th>DNI</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Escuela</th>
                        <th>Opciones</th>
                    </tr>
          </tfoot>       
        <tbody>
            <?PHP
          foreach( $tdocen->result() as $row ){
          ?>
                    <tr>
                        <td><?php echo $row->id; ?></td>
                        <td><?php echo $row->codigo; ?></td>
                        <td><?php echo $row->documento_numero; ?></td>
                        <td><?php echo $row->nombres; ?></td>
                        <td><?php echo $row->apellidos; ?></td>
                        <td><?php echo $row->escuela; ?></td>
                        <td>           </td>
                    </tr>
          <?PHP    
          
        } ?>
        </tbody>
      </table>
    </div>
    <!-- ..................Fin repositorio................... -->

    <!--...................Nuevo posible tesista .......... -->
    <div id="dtab2" class="tab-pane fade" >
      <center><h3> Nuevo Posible Tesista</h3></center>
      <div class="col-md-12" style="border: 1px solid #C0C0FF; padding: 30px">
        <form class="form-horizontal" name="frmnovo" method=post onsubmit="sndLoad('admin/execNewPosibleTesista',new FormData(frmnovo))">
          <div class="col-md-2">
          </div> 
          <div class="col-md-8">
            <div class="form-group">
              <label class="col-md-3 control-label"> Codigo de Estudiante </label>
              <div class="col-md-8">
                <input name="codigo" type="text
                " class="form-control input-md" value="">
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> DNI </label>
              <div class="col-md-8">
                <input name="dni" type="text" class="form-control input-md" value="">
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Nombres </label>
              <div class="col-md-8">
                <input name="nombres" type="text" class="form-control input-md" value="">
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Apellidos </label>
              <div class="col-md-8">
                <input name="apellidos" type="text" class="form-control input-md" value="">
              </div>
              <div class="col-md-1">
              </div>
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
              <label class="col-md-3 control-label"> Matricula ano </label>
              <div class="col-md-8">
                <input name="matriculasanio" type="text" class="form-control input-md" value="">
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label"> Matricula periodo </label>
              <div class="col-md-8">
                <input name="matriculaperiodo" type="text" class="form-control input-md" value="">
              </div>
              <div class="col-md-1">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-5 control-label"></label>
              <div class="col-md-3">
                  <input type="submit" class="btn btn-success col-xs-12" value="Nuevo Posible Tesista">
                </div>
                <div class="col-md-3">
                  <a href="#dtab1" data-toggle="tab" class="btn btn-danger btn-block">Atras <i class="glyphicon glyphicon-repeat"></i></a>
                </div>                
                <div class="col-md-1">                  
                </div>
            </div>
          </div>   
          <div class="col-md-2">
          </div>      
        </form>
      </div>         
  </div>





    <!-- ................ Fin ......................--> 

  