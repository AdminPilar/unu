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


// global session iVRI
include( "absmain/mlLibrary.php" );



class Web extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('dbPilar');
        $this->load->model('dbRepo');
        


        $this->load->library("GenSession");
        $this->load->library("GenMailer");
    }

    public function index()
    {
        $this->load->view( "web/vhead" );
        $this->load->view( "web/vbody" );
    }

    public function jsDlgLogin()
    {
        $this->load->view( "web/dlgLogin" );
    }

    public function jsQryLogin()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        //-------------------------------------------------------
        $user = mlSecurePost("user");
        $pass = mlSecurePost("pass");
        if( !$user ) return;


        // verificar existencia de correo
        if( ! $this->dbRepo->getSnapRow( "tblDocentes", "Correo='$user'" ) ) {
            echo '[{"error":true, "msg":"Este Correo no está registrado"}]';
            return;
        }

        // ahora si comprobar cuenta
        $row = $this->dbRepo->loginByMail( "tblDocentes", $user, sqlPassword($pass) );
        if( ! $row ) {
            echo '[{"error":true, "msg":"Su clave es incorrecta"}]';
            return;
        }

        //----------------------------------------------------------------
        // como todo esta correcto creamos la sesion general
        //----------------------------------------------------------------
        $this->gensession->SetUserLogin(
            'docentes',
            $row->Id,
            $row->Apellidos,
            $row->Correo,
            $row->DNI
        );

        echo '[{"error":false, "msg":"OK, Estamos redireccionando..."}]';
    }

    public function logout() {

        $this->gensession->SessionDestroy();
        redirect( base_url(""), 'refresh');
    }

}

//- EOF
