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

// codigos de prueba aqui: eventos::
// JsBusqar :: Tesista API OTI

// space :: df


class Virtual extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dbPilar');
        $this->load->model('dbRepo');
        //$this->load->library("GenSession");
        //$this->load->library("GenMailer");
    }

    public function index()
    {
        //echo '<iframe width="100%" height="100%" frameborder=1 sandbox="allow-presentation" src="https://meet.google.com/?authuser=1">Loading...</iframe>';
        echo '<iframe name="I1" id="if1" width="100%" height="100%" style="visibility:visible" src="https://www.google.com/webhp?igu=1">Loading...</iframe>';
    }
}