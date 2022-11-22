// #
// # VRI - Universidad Nacional de Ucayali - PUCALLPA 2020
// # Ing. Ramiro Pedro, Ing. Fred Torres, Ing. Julio Tisnado
// #

function showHidden(est){
	for (var i = 1; i < est; i++) {
		$('#est'+i).show();
	}
	$('#textdown').html("<a href='#'onclick='hiddenElem("+est+")'><p> Ocultar Pasos ya realizados</p><span class='glyphicon glyphicon-chevron-up'></span></a>");
}
function hiddenElem(esti){
	for (var i = 1; i < esti; i++) {
		$('#est'+i).hide();
	}
	$('#textdown').html("<a href='#'onclick='showHidden("+esti+")'><span class='glyphicon glyphicon-chevron-down'></span></a>");
}
// main info inicio hide- show

function lodPanel(id,ctrl)
{
	jVRI("#"+id).load(ctrl);
}

// LoadPY cargar proyecto
function selectGroup(kind){
	if (kind==2) {
		$('#twiceModal').modal('show');
		// jVRI('#loadPy').load("tesistas/jsCargaPy/2");
	}else{
		jVRI('#loadPy').load("tesistas/jsCargaPy/1");
	}	
}

function contarwords() {

    $("#resumen").on('keyup', function() {
        var words = this.value.match(/\S+/g).length;
        if (words > 300) {
          
            var trimmed = $(this).val().split(/\s+/, 300).join(" ");
            // Add a space at the end to keep new typing making new words
            $(this).val(trimmed + " ");
        }
        else {
            $('#display_count').text(words);
            $('#word_left').text(300-words);
        }
    });
}

function graba3MT()
{
    $("#plock").show();

    jVRI.ajax({
        url : "tesistas/execPostula3MT/",
        data : new FormData(form3mt),
        success : function( arg )
        {
            $("#plock").hide();
            $('#plops').html(arg);
        }
    });
}

function grabaPoster()
{
    $("#plock").show();

    jVRI.ajax({
        url : "tesistas/execPostulaPoster/",
        data : new FormData(formPoster),
        success : function( arg )
        {
            $("#plock").hide();
            $('#plops').html(arg);
        }
    });
}