// #
// # VRI - Universidad Nacional de Ucayali - PUCALLPA 2020
// # Ing. Ramiro Pedro, Ing. Fred Torres, Ing. Julio Tisnado
// #

function lodPanel(id,ctrl)
{
	jVRI("#"+id).load(ctrl);
}
function jsCarrer(value){
	jVRI("#panelCarrer").load("cordinads/setCarrera/"+value);
}
function jsLoadModalCord(val,ctrl){
	jVRI("#mdlContCord").html();
	$("#cordModal").modal("show");
	jVRI("#mdlContCord").load(ctrl+val);
}
function jsCordDocInfo(val){
	jVRI("#mdlContCord").html(" ");
	$("#cordModal").modal("show");
	jVRI("#mdlContCord").load("cordinads/jsmdlDocInfo/"+val);
}
function jsCordDocOpt(val){
	jVRI("#mdlContCord").html(" ");
	$("#cordModal").modal("show");
	jVRI("#mdlContCord").load("cordinads/jsmdlDocOpc/"+val);
}
function LoadForm(dond,funti,idis){
	// alert(idis.name);
	jVRI("#"+dond).load(funti, new FormData(idis));
}

function jsLoadConfirmLin(val){
	jVRI("#mdlContCord").html(" ");
	$("#cordModal").modal("show");
	jVRI("#mdlContCord").load("cordinads/vwMdlUpdateLin/"+val);
}


function jsConfirmlLinea(val){
	$("#cordModal").modal("show");
    jVRI("#estado"+val).html( "Grabando...");
    jVRI.ajax({
        url  : "cordinads/execUpdateLin/"+val,
        data :  new FormData(porquee),
        success: function( arg )
        {
            $("#cordModal").modal("hide");
            jVRI("#estado"+val).html( arg );
            jVRI("#panelCord").load('cordinads/vwValidaLineas');
        }
    });
}

function pyDirect( itm, tram )
{
	// alert(tram);
    if( confirm('Este proyecto de tesis sera enviado al Asesor, desea continuar?') ) {
    	
    	jVRI("#respcord").load("cordinads/listPyDire/"+tram);

    	// jVRI("#item"+itm).load("cordinads/listPyDire/"+tram);
        $("#item"+itm ).fadeOut(); //hide();
        // jVRI("#panelCord").load( "cordinads/vwProyectos/"+tram );
    }
}

function jsRevalidaLinea(val){
	jVRI("#indecisosisi").load( "cordinads/vwMdlValidaLinea/"+val);
	// $
	// jVRI.ajax({
	// 	url  : "pilar/cordinads/execUpdateLin/"+val+"/"+doc,
	// 	data : new FormData(frmoti),
	// 	success: function( arg )
	// 	{
	// 		jVRI("#pmsg").html( "" );
	// 		jVRI("#pdta").html( arg );
	// 	}
	// }); 
}
//Modificacion unuv1.0 - Estado rechazar proyecto por formato
function cancelar(){
	lodPanel('panelCord','cordinads/vwProyectos');
	/*var estado = document.getElementById("nvo").value;
	if(val<=8)
		{
			lodPanel('panelCord','cordinads/vwProyectos');
		}
		else
		{
			lodPanel('panelCord','cordinads/vwBorradores');
		}*/
		alert(val);
	
}
function cancelarborra(){
	lodPanel('panelCord','cordinads/vwBorradores');
	/*var estado = document.getElementById("nvo").value;
	if(val<=8)
		{
			lodPanel('panelCord','cordinads/vwProyectos');
		}
		else
		{
			lodPanel('panelCord','cordinads/vwBorradores');
		}*/
		alert(val);
	
}

//Modificacion unuv1.0 - Estado rechazar proyecto por formato
function popExeRechaza(val){
	if(document.getElementById('txtporque').value!="")
	{ 	// $("#cordModal").modal("show");
    	// datita = $("#corazon").serialize();
       datita = new FormData(corazon);
		jVRI("#popis").html( "Grabando...");
		$('#modal-btn-si').prop('disabled', true);
		//document.getElementById('modal-btn-si').disabled=true;
		// data.append("webmasterfile", "freed");
		jVRI.ajax({
			url  : "cordinads/doRechaza/"+val,
			data :  datita ,
			success: function( arg )
			{
				// $("#cordModal").modal("hide");
				jVRI("#popis").html( arg );
				// jVRI("#panelCord").load('cordinads/vwValidaLineas');
			}
		});
	 }
	else
	{
		document.getElementById("txtporque").focus();
	}
}

//Agregado unuv1.0 - Estado enviar proyecto al Asesor
function popExeEnviar(val)
{	  
	 datita = new FormData(corazon);
	 	var estado = document.getElementById("nvo").value;
		jVRI("#popis").html( "Grabando...");
		$('#modal-btn-si').prop('disabled', true);
		//document.getElementById('modal-btn-si').disabled=true;
		// data.append("webmasterfile", "freed");
		if(estado<=8){
			jVRI.ajax({
			url  : "cordinads/listPyDire/"+val,
			data :  datita ,
			success: function( arg )
			{
				// $("#cordModal").modal("hide");
				jVRI("#popis").html( arg );
				// jVRI("#panelCord").load('cordinads/vwValidaLineas');
			}
			});	
		}
		else if(estado<=13)
		{
			jVRI.ajax({
			url  : "cordinads/listBrDire/"+val,
			data :  datita ,
			success: function( arg )
			{
				// $("#cordModal").modal("hide");
				jVRI("#popis").html( arg );
				// jVRI("#panelCord").load('cordinads/vwValidaLineas');
			}
			});	
		}
		else
		{
			jVRI.ajax({
			url  : "cordinads/listNotificar/"+val,
			data :  datita ,
			success: function( arg )
			{
				// $("#cordModal").modal("hide");
				jVRI("#popis").html( arg );
				// jVRI("#panelCord").load('cordinads/vwValidaLineas');
			}
			});	
		}
		
}
//agregado unuv1.0
function sndLoad( urlink, args, isin )
{
    if( isin==null ){
        jVRI("#panelCord").html( lodmsg );
        jVRI("#panelCord").load( urlink, args );
    } else {
        jVRI("#panelCord").html( lodmsg );
        jVRI("#panelCord").load( urlink, args );
    }
}

