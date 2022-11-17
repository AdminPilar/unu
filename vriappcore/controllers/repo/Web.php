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
include( "absmain/fpdf/fpdf.php" );


class Web extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dbPilar');
        $this->load->model('dbRepo');
        //$this->load->library("GenSession");
        //$this->load->library("GenMailer");
        //$this->load->library( "GenSexPdf" );
    }

    public function index()
    {
        //header("Location: http://www.vriunap.pe/home");
        if( mlPoorURL() )
            redirect( mlCorrectURL() );

        $this->load->view( "repos/head" );
        $this->load->view( "repos/contens" );
    }

    public function enlaces()
    {
        $str = "https://www.facebook.com/coespe.crpuno.5/";

        echo "<pre>";
        echo htmlentities( $str );
        echo "</pre>";

        echo "<br>";
        //echo html_entity_decode( $str );

        echo urlencode ($str);
    }

    public function GenTapa( $codTram=null )
    {
        if( ! $codTram ) return;

        $codTram = secureString( $codTram );
        $tram = $this->dbPilar->inTramByCodigo( $codTram );
        if( ! $tram ) {
            echo "Lo sentimos, este Codigo no se ha encontrado."; return;
        }

        $dets = $this->dbPilar->inLastTramDet( $tram->Id );
        if( ! $dets ) return;


        if( $tram->Tipo != 3 or $tram->Estado <= 12 ) {
            echo "Aun no ha sustentado o no finalizado su tramite en PILAR."; return;
        }

        $carrerap = "ESCUELA PROFESIONAL DE " . $this->dbRepo->inCarrera( $tram->IdCarrera );
        $facultad = "FACULTAD DE "            . $this->dbRepo->inFacultad( $tram->IdCarrera );
        $tesistas = str_replace( "y", "\n", $this->dbPilar->inTesistas($tram->Id) );
        $tesistas = str_replace( ",", "", $tesistas );
        $profesio = $this->dbRepo->inCarrera( $tram->IdCarrera, 1 );


        //echo "tapa de romel";
        $pdf = new FPDF();
        $pdf->SetMargins( 22, 27, 20 );

        //-------------------------------------------------------------------------------------
        // Cara 1 : TAPA
        //-------------------------------------------------------------------------------------
        $pdf->AddPage();
        $pdf->SetFont( "Arial", "B", 16 );

        $pdf->Ln(1);
        $pdf->Cell( 172, 8, "Universidad Nacional de Ucayali - PUCALLPA", 0, 1, "C" );
        $pdf->Cell( 172, 8, toUTF($facultad), 0, 1, "C" );
        $pdf->Cell( 172, 8, toUTF($carrerap), 0, 1, "C" );

        $pdf->Image( "absmain/imgs/unap.png", 78, 55, 60 );

        $pdf->Ln(80);
        $pdf->SetFont( "Arial", "B", 20 );
        $pdf->Cell( 172, 5, toUTF("TESIS"), 0, 1, "C" );

        $pdf->Ln(5);
        $pdf->SetFont( "Arial", "B", 15 );
        $pdf->MultiCell( 172, 6.5, toUTF($dets->Titulo), 0, "C" );

        $pdf->Ln(10);
        $pdf->SetFont( "Arial", "", 12 );
        $pdf->Cell( 172, 6, toUTF("PRESENTADO POR:"), 0, 1, "C" );

        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "B", 16 );
        $pdf->MultiCell( 172, 6, toUTF($tesistas), 0, "C" );

        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "", 12 );
        $pdf->Cell( 172, 6, toUTF("PARA OPTAR EL TÍTULO PROFESIONAL DE:"), 0, 1, "C" );

        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "B", 16 );
        $pdf->MultiCell( 172, 6, toUTF($profesio), 0, "C" );

        $pdf->Ln(17);
        $pdf->SetFont( "Arial", "B", 16 );
        $pdf->Cell( 172, 6, toUTF("UNA - PUCALLPA"), 0, 1, "C" );
        $pdf->Cell( 172, 6, toUTF("2018"), 0, 1, "C" );

        CodeQR( $pdf, 180, 265, "VRI UNA PUNO", 100 );

        //-------------------------------------------------------------------------------------
        // Cara 2 : Hoja de Jurados
        //-------------------------------------------------------------------------------------
        $pdf->AddPage();
        $pdf->SetFont( "Arial", "B", 14 );

        $pdf->Ln(1);
        $pdf->Cell( 172, 8, "Universidad Nacional de Ucayali - PUCALLPA", 0, 1, "C" );
        $pdf->Cell( 172, 8, toUTF($facultad), 0, 1, "C" );
        $pdf->Cell( 172, 8, toUTF($carrerap), 0, 1, "C" );
        $pdf->SetFont( "Arial", "", 5 );
        $pdf->Cell( 172, 2, "_____________________________________________________________________________________________________________________________________________________________________________________", 0, 1, "C" );


        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 18 );
        $pdf->Cell( 172, 5, toUTF("TESIS"), 0, 1, "C" );

        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "B", 13 );
        $pdf->MultiCell( 172, 6, toUTF($dets->Titulo), 0, "C" );

        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "", 12 );
        $pdf->Cell( 172, 6, toUTF("PRESENTADO POR:"), 0, 1, "C" );

        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "B", 14 );
        $pdf->MultiCell( 172, 6, toUTF($tesistas), 0, "C" );

        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "", 12 );
        $pdf->Cell( 172, 6, toUTF("PARA OPTAR EL TÍTULO PROFESIONAL DE:"), 0, 1, "C" );

        $pdf->Ln(4);
        $pdf->SetFont( "Arial", "B", 16 );
        $pdf->MultiCell( 172, 6, toUTF($profesio), 0, "C" );

        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "", 12 );
        $pdf->Cell( 172, 6, toUTF("APROBADA POR EL JURADO DICTAMINADOR:"), 0, 1, "L" );


        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 10 );

        $jurads = array(
            array( "PRESIDENTE DE JURADO", $this->dbRepo->inDocenteEx($tram->IdJurado1) ),
            array( "PRIMER MIEMBRO"      , $this->dbRepo->inDocenteEx($tram->IdJurado2) ),
            array( "SEGUNDO MIEMBRO"     , $this->dbRepo->inDocenteEx($tram->IdJurado3) ),
            array( "Asesor"     , $this->dbRepo->inDocenteEx($tram->IdJurado4) )
        );

        for( $i=0; $i<4; $i++ ) {
            $pdf->Cell( 80, 5, toUTF( $jurads[$i][0] ), 0, 0, "L" ); $pdf->Cell( 90, 5, ": __________________________________________", 0, 1, "L" );
            $pdf->Cell( 80, 5, "", 0, 0, "L" );                      $pdf->Cell( 90, 5, toUTF( $jurads[$i][1] ), 0, 1, "C" );
            if( $i < 3 ) $pdf->Cell( 2, 16, "", 0, 1 );
        }

        $area = $this->dbRepo->inAreaInv( $tram->IdLinea );
        $tema = $this->dbRepo->inLineaInv( $tram->IdLinea );

        $pdf->Ln(7);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 30, 5, "AREA", 0, 0 ); $pdf->Cell( 50, 5, toUTF(": $area"), 0, 1 );
        $pdf->Cell( 30, 5, "TEMA", 0, 0 ); $pdf->Cell( 50, 5, toUTF(": $tema"), 0, 1 );

        CodeQR( $pdf, 180, 265, "VRI UNA PUNO", 100 );

        $pdf->Output();
    }
}

//- EOF