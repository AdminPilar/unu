<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading"><?php echo "$carrera"?>
  </div>
  <div class="panel-body">
  </div>

  <!-- Table -->

<?php  if($proyectos->num_rows()!=0){?>
<table class="table"> 
	<thead><tr> 
	<th>N°</th>
	<th>Carrera</th>
	<th width="40%">Titulo</th> 
	<th>Estado</th>
	<th>Jurados</th>
	<th>Last Time</th> 
	</tr></thead> 
<tbody> 
<?php
	$i=1;
	
	foreach($proyectos->result() as $row){
		
		$name=$this->dbRepo->inCarrera($row->IdCarrera);
		if ($row->Estado>9) {
			$jurados="	P :".$this->dbRepo->inDocente($row->IdJurado1)."<br>
					J1:".$this->dbRepo->inDocente($row->IdJurado2)."<br>
					J2:".$this->dbRepo->inDocente($row->IdJurado3)."<br>
					D :".$this->dbRepo->inDocente($row->IdJurado4)."<br>";
		}else{
			$jurados="Información Anónima";
		}
		echo "
			<tr> 
				<th scope='row'>$i</th>
				<th>".$name."</th> 
				<td style='font-size:12px;'>".$this->dbPilar->inTitulo("$row->Id")."($row->Id)</td>
				<td>".$row->Estado."</td>
				<td style='font-size:9px;'>".$jurados."</td>
				<td>".$row->FechModif."</td> 
			</tr> 
		";
		$i++;
	}
	}else{
		echo "<h2><center><b><span class='text-warning'>No se tiene Información para esta consulta</span></b></center></h2>";
	}
 ?>
</tbody> 
</table>

</div>