<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/*************************************************************************
 *
 *  API REST connection to GEN.Repo
 *  Developed by VRI - IT Team
 *  Oct 22, 2018
 *
 ************************************************************************/


class GenApi {

    //* Do not optimize the redundancy it is just for modularity in other APIs


    //-----------------------------------------------------------------------------------
    // grados y titulos SUNEDU con Captcha IA : do not use without API module
    //-----------------------------------------------------------------------------------
    public function getGradeSune( $dni="01307299", $cap="" )
    {
        $userId = 'usr.developer';
        $pubKey = 'ab0e1606f11c69eaf695e0877a261269';

        $api = "";
        $res = curl_exec($api);
        return $res; //return json_decode($res);
    }


    // solo datos sin foto
    public function getDataBasic( $dni="01307299" )
    {
        $userId = 'usr.pilar.vri';
        $pubKey = 'ab0e1606f11c69eaf695e0877a261269';

        $api = "";
        $res = curl_exec($api);
        return $res; //json_decode($res);
    }


    // Full data with image ::::  Previos : 1240
    //
    public function getDataPer( $dni="01307299" )
    {
        $userId = 'adm.pilar.vri';
        $pubKey = 'ab0e1606f11c69eaf695e0877a261269';


        $api = "";
        $res = curl_exec($api);
        return json_decode($res);
    }
}

?>