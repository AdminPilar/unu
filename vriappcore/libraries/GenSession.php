<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

//--------------------------------------------------------------------------------
//  Custom Library : Native Session Manager
//   coded by: Ramiro Pedro Laura Murillo
//   dated on: 21/02/2017
//--------------------------------------------------------------------------------

class GenSessionNative {

    public function isOn()
    {
        echo "custom lib: GenSession loaded.<br>";
    }

    public function set_userdata( $name, $value )
    {
        if(!isset($_SESSION))
            session_start();

        //session_register( $value );
        $_SESSION[ $name ] = $value;
    }

    public function userdata( $name )
    {
        if(!isset($_SESSION))
	        session_start();

        if( isset($_SESSION[ $name ]) )
            return $_SESSION[ $name ];

        return NULL;
    }

    public function sess_destroy( $name )
    {
        if(!isset($_SESSION))
            session_start();

        $_SESSION[ $name ] = NULL;
    }
}


//--------------------------------------------------------------------------------
// Enhanced Session Class : indepent sessions by App as it worth  (Pedrix)
//--------------------------------------------------------------------------------

define( "SessRealName", "iVRI" );


class GenSession extends GenSessionNative {

    public function __construct()
    {
        //parent::__construct();
    }

    //------------------------------------------------------------------------------------------------
    // Login para usuarios pilar, fedu
    //------------------------------------------------------------------------------------------------
    public function  SetUserLogin( $userDesc, $userId, $userName, $userMail="(none)", $userDNI="", $userCod="", $IdCarr=0, $IdCateg=0 ){

        $sessdata = array(
            'IdService' => 0x510,
            'servName'  => 'utf8',
            'userLevel' => 0,
            'userType'  => 0,
            'userId'    => $userId,
            'userCod'   => $userCod,
            'userDesc'  => $userDesc,
            'userName'  => $userName,
            'userMail'  => $userMail,
            'userDNI'   => $userDNI,
            'IdCarrera' => $IdCarr,
            'IdCategor' => $IdCateg,
            'islogged'  => true
        );

        $this->SetSessionData( $sessdata, SessRealName );
    }
    public function SetCordLogin( $sessName, $userId, $userName,$userFacu,$userLevel=0 )
    {
        $sessdata = array(
            'IdService' => 0x320,
            'servName'  => 'genx',
            'userId'    => $userId,
            'userName'  => $userName,
            'IdFacultad' => $userFacu,
            'userLevel' => $userLevel,
            'userType'  => "Coordinador",
            'islogged'  => true
        );

        $this->SetSessionData( $sessdata, $sessName );
    }

    public function SetAdminLogin( $sessName, $userId, $userName, $userAlias, $userLevel=0 )
    {
        $sessdata = array(
            'IdService' => 0x420,
            'servName'  => 'genx',
            'userId'    => $userId,
            'userLevel' => $userLevel,
            'userType'  => "Admin",
            'userName'  => $userName,
            'userAlias' => $userAlias,
            'islogged'  => true
        );

        $this->SetSessionData( $sessdata, $sessName );
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

    function GetData( $sessName=SessRealName )
    {
        return $this->GetSessionData($sessName);
    }

    /*
    function IsLoggedAccess( $sessName=SessRealName )
    {
        $sessData = $this->GetSessionData( $sessName );
        if( ! $sessData ) {
            echo "Pilar : You aren't allowed";
            exit; return;
        }
    }*/

    // version modificada para sessiones Multi-Admin level
    function IsLoggedAccess( $sessName=SessRealName, $arrAllows=1 )
    {
        $sessData = $this->GetSessionData( $sessName );
        if( ! $sessData ) {
            echo "iVRI : You aren't allowed";
            exit; return;
        }

        $allowed = false;
        if( is_array($arrAllows) ) {
            foreach( $arrAllows as $itm ){
                if( $itm == $sessData->userLevel )
                    $allowed = true;
            }
        } else {
            //if( $arrAllows == $sessData->userLevel )
            $allowed = true; // para todos los ($sessName)
        }

        if( ! $allowed ) {
            echo "iVRI : You haven't enough priviledges";
            exit; return;
        }
    }

    function SessionDestroy( $sessName=SessRealName )
    {
        $this->sess_destroy( $sessName );
    }
}

?>
