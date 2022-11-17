<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/****************************************************************************
 *
 *   Project: Integrated VRI - [codename: BoobieMovie]  ::: core 3.1.3
 *
 *   Software Architects:
 *     M.Sc. Ramiro Pedro Laura Murillo
 *     Ing. Fred Torres Cruz
 *     Ing. Julio Cesar Tisnado Puma
 *
 *   Begin coding Date: 20 - 03 - 2017
 *
 ***************************************************************************/


include( "absmain/mlLibrary.php" );
include( "absmain/mlotiapi.php" );
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


// tesBorrador
// Edicion 2018.a
define( "ANIO_PILAR", "2020" );
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


class Defensa extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function Index() {
        echo "<center><iframe src='https://docs.google.com/forms/d/e/1FAIpQLSfj1qXwcEDGer3AuZq4dA7ujjLfNZbNajNsSP5vHj0nN0pRHw/viewform?embedded=true' width='640' height='900' frameborder='0' marginheight='0' marginwidth='0'>Cargando…</iframe><center>";
    }

}

//- EOF

