<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8" async defer>
	function contarwords() {

    $("#resumen").on('keyup', function() {
        var words = this.value.match(/\S+/g).length;
        if (words > 150) {
          
            var trimmed = $(this).val().split(/\s+/, 150).join(" ");
            // Add a space at the end to keep new typing making new words
            $(this).val(trimmed + " ");
        }
        else {
            $('#display_count').text(words);
            $('#word_left').text(150-words);
        }
    });
}
</script>
<div class="col-md-12">
<?php 
	$fred=$this->dbPilar->getSnapRow("_laspau","IdDoc=$sess->userId");
	if ($fred) {
		echo "<center><img src='http://vriunap.pe/vriadds/vri/web/laspau.png' class='img-responsive'> </img>";
		echo "<h2>INSCRIPCIÓN</h2>";
		echo "<h4 class='text-gray-dark'>Su postulación fue registrada con el código : $fred->Cod.</h4></center>";
	}else{
?>
<!-- <center><img class='img-responsive' width="260px" src="http://vriunap.pe/vriadds/vri/web/convocatorias/curso1-3mt.jpg"></center> -->

      <div id='plops'>
		<form class='form-horizontal' name ='form3mt' id ='form3mt' method="POST" onsubmit="grabaLaspau(); return false;" accept-charset="utf-8" enctype="multipart/form-data">
		<fieldset>
		<!-- Form Name -->
		<legend> <span class="glyphicon glyphicon-book"></span> POSTULACIÓN : CONCURSO LASPAU 
		</legend>  

			<?php 
  
				$toti=235-$cuantos;
				echo "<p class='text-right text-danger'>Vacantes Disponibles :$toti</p>";
			?>

<BR><BR>

	 <div id="plock" style="display: none; z-index: 1000; position: fixed; left: 0; top: 0; width:100%; height:100%; padding: 300px; background: rgba(0,0,0,0.5)">
          <div style="margin: 0 auto; width: 320px; height: 80px; background: white; padding: 15px">
            <center> <b> Enviando datos, espere ... </b> </center>
            <div class="progress progress-striped active" style="margin-bottom:0;">
            <div class="progress-bar" style="width: 100%"></div></div>
          </div>
      </div>

		<!-- Tesista -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Apellidos y Nombres:</label>  
		  <div class="col-md-5">
		  <input id="tessita" name="tesista" type="text" placeholder="Tesista Name" class="form-control input-md" value="<?php echo $this->dbRepo->inDocente($sess->userId);?>" disabled="">
		  </div>
		</div>
		
		<!-- Facultad -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Carrera:</label>  
		  <div class="col-md-5">
		  <input id="tessita" name="tesista" type="text" placeholder="Tesista Name" class="form-control input-md" value="<?php echo $dat->Carrera;?>" disabled="">
		  </div>
		</div>

		<!-- Correo -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Correo:</label>  
		  <div class="col-md-5">
		  <input id="tessita" name="tesista" type="text" placeholder="Tesista Name" class="form-control input-md" value="<?php echo $this->dbRepo->inCorreo($sess->userId);?>" disabled="">
		  </div>
		</div>


		<label class="col-md-6 control-label text-left" for="textinput">Curso que ha dictado durante el semestre académico 2018 -I:</label> <br>  
		<label class="col-md-12"></label>

		<div class="form-group">
			<label class="col-md-1 control-label" for="textinput"> -</label>  
			<label class="col-md-3 control-label" for="textinput">Semestre</label>  
			<label class="col-md-3 control-label" for="textinput">Nombre de Curso</label>  
		</div>

		<!-- CURSO  -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Curso N° 1:</label>  
		  <div class="col-md-8">
		  	<div class="col-md-2">
		  		<select name="sem1" class="form-control">
<option value="0">-</option>
		  			<option value="1">1</option>
		  			<option value="2">2</option>
		  			<option value="3">3</option>
		  			<option value="4">4</option>
		  			<option value="5">5</option>
		  			<option value="6">6</option>
		  			<option value="7">7</option>
		  			<option value="8">8</option>
		  			<option value="9">9</option>
		  			<option value="10">10</option>
		  			<option value="11">11</option>
		  			<option value="12">12</option>
		  			
		  		</select>
		  	</div>
		  	<div class='col-md-6'>
				  <input id="cur1" name="cur1" type="text" class="form-control input-md text-justify">
		  	</div>
		  </div>
		</div>
		<!-- CURSO  -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Curso  N° 2:</label>  
		  <div class="col-md-8">
		  	<div class="col-md-2">
		  		<select name="sem2" class="form-control">
		  			<option value="0">-</option>
		  			<option value="1">1</option>
		  			<option value="2">2</option>
		  			<option value="3">3</option>
		  			<option value="4">4</option>
		  			<option value="5">5</option>
		  			<option value="6">6</option>
		  			<option value="7">7</option>
		  			<option value="8">8</option>
		  			<option value="9">9</option>
		  			<option value="10">10</option>
		  			<option value="11">11</option>
		  			<option value="12">12</option>
		  			
		  		</select>
		  	</div>
		  	<div class='col-md-6'>
				  <input id="cur2" name="cur2" type="text" class="form-control input-md text-justify">
		  	</div>
		  </div>
		</div>
		<!-- CURSO  -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Curso III :</label>  
		  <div class="col-md-8">
		  	<div class="col-md-2">
		  		<select name="sem3" class="form-control">
		  			<option value="0">-</option>
		  			<option value="1">1</option>
		  			<option value="2">2</option>
		  			<option value="3">3</option>
		  			<option value="4">4</option>
		  			<option value="5">5</option>
		  			<option value="6">6</option>
		  			<option value="7">7</option>
		  			<option value="8">8</option>
		  			<option value="9">9</option>
		  			<option value="10">10</option>
		  			<option value="11">11</option>
		  			<option value="12">12</option>
		  			
		  		</select>
		  	</div>
		  	<div class='col-md-6'>
				  <input id="cur3" name="cur3" type="text" class="form-control input-md text-justify" rows="4" >
		  	</div>
		  </div>
		</div>
		<!-- CURSO  -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Curso IV :</label>  
		  <div class="col-md-8">
		  	<div class="col-md-2">
		  		<select name="sem4" class="form-control">
		  			<option value="0">-</option>
		  			<option value="1">1</option>
		  			<option value="2">2</option>
		  			<option value="3">3</option>
		  			<option value="4">4</option>
		  			<option value="5">5</option>
		  			<option value="6">6</option>
		  			<option value="7">7</option>
		  			<option value="8">8</option>
		  			<option value="9">9</option>
		  			<option value="10">10</option>
		  			<option value="11">11</option>
		  			<option value="12">12</option>
		  			
		  		</select>
		  	</div>
		  	<div class='col-md-6'>
				  <input id="cur4" name="cur4" type="text" class="form-control input-md text-justify" rows="4" >
		  	</div>
		  </div>
		</div>

		<!-- Titulo -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Titulo Profesional:</label>  
		  <div class="col-md-8">
		  <textarea id="titulod" name="titulod" type="text" class="form-control input-md text-justify" rows="1" required=""></textarea>
		  </div>
		</div>

				<!-- Titulo -->
		<div class="form-group">
		  <label class="col-md-3 control-label" for="textinput">Max. Grado Académico:</label>  
		  <div class="col-md-8">
		  <textarea id="grad" name="grad" type="text" class="form-control input-md text-justify" rows="1" required=""></textarea>
		  </div>
		</div>



		<!-- Resumen -->
		<div class="form-group">
		  	<label class="col-md-3 control-label" for="textinput">Expresar de forma concreta un aspecto que desee mejorar en su enseñanza(Máximo 150 Palabras):</label>  
		  	<div class="col-md-8">
		  		<textarea name="resumen" id="resumen" class="form-control input-md text-justify"  rows="10" placeholder=' Escriba aqui' onkeyup="contarwords()" required=""> </textarea>
				 <span class="help-block pull-right">(<small>Total de palabras: <span id="display_count">0</span> palabras.  Palabras Restantes:<b> <span id="word_left" style="font-size: 16px;" class="text-danger">150</span>)</b></small></span> 
			</div>
		</div>

		<div class="form-group success">
			<label class="col-md-3 control-label"> Adjuntar una carta donde exponga las razones para participar en el programa:</label>
			<div class="col-md-8">
				<input name="nomarch" id="nomarch" type="file" class="file form-control input-md" required=''>
				<span id="filemsg" class="help-block"> <center>Solo se aceptan documentos en formato PDF.</center> </small></span>
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
		          <span class="glyphicon glyphicon-save"></span> &nbsp; ENVIAR POSTULACIÓN 
		      </button>
		  </div>
		</div>
		</fieldset>
	</form>
	</div>
<?php } ?>
 </div>

