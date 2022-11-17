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



define( "OGT_ADMIN", "true" );


class Web extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('dbRepo');
        $this->load->library("GenSession");
        $this->load->library("GenSexPdf");
        $this->load->library("GenApi");
    }

    public function index()
    {
        if( mlPoorURL() )
            redirect( mlCorrectURL() );

        $this->load->view( "pilar/web/header" );
        $this->load->view( "ogt/panel" );
    }

    public function procexa( $dni=null, $code=null )
    {
        if( !$dni ){ echo "no DNI"; return; }

        // ini_set('max_execution_time', 3600*10 );  // bucles largos

        $rex = $this->genapi->getGradeSune( $dni, $code );
        $res = json_decode($rex);

        if( is_array($res) ) {

            echo "<pre>";
            print_r( $res );

        } elseif( isset($res->error) ){
            echo "<img src=\"data:image/png;base64,$res->img\">";
        } else {
            echo "Out: $rex";
        }
    }

    public function ogtSave( $sun, $dni )
    {
        if( !$this->dbRepo->getSnapRow("dicSuneOgt", "IdSune=$sun->ID") ){

            $this->dbRepo->Insert( "dicSuneOgt", array(
                "DNI"      => $dni,
                "IdSune"   => $sun->ID,
                "Tipo"     => $sun->TIPO_GRADO,
                "Grado"    => mb_strtoupper( strip_tags($sun->GRADO) ),
                "Nombres"  => mb_strtoupper( $sun->NOMBRE ),
                "Univ"     => $sun->UNIV,
                "Pais"     => $sun->PAIS,
                "Fecha"    => mlCurrentDate(),
                "FechObte" => $sun->DIPL_FEC
            ) );
        }
    }

    public function ogtBusqa()
    {
        $dnisx = mlSecurePost( "dnites" );
        $dnisx = str_replace(" ", "", $dnisx);
        $items = explode( "\n", $dnisx );


        echo "<table class='table table-bordered table-striped' style='font-size: 10px'>";
        echo "<tr> <th>Id</th> <td>DNI</th> <td>Tipo</th> <td>Nombres</th> <td>Grado</th> <td>Universidad</th> <td>Fecha</td> </tr>";
        foreach($items as $dni){

            if( strlen($dni) == 8 ){

                $sun = $this->genapi->getGradeSune( $dni );
                $sun = json_decode($sun);
                if( !is_array($sun) ){ echo "</table><b>Sin acceso re-captcha</b>"; break; }


                foreach( $sun as $row ){

                    echo "<tr>";
                    echo "<td> $row->ID </td>";
                    echo "<td> $row->DOC_IDENT </td>";
                    echo "<td> $row->TIPO_GRADO .$row->TIPO_INSCRI </td>";
                    echo "<td>" .mb_strtoupper($row->NOMBRE). "</td>";
                    echo "<td>" .mb_strtoupper($row->GRADO). "</td>";
                    echo "<td> $row->UNIV<br>$row->PAIS </td>";
                    echo "<td> $row->DIPL_FEC </td>";
                    echo "</tr>";

                    $this->ogtSave( $row, $dni );
                }
            }
        }
        echo "</table>";


        /*

        [0] => stdClass Object
        (
            [ID] => 5046675
            [NOMBRE] => LAURA MURILLO, RAMIRO PEDRO
            [DOC_IDENT] => DNI 41939172
            [GRADO] => BACHILLER EN CIENCIAS ESTADISTICAS E INFORMATICA
            [TITULO_REV] => BACHILLER EN CIENCIAS ESTADISTICAS E INFORMATICA
            [GRADO_REV] =>
            [DIPL_FEC] => 27/12/2012
            [RESO_FEC] => 27/12/2012
            [ESSUNEDU] => 0
            [UNIV] => Universidad Nacional de Ucayali
            [PAIS] => PERU
            [COMENTARIO] => -
            [TIPO] => N
            [TIPO_GRADO] => B
            [DIPL_TIP_EMI] =>
            [TIPO_INSCRI] =>
            [NUM_DIPL_REVA] =>
            [NUM_ORD_PAG] =>
            [V_ORIGEN] =>
            [NRO_RESOLUCION_NULIDAD] =>
            [FLG_RESOLUCION_NULIDAD] =>
            [FECHA_RESOLUCION_NULIDAD] =>
        )

        */
    }

    public function ogtLista()
    {
        $this->gensession->IsLoggedAccess(OGT_ADMIN);
        $sess = $this->gensession->GetData(OGT_ADMIN);

        $tbl = $this->dbRepo->getSnapView( "dicSuneOgt", "1 ORDER BY Id DESC" );

        echo "<table class='table table-bordered table-striped' style='font-size: 10px'>";
        echo "<tr> <th>Nro</th> <th>Id</th> <td>DNI</th> <td>Tipo</th> <td>Nombres</th> <td>Grado</th> <td>Universidad</th> <td>Fecha</td> </tr>";

        if( $tbl )
        foreach( $tbl->result() as $row ){
            //print_r( $row );
            //echo "<hr>";

            echo "<tr>";
            echo "<td> $row->Id </td>";
            echo "<td> $row->IdSune </td>";
            echo "<td> $row->DNI </td>";
            echo "<td> $row->Tipo </td>";
            echo "<td> $row->Nombres </td>";
            echo "<td> $row->Grado </td>";
            echo "<td> $row->Univ<br>$row->Pais </td>";
            echo "<td> $row->FechObte </td>";
            echo "</tr>";
        }

        echo "</table>";
    }




    //---------------------------------------------------------------------
    // area de validacion
    //---------------------------------------------------------------------
    public function login()
    {
        $user = mlSecurePost("user");
        $pass = mlSecurePost("pass");
        if( !$user or !$pass ) exit;


        if( $user == "flor" && $pass=="lanalgona" )
        {
            $this->gensession->SetAdminLogin (
                OGT_ADMIN, 1, "Admin OGT", "Local pass"
            );
        }

        if( $user == "admin" && $pass=="ogt**911" )
        {
            $this->gensession->SetAdminLogin (
                OGT_ADMIN, 1, "Admin OGT", "Los Patas"
            );
        }


        redirect( "ogt", "refresh" );
    }

    public function logout()
    {
        $this->gensession->SessionDestroy( OGT_ADMIN );
        redirect( base_url("ogt"), 'refresh');
    }

    public function ver()
    {
        //$this->gensession->IsLoggedAccess(OGT_ADMIN);
        $sess = $this->gensession->GetData(OGT_ADMIN);

        print_r( $_SESSION );
        echo "<hr>";
        print_r( $sess );
    }

}
