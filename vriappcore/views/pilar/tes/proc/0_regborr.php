<div id="loadPy">
<div class="page-header">
	<h4 class="titulo"> Borrador de Tesis </h4>
</div>

<div class="contenido">

    <?PHP
        //-----agregado unuv2.0-------
        $TipoMiembro = array (
        "Presidente",
        "Primer Miembro",
        "Segundo Miembro",
        "Asesor"
    );
        //------fin agregado unuv2.0------
        echo "<b>Revisión de Composición de Jurado :</b>";
        echo "<ul>";
        $count=0;
        for ($i=1; $i <=4 ; $i++) { 
        	if($doc[$i]){
       			$status=($doc[$i]->Activo >= 5)?"(Docente Habilitado)":"(Necesita Cambio)";
       			$kind=($doc[$i]->Activo >= 5)?"success":"danger";
       			$count=($doc[$i]->Activo>=5)?$count:$count+1;
       			echo "<li class='text-$kind'> $status |  ".$TipoMiembro[$i-1] ." | ".$doc[$i]->DatosPers."  </li>";
        	} 
        }

        echo "</ul>";
        echo "<div class='alert alert-warning'>";
        echo "<span class='glyphicon glyphicon-exclamation-sign'></span> <b>Aviso</b> : Antes de subir tu <b>Borrador de Tesis</b> verifica que en la conformación de Jurado todos estén <b class='text-success'>habilitados</b>. De lo contrario solicita el <b class='text-danger'>cambio</b> para completar el proceso.";
        echo "</div>";
        // exit;
    ?>

	<p>
       Le recordamos que el borrador debe de cumplir con el formato establecido y el asesor de tesis deberá haber revisado previamente el <b>borrador de tesis</b> asi evitar el rechazo del mismo. De lo contrario podría verser perjudicado en el proceso.
    </p>
    <hr>
    	<?php 
    		if($count==0){
    			?>

					<div class="col-md-11 btn-select">
						<button class="btn btn-default indi_group bg-1" onclick="cargaBorr()">
				            <span class="glyphicon glyphicon-upload" ></span>
				            Subir Mi Borrador de Tesis
				        </button>
					</div>

    			<?php
    		}else{
    			echo "<div class='alert alert-danger'>";
		        echo "<span class='glyphicon glyphicon-exclamation-sign'></span> <b>Aviso</b> : Debe cambiar algunos jurados antes de continuar, póngase en contacto con la unidad de investigación de su facultad respectiva.";
		        echo "</div>";


    		}
    	 ?>

<hr>

</div>
</div>

