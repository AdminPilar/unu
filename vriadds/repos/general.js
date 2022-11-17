// #
// # VRI - Universidad Nacional de Ucayali - PUCALLPA 2020
// # Ing. Ramiro Pedro, Ing. Fred Torres, Ing. Julio Tisnado
// #

function repoLoad( idv, urlx, arg )
{
    //jVRI( "#"+idv ).html( "Buscando..." );
    //jVRI( "#"+idv ).load( urlx, arg );
    $('#frmpdf').attr('src', urlx);
    return false;
}

function divLoad( idv, urlx, arg )
{
    if( urlx=="jurados/grabNotas" ) {
        if( validaIn(nota1) ) return false;
        if( validaIn(nota2) ) return false;
        if( validaIn(nota3) ) return false;
    }

    if( urlx=="admin/sorteo" )
        jVRI( "#"+idv ).html( "<center><img src='../vriadds/repos/sortes.gif' width=80%>" );
    else
        jVRI( "#"+idv ).html( "Procesando..." );

    jVRI( "#"+idv ).load( urlx, arg );
    return false;
}

function validaIn( edt )
{
    if( edt.value < 0 || edt.value > 10 ) {
        alert( "La puntuación es entre 0.0 y 10.0" );
        edt.value = "";
        edt.focus();
        return true;
    }
    return false;
}

function imprime(cod,jur)
{
    jVRI.ajax({
        url  : "jurados/imprime/"+cod+"/"+jur,
        success: function ( arg ){
            window.print();
            alert( arg );
            divLoad('dvDisp','jurados/listar');
        }
    });
}

