<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script type="text/javascript">


</script>
<div class="col-md-12" 	>
<center><img class='img-responsive' width="260px" src="http://vriunap.pe/vriadds/vri/web/convocatorias/curso1-3mt.jpg"></center>
<?php
	$mensajeNo="<h3 class='text-center text-danger'>UPSS...Lo sentimos! <br>Usted no aplica al concurso.! <br> <small>No tiene proyecto registrado o su director de tesis aun no acepto su proyecto.</small></h3>";
// Existe el Trámite?
	$tram=$this->dbPilar->inTramByTesista($sess->userId);
	if($tram){
		if ($tram->Estado>2 OR $tram->Estado<13) {
			$postuli=$this->dbPilar->getSnaprow("3mtPostul","IdTesista=$sess->userId");
			if(!$postuli){
?>	
      <div id="plock" style="display: none; z-index: 1000; position: fixed; left: 0; top: 0; width:100%; height:100%; padding: 300px; background: rgba(0,0,0,0.5)">
          <div style="margin: 0 auto; width: 320px; height: 80px; background: white; padding: 15px">
            <center> <b> Enviando datos, espere ... </b> </center>
            <div class="progress progress-striped active" style="margin-bottom:0;">
            <div class="progress-bar" style="width: 100%"></div></div>
          </div>
      </div>
      <div id='plops'>
		<form class='form-horizontal' name ='form3mt' id ='form3mt' method="POST" onsubmit="graba3MT(); return false" accept-charset="utf-8" enctype="multipart/form-data">
		<fieldset>
		<!-- Form Name -->
		<legend> <span class="glyphicon glyphicon-book"></span> Sign up here</legend>

		<!-- Tesista -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Student/Bachelor:</label>  
		  <div class="col-md-5">
		  <input id="tessita" name="tesista" type="text" placeholder="Tesista Name" class="form-control input-md" value="<?php echo $this->dbPilar->inTesista($sess->userId);?>" disabled="">
		  </div>
		</div>

		<!-- Titulo -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Title:</label>  
		  <div class="col-md-8">
		  <textarea id="titulo" name="titulo" type="text" class="form-control input-md text-justify" rows="4" required=""><?php echo $this->dbPilar->inTitulo($tram->Id);?></textarea>
		  <span class="help-block pull-right">Translate the title please.</span>
		  </div>
		</div>
		<!-- Resumen -->
		<div class="form-group">
		  	<label class="col-md-3 control-label" for="textinput">Abstract:</label>  
		  	<div class="col-md-8">
		  		<textarea name="resumen" id="resumen" class="form-control input-md text-justify"  rows="10" placeholder=' Escriba aqui'onkeyup="contarwords()" required=""> </textarea>
				 <span class="help-block pull-right">The abstract should be in English. (<small>Total words count: <span id="display_count">0</span> words.  Words left:<b> <span id="word_left" style="font-size: 16px;" class="text-danger">300</span>)</b></small></span> 
			</div>
		</div>
		<div class="form-group success">
			<label class="col-md-3 control-label"> Cargar Diapositiva (1):</label>
			<div class="col-md-8">
				<input name="nomarch" id="nomarch" type="file" class="file form-control input-md" required=''>
				<span id="filemsg" class="help-block"> <center>Solo se aceptan diapositivas en formato PPT. (Una sola Diapostiva)</center> </small></span>
			</div>
		</div>
<!-- 		<div class="form-group success">
			<label class="col-md-3 control-label"> Cargar mi Foto:</label>
			<div class="col-md-8">
				<input name="nomphot" id="nomphot" type="file" class="file form-control input-md" required=''>
				<span id="filemsg" class="help-block"> <center>Don't forget upload a single slide.</center> </small></span>
			</div>
		</div> -->
		<!-- Button (Double) -->
		<div class="form-group">
		  <div class="col-md-4"></div>
		  <div class="col-md-5">
		      <button type="submit" class="btn btn-info col-xs-12">
		          <span class="glyphicon glyphicon-save"></span> &nbsp; Submit 
		      </button>
		  </div>
		</div>
		</fieldset>
	</form>
	</div>
<?php
		}else{
			echo "<center><h2>POSTULACIÓN CORRECTA.<h2></center> ";
			echo "<p style='font-size:18px;' ><b>CODIGO</b>: $postuli->Codigo</p>";
			echo "<p style='font-size:14px;' ><b>FECHA</b> :$postuli->Fecha</p>";
			echo "<p style='font-size:15px;' ><b>TITLE</b><br> $postuli->Titulo</p>";
			echo "<p style='font-size:14px;' class='text-justify' ><b>ABSTRACT</b> <br>$postuli->Resumen</p>";
			echo "<p style='font-size:14px;' ><b>FILE  </b><br><a  class='btn btn-info btn-md' href='http://vriunap.pe/repositor/tesis3m/$postuli->Archivo'> Download File </a>  </p>";
		}		
	}else{	
			echo $mensajeNo;
		}
	}else{
		echo $mensajeNo;
	}
 ?>
 </div>

