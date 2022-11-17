
// #
// # VRI - Universidad Nacional de Ucayali - PUCALLPA 2020
// # Ing. Ramiro Pedro, Ing. Fred Torres, Ing. Julio Tisnado
// #

function showHidden(est){
	for (var i = 1; i < est; i++) {
		$('#est'+i).show();
	}
	$('#textdown').html("<a href='#'onclick='hiddenElem("+est+")'><p> Ocultar Pasos yá realizados</p><span class='glyphicon glyphicon-chevron-up'></span></a>");
}

function hiddenElem(esti){
	for (var i = 1; i < esti; i++) {
		$('#est'+i).hide();
	}
	$('#textdown').html("<a href='#'onclick='showHidden("+esti+")'><p> Mostrar Estados yá realizados</p><span class='glyphicon glyphicon-chevron-down'></span></a>");
}


// main info inicio hide- show
//
function lodPanel(id,ctrl)
{
    jVRI("#"+id).html("cargando...");
	jVRI("#"+id).load(ctrl);
    initProyPrec();
}

function initProyPrec()
{
	// activamos el pre-revisor del PDF
	jVRI("#nomarch").change( function(e) {

		var grup = e.target.files;
		var file = grup[0];
		if( file.type!="application/pdf" || file.size>(10485760) ){
			jVRI("#nomarch").val("");
			jVRI("#filemsg").html( "No cumple con ser (PDF) de menos de 10MB");

			$("#nomarch").addClass("btn-danger");
			$("#nomarch").removeClass("btn-success");
		} else {
			$("#filemsg").html( "Archivo correcto (PDF) de menos de 10MB");
			$("#nomarch").addClass("btn-success");
			$("#nomarch").removeClass("btn-danger");
		}
	});
}

function initProyPrec2()
{
    // activamos el pre-revisor del PDF
    jVRI("#nomarch").change( function(e) {

        var grup = e.target.files;
        var file = grup[0];
        if( file.type!="application/pdf" || file.size>(1048576) ){
            jVRI("#nomarch").val("");
            jVRI("#filemsg").html( "No cumple con ser (PDF) de menos de 1MB");

            $("#nomarch").addClass("btn-danger");
            $("#nomarch").removeClass("btn-success");
        } else {
            $("#filemsg").html( "Archivo correcto (PDF) de menos de 1MB");
            $("#nomarch").addClass("btn-success");
            $("#nomarch").removeClass("btn-danger");
        }
    });
}

function initProyPrec3()
{
    // activamos el pre-revisor del PDF
    jVRI("#nomarch").change( function(e) {

        var grup = e.target.files;
        var file = grup[0];
        if( file.type!="application/pdf" || file.size>(2048000) ){
            jVRI("#nomarch").val("");
            jVRI("#filemsg").html( "No cumple con ser (PDF) de menos de 2MB");

            $("#nomarch").addClass("btn-danger");
            $("#nomarch").removeClass("btn-success");
        } else {
            $("#filemsg").html( "Archivo correcto (PDF) de menos de 2MB");
            $("#nomarch").addClass("btn-success");
            $("#nomarch").removeClass("btn-danger");
        }
    });
}

function lodShifs( id )
{

	if( id == 1 ) {
		$("#blq1").show();
		$("#blq2").hide();
	} 
    else if(id==2) {
        
		//$("#blq1").hide();
        $('#msgPosterX').modal({backdrop: 'static', keyboard: false});        
		//$("#blq2").show();  
        document.getElementById('total').style.display='none';
        document.getElementById('Final').style.display='block'; 
        document.getElementById('nomarch').value=''; 
        document.getElementById('resumen').value='';
        document.getElementById('pclaves').value='';
        document.getElementById('conclus').value='';        
        initProyPrec();

		// activamos el pre-revisor del PDF		
	}
    else if(id==21) {
        
        //$("#blq1").hide();       
        $("#blq1").hide();
        $("#blq2").show();       
        initProyPrec();

        // activamos el pre-revisor del PDF     
    }
    else if(id==22) {
        document.getElementById('total').style.display='none';
        document.getElementById('Final').style.display='none'; 
        document.getElementById('verifica').style.display='block';
        
        //alert('hola') ;
       
        // activamos el pre-revisor del PDF     
    }
    else if(id==3)
    {
        document.getElementById('total').style.display='none';
        document.getElementById('Final').style.display='block';   
        initProyPrec();
    }
    else if(id==4) //para volver a inicio
    {
         document.getElementById('total').style.display='block';
         document.getElementById('Final').style.display='none'; 
         document.getElementById('verifica').style.display='none'; 
          document.getElementById('nomarch').value=''; 
        document.getElementById('resumen').value='';
        document.getElementById('pclaves').value='';
    }
    else
    {
        document.getElementById('total').style.display='none';
        document.getElementById('Final').style.display='block'; 
        initProyPrec2();

    }
}
function grabaCorrBorr(id)
{
    $("#plock").show();

    if(id==1)
    {
        jVRI.ajax({
        url : "tesistas/execInCorrBorr",
        data : new FormData(frmborr),
        success : function( arg )
        {
            $("#plock").hide();
            $('#Final').html(arg);
        }
        });
    }
    else
    {      
        
        jVRI.ajax({
        url : "tesistas/execInCorrBorr",
        data : new FormData(frmborr),
        success : function( arg )
        {
            $("#plock").hide();
            $('#Final').html(arg);
        }
        });
    }

   


    /*jVRI.ajax({
        url : "tesistas/execInCorrBorr",
        data : new FormData(frmborr),
        success : function( arg )
        {
            $("#plock").hide();
            document.getElementById('total').style.display='block';
            document.getElementById('Final').style.display='none'; 
            $('#total').html(arg);
        }
    });*/
}



// carga previa de borrador
//
function cargaBorr()
{
    jVRI.ajax({
        url : "tesistas/loadRegBorr",
        success : function( arg ){
            jVRI('#loadPy').html( arg );
            //--------------------------------------------------           

            jVRI("#cotiarch").change( function(e)
            {
                var grup = e.target.files;
                var file = grup[0];
                if( file.type!="application/pdf" || file.size>(1048576) ){
                    jVRI("#cotiarch").val("");
                    jVRI("#cotimsg").html( "No cumple con ser (PDF) de menos de 1MB");

                    $("#cotiarch").addClass("btn-danger");
                    $("#cotiarch").removeClass("btn-success");
                } else {
                    $("#cotimsg").html( "Archivo correcto (PDF) de menos de 1MB");
                    $("#cotiarch").addClass("btn-success");
                    $("#cotiarch").removeClass("btn-danger");
                }
            });

            jVRI("#nomarch").change( function(e)
            {
                var grup = e.target.files;
                var file = grup[0];
                if( file.type!="application/pdf" || file.size>(10485760) ){
                    jVRI("#nomarch").val("");
                    jVRI("#filemsg").html( "No cumple con ser (PDF) de menos de 10MB");

                    $("#nomarch").addClass("btn-danger");
                    $("#nomarch").removeClass("btn-success");
                } else {
                    $("#filemsg").html( "Archivo correcto (PDF) de menos de 10MB");
                    $("#nomarch").addClass("btn-success");
                    $("#nomarch").removeClass("btn-danger");
                }
            });
            jVRI("#anexarch").change( function(e)
            {
                var grup = e.target.files;
                var file = grup[0];
                if( file.type!="application/pdf" || file.size>(10485760) ){
                    jVRI("#anexarch").val("");
                    jVRI("#anexmsg").html( "No cumple con ser (PDF) de menos de 10MB");

                    $("#anexarch").addClass("btn-danger");
                    $("#anexarch").removeClass("btn-success");
                } else {
                    $("#anexmsg").html( "Archivo correcto (PDF) de menos de 10MB");
                    $("#anexarch").addClass("btn-success");
                    $("#anexarch").removeClass("btn-danger");
                }
            });


            
            //---------------------------------------------------
        }
    });
}

// enviar pdf correcs
//unuv1.0 - estado revision 1
//unuv1.0 - estado revision 2
//unuv1.0 - estado revision 3
function grabaCorr()
{
    $("#plock").show();

    jVRI.ajax({
        url : "tesistas/execInCorr",
        data : new FormData(frmborr),
        success : function( arg )
        {
            $("#plock").hide();
            $('#bodive').html(arg);
        }
    });
}

function grabaBorr()
{
    $("#plock").show();
     var IdFacultad = $("#facultadId").val();
     jVRI("#facultadId").val("10");
    var file = document.getElementById("anexarch");
    
            jVRI.ajax({
                url : "tesistas/SubirCoti",
                data : new FormData(frmborr),
                success : function( arg )
                {   
                    jVRI("#nomcoti").val(arg);
                    if(file.files.length == 0 ){ //agregado unuv2.0
                        jVRI.ajax({
                                url : "tesistas/execInBorr",
                                data : new FormData(frmborr),
                                success : function( arg )
                                {
                                    $("#plock").hide();
                                    $('#plops').html(arg);                          

                                }
                            });  
                    } else {
                       jVRI.ajax({

                                url : "tesistas/SubirAnexos",
                                data : new FormData(frmborr),
                                success : function( arg )
                                {
                                    jVRI("#nomanexo").val(arg);
                                    jVRI.ajax({
                                        url : "tesistas/execInBorr",
                                        data : new FormData(frmborr),
                                        success : function( arg )
                                        {
                                            $("#plock").hide();
                                            $('#plops').html(arg);                          

                                        }
                                    });                        

                                }
                            });
                    }
                }
            });
        
}

// cargar proyecto discriminado arg
// modificado unuv1 --(3.8.1)
// modificado unuv1 --(3.9.1)
function cargaProy(modo)
{
    var txt;
    if(modo==2)
    {
        
        var codex = prompt("Ingrese el Código de su compañero", "");
        if (codex == null || codex == "") {
            setTimeout(document.getElementById('mos').style.display='block',5000);
            if(codex == null)
            {
                txt='';
            }
            else
            {
                txt='Ingrese Codigo de su compañero'
            }

             document.getElementById("demo").innerHTML = txt;
        }        
        else
        {
            jVRI.ajax({
                url : "tesistas/ValidarCodigo/"+ codex, //(3.9.2)
                success : function( arg ){
                    if (arg.trim()=='') 
                    {
                        jVRI.ajax({
                             url : "tesistas/loadRegProy/" + codex, //(3.9.3)
                            success : function( arg ){
                                jVRI('#loadPy').html( arg );
                                initProyPrec3();
                            }
                        });                        
                    }
                    else
                    {         
                    setTimeout(document.getElementById('mos').style.display='block',5000);               
                        jVRI('#demo').html( arg );                         
                    }
                }
            });  
        }
    }
    else
    {
        jVRI.ajax({
            url : "tesistas/loadRegProy/" + codex,
            success : function( arg ){
                jVRI('#loadPy').html( arg );
                initProyPrec3();
        }
    });
    }
    /*codex = (modo == 2)? prompt("Ingrese el Código de su compañero","") : "" ;

    jVRI.ajax({
        url : "tesistas/loadRegProy/" + codex,
        success : function( arg ){
            jVRI('#loadPy').html( arg );
			initProyPrec();
        }
    }); Modificado unuv1.0*/
}

//modificado unuv1 --(3.8.5)
//modificado unuv1 --(3.9.4)
function grabaProy()
{
    $("#plock").show();

    jVRI.ajax({
        url : "tesistas/execInProy",
        data : new FormData(frmproy),
        success : function( arg )
        {
            $("#plock").hide();
            $('#plops').html(arg);
        }
    });
}

function subBatch(){
    $("#plock").show();
    jVRI.ajax({
        url:"tesistas/execInBachi",
        data: new FormData(frmbach),
        success : function( arg ){
            $("#plock").hide();
            $("#plops").html(arg);
        }
    });
}


function solSusten(){
    $("#plock").show();
    jVRI.ajax({
        url:"tesistas/execSolSusten",
        data: new FormData(frmbach),
        success : function( arg ){
            $("#plock").hide();
            $("#plops").html(arg);
        }
    });
}

//modificado unuv1 --(3.8.3)
function cargaDocEnLin()
{
    jVRI( "#j4" ).load( "tesistas/loadLinCbo/4/" + cbolin.value );
}

function prueba()
{
    alert('hola mjundkcn');
}


///jVRI( "#j3" ).load( "tesistas/loadLinCbo/3/" + cbolin.value );
/*
function tesRevIgu()
{
    if( jVRI("#j4").val() == jVRI("#j3").val() )
        jVRI("#j3").val( 0 );
}
*/

// EOF