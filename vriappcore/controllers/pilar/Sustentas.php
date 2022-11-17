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
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


class Sustentas extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dbPilar');
        $this->load->model('dbRepo');
        $this->load->library('GenSexPdf');
    }

    public function index()
    {
        $this->load->view( "pilar/web/header" );
        $this->load->view( "pilar/web/comuns" );
    }

    public function aviso( $idav=null)
    {
        $idav = secureString( $idav );
        if( ! $idav ) return;

        $row = $this->dbPilar->getSnapRow( "vxSustens", "Id=$idav" );
        $tipo = $this->dbPilar->getOneField( "tesSustens","Tipo", "Id=$idav" );
        if( !$row ) {
            echo "Sin registros";
            return;
        }

        //--------------------------------------------------------------------
        $tram = $this->dbPilar->inProyTram($row->IdTramite);
        $det = $this->dbPilar->inLastTramDet($row->IdTramite);

        // ambos tesistas : tambien de 1 en singular
        $nombs = $this->dbPilar->inTesistas($row->IdTramite);
        $aseso = $this->dbRepo->inDocente( $tram->IdJurado4 );
        $jura3 = $this->dbRepo->inDocente( $tram->IdJurado3 );
        $jura2 = $this->dbRepo->inDocente( $tram->IdJurado2 );
        $jura1 = $this->dbRepo->inDocente( $tram->IdJurado1 );


        $pdf = new GenSexPdf();

        //$pdf->AddPage();
        $pdf->AddPageEx( "L", "A4", 0, 0 );
        if ($tipo==1) {
            $pdf->Image( "vriadds/pilar/imag/sustenvri.jpg", 0, 0, 296, 205 );
        }
        if ($tipo==2) {
            $pdf->Image( "vriadds/pilar/imag/sustenvri2.jpg", 0, 0, 296, 205 );
        }
        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->Line( 1, 30, 299, 30 );


        $pdf->Ln(68);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell( 10, 8, "" );
        $pdf->MultiCell( 260, 8, toUTF("\"$det->Titulo\""), 0, 'C' );


        $pdf->Text( 105, 125, toUTF("$nombs") );
        $pdf->Text( 105, 134, toUTF("$aseso") );

        $pdf->Text( 105, 151, toUTF("$jura1") );
        $pdf->Text( 105, 160, toUTF("$jura2") );
        $pdf->Text( 105, 169, toUTF("$jura3") );


        // mlFechaNorm
        $pdf->SetFont('Arial','B',14);
        $pdf->Text( 60, 180, toUTF(mlFechaNorm($row->Fecha) ."  --  $row->Lugar")  );


        // obtener el mes.
        $arr = array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
                     "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $mes = $arr[ (int)substr($row->Fecha,5,2) ];


        $fecha = mlCurrentDate();
        $aniox = (int) substr( $fecha, 0, 4 );

        $pdf->SetFont('Arial','B',10);
        $pdf->Text( 240, 200, toUTF("Puno, $mes de $aniox") );

        mlQrRotulo( $pdf, 242, 120, $row->Codigo );

        $pdf->Output();
    }

}

