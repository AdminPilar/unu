<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//
// add our Base Model
//
require_once APPPATH."models/PedrixAdo.php";


//
// our Composed Model to WebApp
//

class DbFedu extends PedrixAdo
{

    public function __construct()
    {
        parent::__construct();

        //
        // set the local class DB access
        //
        $this->setDB( "desarrollo_fedu" );
    }

    public function proyectosExe(){
    	// return  $this->getSnapView("proyecto","estado=3 OR Estado=7")->num_rows();
    }
}

// EOF