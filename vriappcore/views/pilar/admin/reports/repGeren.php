<div class="col-md-2">
</div>
<div class="col-md-8" class="workspace">
<h4>Reportes de Administración</h4>
<table class="table">
    <thead>
      <tr>
        <th>Id</th>
        <th>Tipo de Reporte</th>
        <th>Opción</th>
      </tr>
    </thead>
	<tbody>
		<!-- Reporte general PILAR  -->
		<tr>
			<td>0.1</td>
			<td>Reporte Sustentaciones por carrera </td>

			<td> 	
					<ul>
						<?php 
							$carr=$this->dbRepo->getSnapView('dicCarreras'); 
							foreach ($carr->result() as $row) {
								$url1 = "<a href='http://vriunap.pe/pilar/reports/SustentxLineaxCarrera/$row->Id' class='btn btn-success btn-xs'  target='_blank'>IR</a>";
								echo "<li>$row->Nombre : $url1</li>";
							}
						?>
						
						
					</ul>

			</td>
		</tr>
		
		<!-- Reporte general PILAR  -->
		<tr>
			<td>1</td>
			<td>Reporte General PILAR </td>
			<td><a href="http://vriunap.pe/pilar/reports/repoGenpilar" class='btn btn-success btn-xs' target="_blank">Ver</a></td>
		</tr>

		<!-- Reporte Lineas  PILAR  -->
		<tr>
			<td>2</td>
			<td>Reporte Tesis por Lineas (2016) </td>
			<td><a href="http://vriunap.pe/pilar/reports/ReporteLienasPy2016" class='btn btn-success btn-xs' target="_blank">Ver</a></td>

		</tr>

		<!-- Reporte Lineas  PILAR  -->
		<tr>
			<td>3</td>
			<td>Reporte Tesis por Sub - Lineas (2017) </td>
			<td><a href="http://vriunap.pe/pilar/reports/ReporteLienasPy2017" class='btn btn-success btn-xs' target="_blank">Ver</a>
			<a href="http://vriunap.pe/pilar/reports/ReporteLienasPyweb" class='btn btn-warning btn-xs' target="_blank">Ver en Web</a></td>
		</tr>

		<!-- Reporte Áreas OCDE  PILAR  -->
		<tr>
			<td>4</td>
			<td>Reporte Tesis Áreas OCDE (2017) </td>
			<td><a href="http://vriunap.pe/pilar/reports/ReportOCDEweb" class='btn btn-success btn-xs' target="_blank">Ver Web</a></td>
		</tr>

		<!-- Reporte Áreas OCDE  PILAR  -->
		<tr>
			<td>5</td>
			<td>Tesis por Líneas de Investigación UNAP 2017</td>
			<td><a href="http://vriunap.pe/pilar/reports/LineasUNAP" class='btn btn-success btn-xs' target="_blank">Ver Web</a></td>
		</tr>


		<!-- Reporte Áreas OCDE  PILAR  -->
		<tr>
			<td>6</td>
			<td>Número de Proyectos de Tesis por Escuelas Profesionales 2017</td>
			<td><a href="http://vriunap.pe/pilar/reports/ReportePILARCarreras" class='btn btn-success btn-xs' target="_blank">Ver Web</a></td>
		</tr>


		<!-- Reporte  FEDU PILAR  -->
		<tr>
			<td>6</td>
			<td>Número de Proyectos de Proyectos FEDU 2016 - 2017</td>
			<td><a href="http://vriunap.pe/pilar/reports/FEDUweb" class='btn btn-success btn-xs' target="_blank">Ver Web</a></td>
		</tr>
		

		<!-- Reporte  FEDU PILAR  -->
		<tr>
			<td>6</td>
			<td>Número de Proyectos de Proyectos FEDU  REGINAS 2016 - 2017</td>
			<td><a href="http://vriunap.pe/pilar/reports/FEDUReginas" class='btn btn-success btn-xs' target="_blank">Ver Web</a></td>
		</tr>
		
    </tbody>
  </table>
  </div>
</div>