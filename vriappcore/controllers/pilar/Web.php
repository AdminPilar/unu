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
 *   Interface Design : Init: 11/04/17  Upload: 13/04/17
 *
 ***************************************************************************/

include( "absmain/mlLibrary.php" );
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


class Web extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dbPilar');
        //$this->load->library("GenSession");
        //$this->load->model('dbRepo');
    }


    public function index()
    {
        //redirect( base_url() );
        //echo "Redirigiendo...";

        $this->correctURL();

        $this->load->view( "pilar/web/header" );
        $this->load->view( "pilar/web/page" );
    }

    public function preguntas()
    {
        //redirect( base_url() );
        //echo "Redirigiendo...";

        $this->correctURL();
        $this->load->view( "pilar/web/header" );
        $this->load->view( 'pilar/web/pagefaq',array('faqs'=>$this->dbPilar->getTable('tblPreguntas')));
        
        //$this->load->view( "pilar/web/pagefaq" );
    }


    function correctURL()
    {
        if( mlPoorURL() )
            redirect( mlCorrectURL() );
    }
}

// EOF