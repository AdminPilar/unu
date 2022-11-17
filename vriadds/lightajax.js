//---------------------------------------------------------------
//
//  light Ajax - codename [fuck JQuery.c]
//  * Developed by: M.Sc. Ramiro Pedro Laura Murillo
//  * Modules:  set, get $,  ajax DOM request
//  * Modules:  JS script DOM in Request
//
//---------------------------------------------------------------

// autoexecutables functions
//
(function ($) {
	// do something  $  is an argument
})("fuckQuery v.0.5.c");



//---------------------------------------------------------------------
// our JS+DOM Request
//---------------------------------------------------------------------
function privSendRequest( qryFile, argObj, evOnDone, divDest )
{
    var ajax = new XMLHttpRequest();

    ajax.open( "POST", qryFile, true ); // async: no wait
    if( argObj != "[object FormData]" )
        ajax.setRequestHeader(
            "Content-Type",
            "application/x-www-form-urlencoded"
    );

    // stringURL or FormData
    ajax.send( argObj );
    ajax.onreadystatechange = function() {
        if ( ajax.readyState == 4 ) {
            if( evOnDone != null )
                evOnDone( parseJS(ajax.responseText) );
            if( divDest != null )
                divDest.innerHTML = parseJS( ajax.responseText );
        }
    }
}

//---------------------------------------------------------------
// funciones locales de tratamiento
//---------------------------------------------------------------
function mlElementById( strid )
{
    return document.getElementById( strid );
}

function parseJS(texto)
{
    var str = texto.split( "<script>" );
    var res = str[0];
    for( var i=1; i<str.length; i++ ) {
        var tmp = str[i].split("</script>", 2 );
        res = res + tmp[1];
        if( tmp[0].length )
            eval( tmp[0] );
    }
	return res;
}

function jsonStr( vars ) {
    // in  = { lastname:'Jose', firstname:'Xavier' }
    // out = lastname=jose...
    arr = JSON.stringify( vars );
    str = arr.split(":").toString();
    str = str.replace( "{", "(" );
    str = str.replace( "}", ")");
    arr = eval( 'Array' + str );
    tmp = "";
    for( i=0; i<arr.length; i+=2 ){
        if( tmp ) tmp += "&";
        tmp += arr[i+0] +"="+ arr[i+1];
    }
    return tmp;
}
//---------------------------------------------------------------
function mlObjectDOM( ctrl ) {

    this.control = ctrl;
    //---------------------------------------------
    this.val = function ( arg ) {

        if( arg!=null ) this.control.value = arg;
        else {
            if( this.control == null )
                return 0;

            return this.control.value;
        }
    };

    this.html = function( arg ) {

        this.control.innerHTML = arg;
    };

    this.focus = function(){

        this.control.focus();
    };

    this.change = function( argfunc ){

        this.control.addEventListener('change', argfunc, false);
    };

    this.show = function(){
        this.control.style = "display: block !important;";
    }

    this.load = function( url, arg ) {

        // arguments r: url, arg, no-event, div
        privSendRequest( url, arg, null, this.control );
    };

    this.style = function( arg ) {
        ;
    };
};
//---------------------------------------------------------------
var mlLightAjax = function( strId ) {
	// this.params = params; {a:0}
	//
	// intead of: document.getElementById(idStr)

    return ( new mlObjectDOM(document.querySelector(strId)) );
};


mlLightAjax.ajax = function( params ) {

    privSendRequest(
            params.url,
            //params.type,  ::no used
            params.data,
            params.success,
            null   // no-div
    );
};

//-------------------------------------------------------
// local definition over our Compatible fuckQuery
//-------------------------------------------------------
//$ = mlLightAjax;


jVRI = mlLightAjax;
//-------------------------------------------------------

/*
    $(document).ready(function() {
    $("#word_count").on('keyup', function() {
        var words = this.value.match(/\S+/g).length;
        if (words > 10) {

            var trimmed = $(this).val().split(/\s+/, 10).join(" ");
            // Add a space at the end to keep new typing making new words
            $(this).val(trimmed + " ");
        }
        else {
            $('#display_count').text(words);
            $('#word_left').text(10-words);
        }
    });
 });
*/