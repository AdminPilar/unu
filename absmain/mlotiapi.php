<?PHP
/*
	echo "<br> Codigo:       " . $alumno->items[0]->codigo;
	echo "<br> DNI:          " . $alumno->items[0]->documento_numero;
	echo "<br> apellidos:    " . $alumno->items[0]->apellidos;
	echo "<br> Nombres:      " . $alumno->items[0]->nombres;
	echo "<br> Carrera:      " . $alumno->items[0]->escuela;
	echo "<br> Modaliad:     " . $alumno->items[0]->matricula->modalidad;
	echo "<br> Año de Mat:   " . $alumno->items[0]->matricula->anio;
	echo "<br> Periodo:      " . $alumno->items[0]->matricula->periodo;
	echo "<br> Especialidad: " . $alumno->items[0]->matricula->especialidad;
	echo "<br> Semestre:     " . $alumno->items[0]->matricula->semestre;
	echo "<br> Creditos:     " . $alumno->items[0]->matricula->creditos;


    186834 : codigo segunda espec

*/
//----------------------------------------------------------------------------------------------
//
// print_r( $var );
//
//----------------------------------------------------------------------------------------------
function otiGetData( $codMat )
{
    return json_decode( otiGetAlumno($codMat) );
}
//----------------------------------------------------------------------------------------------

function otiGetAlumno( $codMat ){

    /********************************************************************************************
    DATOS DE TU CUENTA
    ********************************************************************************************/

    $app_id  = 'VRI_api_ID';
    $app_key = 'KEY:df5sf46ds5f4sdfkdslfks';

    $parametros = array(
        'controller' => 'Estudiantes',
        'action'     => 'datos',
        'codigo'     => $codMat, // '131313',
        'clave'      => 'dskfjdslfsd4'
    );

    // $peticion = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $app_key, json_encode($parametros), MCRYPT_MODE_ECB));
    //
    // $id = openssl_random_pseudo_bytes(16);
    // $peticion = openssl_encrypt( json_encode($parametros), 'AES-128-CBC', $app_key, OPENSSL_RAW_DATA, $id);

    $peticion = json_encode($parametros);  /// -> 2019-11-11 Solo se cambio esta linea

    $api = curl_init();
    curl_setopt($api, CURLOPT_URL, 'http://hosting.deconsumo.api/');
    curl_setopt($api, CURLOPT_POST, TRUE);
    curl_setopt($api, CURLOPT_POSTFIELDS, array('request'=>$peticion, 'id'=>$app_id));
    curl_setopt($api, CURLOPT_RETURNTRANSFER, 1);

    $resultado = curl_exec($api);

    return $resultado;
}

?>