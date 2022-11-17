<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

//--------------------------------------------------------------------------------
//  Custom Library : Enhanced General Session Manager
//   coded by: M.Sc. Ramiro Pedro Laura Murillo
//   dated on: 21/02/2017
//--------------------------------------------------------------------------------


require_once BASEPATH . "libraries/Session/Session.php";


define("SessRealName", "SessVRIc" );


// our Composed Control to WebApp
//
class GenSessionCI  extends CI_Session {

    public function isOn()
    {
        echo "custom lib: GenSession loaded.<br>";
    }

    public function __construct()
    {
        parent::__construct();
    }

    //------------------------------------------------------------------------
    public function  SetUserLogin( $userDesc, $userName, $userId=0, $userMail="(none)", $userDNI="" ){

        $sessdata = array(
            'IdService' => 0x01,
            'servName'  => 'CCC17',
            'IdUser'    => $userId,
            'userLevel' => 1,
            'userType'  => 1,
            'userDesc'  => $userDesc,
            'userName'  => $userName,
            'userMail'  => $userMail,
            'userDNI'   => $userDNI,
            'islogged'  => true
        );

        // session name 'SessVRIc'
        //
        $this->SetSessionData( $sessdata );
    }

    function SetSessionData( $arrData, $sessName=SessRealName )
    {
        // $this->mark_as_temp( $arrData, seconds );
        $this->set_userdata( $sessName, $arrData );
    }

    function GetSessionData( $sessName=SessRealName )
    {
        // verificar si es de esta APP ojo

        $sessData = $this->userdata( $sessName );
        if( ! $sessData ) return NULL;

        // si existe info, crear en JSON
        return json_decode( json_encode($sessData) );
    }

    function IsLoggedAccess( $sessName=SessRealName )
    {
        $sessData = $this->GetSessionData( $sessName );
        if( ! $sessData ) {
            echo "CCC2017 : You aren't allowed";
            exit; return;
        }
    }

    function SessionDestroy( $sessName=SessRealName )
    {
        $this->sess_destroy();
    }
}