<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//
// add our Base Model
//
require_once APPPATH."models/PedrixAdo.php";


//
// our Composed Model to WebApp
//

class DbWeb extends PedrixAdo
{

    public function __construct()
    {
        parent::__construct();

        //
        // set the local class DB access
        //
        $this->setDB( "vriunap_web" );
    }

    //---------------------------------------------------------------------------
    // area de funciones propias para DB manage
    //---------------------------------------------------------------------------


}
