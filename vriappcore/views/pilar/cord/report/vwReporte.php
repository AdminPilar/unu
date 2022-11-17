
 <div class="container-fluid">
  <h3>Reportes la Escuela Profesional:: <small><?php  echo $Carrera; ?></small></h3>
  <div class="panel">
    <table class="table">
    <thead>
      <tr>
        <th>Id</th>
        <th>Tipo de Reporte</th>
        <th>Opción</th>
      </tr>
    </thead>
  <tbody> 
    <tr>
      <td>1</td>
      <td>Estado Actual de la Carrera  </td>
      <td><a href="<?=base_url("pilar/cordinads/EstadoActualPilar")?>" class='btn btn-success btn-xs' target="_blank">Ver</a></td>
    </tr>
    <tr>
      <td>2</td>
      <td>Evaluacion de Docente </td>
      <td><a href="javascript:void(0)"  onclick="lodPanel('panelCord','cordinads/vwReportesev')" class='btn btn-success btn-xs' >Ver</a></td>
    </tr>
    
    </tbody>
  </table>
    
  </div>
</div>
