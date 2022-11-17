<?PHP
/*
 *  MicroLogic Software - Fast php Library
 *  Developed by:  M.Sc. Ramiro Pedro Laura Murillo
 *  Date : January, 2016
 *
 *  ** v0.59 [ april 2017 ]
 *
 **/

function toUTF( $str ){
    return utf8_decode( $str );
}

function secureString( $str )
{
    $str = strip_tags($str);
    $str = str_replace( "'",  "", $str);
    $str = str_replace( "{", "",  $str);
    $str = str_replace( "}", "",  $str);
    $str = str_replace( "%", "",  $str);
    $str = str_replace( "&", "",  $str);
    $str = str_replace( "#", "",  $str);
    $str = str_replace( ";", "",  $str);
    $str = str_replace( "!", "",  $str);
    $str = str_replace( ">", "",  $str);
    $str = str_replace( "<", "",  $str);
    $str = str_replace( "--", "", $str);

    $str = str_replace( "\"", "", $str);
    $str = str_replace( "\\", "", $str);

    $str = str_replace( "“", "",  $str);
    $str = str_replace( "”", "",  $str);
    $str = str_replace( "–", "-",  $str);

    return $str;
}
//------------------------------------------------------------
function mlSecureRequest( $field, $valdef=null ){

    if( isset($_REQUEST[$field]) )
        return secureString( $_REQUEST[$field] );

    return $valdef;
}
//------------------------------------------------------------
function mlRequest( $field, $valdef=null ){

    if( isset($_REQUEST[$field]) )
        return $_REQUEST[$field];

    return $valdef;
}
//------------------------------------------------------------
function mlSecurePost( $field, $valdef=null ){

    if( isset($_POST[$field]) )
        return secureString( $_POST[$field] );

    return $valdef;
}

function mlRequestNumber( $field, $valdef=null ) {

    return  (int) mlRequest( $field, $valdef );
}

function mlCurrentDate(){
	//date_default_timezone_set('America/Bogota');
    return date('Y-m-d H:i:s');
}

function mlConvertDate( $dateutc ) {

    return date_format( date_create($dateutc),"d/m/Y g:ia" );
}

function mlShortDate( $dateutc ) {

    return date_format( date_create($dateutc),"d/m/Y" );
}

function sqlPassword( $input ) {
    $pass = strtoupper(sha1(sha1($input,true)));
    $pass = '*' . $pass;
    return $pass;
}

// isepjae: filicc==
function mlRandomStr( $length=7 )
{
    $result = '';
    $source = 'abcdefghijklmnopqrstuvwxyz' .
              'ABCDEFGHIJKLMNOPQRSTUVWXYZ' .
              '1234567890';
    $source = str_split($source,1);
    for($i=1; $i<=$length; $i++){
        mt_srand((double)microtime() * 1000000);
        $num = mt_rand( 1, count($source) );
        $result .= $source[$num-1];
    }
    return $result;
}

function mlClientIP()
{
    if (isset($_SERVER["HTTP_CLIENT_IP"]))
    {
        return $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_X_FORWARDED"]))
    {
        return $_SERVER["HTTP_X_FORWARDED"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"]))
    {
        return $_SERVER["HTTP_FORWARDED_FOR"];
    }
    elseif (isset($_SERVER["HTTP_FORWARDED"]))
    {
        return $_SERVER["HTTP_FORWARDED"];
    }
    else
    {
        return $_SERVER["REMOTE_ADDR"];
    }
}

function mlReadURL()
{
    return ( "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] );
}

function mlCorrectURL()
{
    $url = mlReadURL();
    $len = strlen( $url );
    if( $url[$len-1] == "/" )
        return substr($url,0,$len-1);
    return $str;
}

// url finaliza con '/'
function mlPoorURL()
{
    $url = mlReadURL();
    $len = strlen( $url );
    if( $url[$len-1] == "/" )
        return true;
    return false;
}

// sin sesiones
function mlSetGlobalVar( $name, $value )
{
    if(!isset($_SESSION))
        session_start();

    $_SESSION[ $name ] = $value;
}

function mlGetGlobalVar( $name )
{
    if(!isset($_SESSION))
	    session_start();

    if( isset($_SESSION[ $name ]) )
        return $_SESSION[ $name ];

    return NULL;
}

function mlDestroyVars()
{
    if( isset($_SESSION) ) {
        session_unset();
        //session_destroy();
    }
}


function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data)
{
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

// date as UTC
function mlFechaNorm( $utcFecha )
{
    ///return date_format( date_create($utcFecha),"d/m/Y g:ia");
    return date_format( date_create($utcFecha),"d/m/Y h:i a" );
}

function mlFechaSolo( $utcFecha )
{
    return date_format( date_create($utcFecha),"d/m/Y" );
}

// diference betwen dates UTC
function mlDiasTransc( $fecha_i , $fecha_f )
{
    $dias = (strtotime($fecha_i)-strtotime($fecha_f))/86400;
    $dias = abs($dias); $dias = floor($dias);
    return $dias;
}

// how many days since arg 2 today
function mlDiasTranscHoy( $fecha_f )
{
    $dias = (strtotime("now")-strtotime($fecha_f))/86400;
    $dias = abs($dias);
    $dias = floor($dias);
    return $dias;
}

function mlNombreMes( $nmes )
{
    $meses = array( "", "ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO", "AGOSTO", "SEPTIEMBRE", "OCTUBRE", "NOVIEMBRE", "DICIEMBRE" );
    return $meses[ (int)$nmes ];
}

/*
function CodeQR( $pdf, $xpos, $ypos, $code, $siz=152 )
{
    $urlCode = urlencode ( $code );
    // "http://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=PedroEstuvo&.png";

    //$urlImage = "http://chart.googleapis.com/chart?"
    //          . "cht=qr&chs=$siz" ."x" ."$siz&chl=$urlCode&.png";
    //
    //$urlImage = "http://phpqrcode.sourceforge.net/qrsample.php?data=$urlCode&ecc=M&matrix=5&.png";

    $urlImage = "https://chart.googleapis.com/chart?chs=$siz" ."x" ."$siz&cht=qr&chl=$urlCode&choe=UTF-8";

    $pdf->Image( $urlImage, $xpos, $ypos);
    //$pdf->Rect( $xpos+3, $ypos+3, 20, 20 );
}*/

function mlQrRotulo( $pdf, $x, $y, $codigo )
{
    $codigof = toUTF("Código: $codigo");
    $titulo  = "VRI UNU - ".date("Y");
    
    $taman   = 150;
    $rectAnch = 32;
    if( strlen($codigo) > 14 ){
        $codigof = "";
        $taman   = 162;
        $rectAnch = 35;
    }

    // rectangle
    $pdf->SetFillColor( 255, 255, 255 );
    $pdf->SetDrawColor( 100, 100, 100 );
    //$pdf->Rect( $x, $y-3, 32, 41, 'F' );

    $pdf->SetFont('Arial','',7);

    //$pdf->Image( $file, $x, $y );  // no se graba ya
    $pdf->CodeQR( $x-1.6, $y-0.5, $codigo, $taman ); // 152

    $pdf->Text( $x+4, $y+0.5, $titulo );
    $pdf->Text( $x+5, $y+34, $codigof );

    $pdf->Rect( $x, $y-3, $rectAnch, 39 );

    // texto pie de pagina
    $pdf->SetFont( "Arial", "", 7 );
    $pdf->Text( $x, $y+39, toUTF("Vicerrectorado de Investigación") );
    //$pdf->Text( $x, $y+42, toUTF("Telefono: 051-365054") );
    $pdf->Text( $x, $y+45, toUTF("e-mail: soporte_pilar@unu.edu.pe") );
    $pdf->Text( $x, $y+48, toUTF("web: http://pilar.unu.edu.pe/unu/pilar") );
}

?>
