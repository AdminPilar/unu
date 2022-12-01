//---------------------------------------------------------------
//
//  Ajax Events - codename [boobieMovie]
//
//  * Developed by
//       : M.Sc. Ramiro Pedro Laura Murillo
//       : Ing. Fred Torres Cruz
//       : Ing. Julio Cesar Tisnado Puma
//
//---------------------------------------------------------------

var item = 0;
var lodmsg = "&nbsp;&nbsp;&nbsp;<b><small>"
           + "Cargando html...</small></b>";
// $$dom

function lodPanel( urlink )
{
	//jVRI("#panelView").load( urlink );
    $("#panelView").html( lodmsg );
    $("#panelView").load( urlink );
  }


//unuv1.0 - estado sorteo de jurado
function popProcede( urlink, arg )
{
  

	jVRI("#vwCorrs").load( urlink, arg );    
    jVRI("#vwCorrs").html( "¡...Procesando...... por favor espere!"); 
    $("#popOk").prop( "disabled", true );
     $("#notas").hide();
    $("#nr"+item).fadeOut();
}

function popLoad( urlink, itm )
{
    
    item = itm;
    jVRI("#vwCorrs").html( lodmsg );
	jVRI("#vwCorrs").load( urlink );
    $("#popOk").prop("disabled",false);
	$('#dlgPan').modal({
                backdrop: 'static',
                keyboard: false
            }) //agregado unuv2.0
}

function listDocRepo()
{
    jVRI("#tblist").load( "admin/listDocRepo", new FormData(frmbusq) );
}


//-------------------------------------------------------------------------------
function borDirect( itm, tram )
{
    if( confirm('Este Borrador sera enviado a Revisión, desea continuar?') ) {
        jVRI("#ixp").load( "admin/listBrDire/"+tram );
        $("#nr"+itm ).fadeOut(); //hide();
    }
}

function pyDirect( itm, tram )
{
    if( confirm('Esta Item sera enviado al Asesor, desea continuar?') ) {
        jVRI("#ixp").load( "admin/listPyDire/"+tram );
        $("#nr"+itm ).fadeOut(); //hide();
    }
}

function pyRetorna( itm, tram )
{
    if( confirm('Esta Item no cumple con el formato, Rechazar?') ) {
        jVRI("#ixp").load( "admin/listPyBorr/"+tram );
        $("#nr"+itm ).fadeOut(); //hide();
    }
}

// temporal.
function fillItms()
{
    nesta.value     = 6;
    cambest.checked = true;
    fechaCon.value  = "2018-04-09";
    resolCon.value  = "R.R. Nro 0000-2018-R-UNA";
    docu.value = "R.R. Nro 0000-2018-R-UNA";
    desc.value = "Ingreso a Plataforma, Por contrato Docente 2018-I";
}

function listCboCarrs()
{
    jVRI("#carre").html( "" );
    jVRI("#carre").load( "admin/listCboCarrs/" + facul.value );
}

function listDocGrad()
{
    dni = $("#dnx").val();

    $("#dlg").modal();
    $("#doc").val( dni? dni : "" );
    $("#tres").empty();
}

function listDocRepo()
{
    jVRI("#tblist").load( "admin/listDocRepo", new FormData(frmbusq) );
}

function sndLoad( urlink, args, isin )
{
    if( isin==null ){
        jVRI("#panelView").html( lodmsg );
	    jVRI("#panelView").load( urlink, args );
    } else {
        jVRI("#panelBar").html( lodmsg );
        jVRI("#panelBar").load( urlink, args );
    }
}

function actiTram()
{
    // datos son internos por seguridad
    //
    jVRI("#panelBar").load( "admin/lisActTram" );
}

function repoLoad( iddiv, urlink, args )
{
    jVRI("#"+iddiv).html( "Consultando..." );
	jVRI("#"+iddiv).load( urlink, args );
    return false;
}

function repoLoads( iddiv, urlink, args )
{
    jVRI("#"+iddiv).html( "Consultando..." );
	jVRI("#"+iddiv).load( urlink, args );
    return false;
}

function lodNoti(ctrl)
{
    jVRI("#id").load(ctrl);
    alert("Notificado");
}


//-------------
// E: Mar-2018
//-------------
