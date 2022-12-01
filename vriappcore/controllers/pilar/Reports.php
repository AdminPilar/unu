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


define( "PILAR_CORDIS", "AdmCoords" );
define( "ANIO_PILAR", "2019" );
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


class Reports extends CI_Controller {

    public function __construct()    {
        parent::__construct();
        $this->load->model('dbPilar');  
        $this->load->model('dbFedu');
        $this->load->model('dbRepo');
        $this->load->model('dbComs');
        $this->load->model('dbCursos');
        $this->load->library("GenSession");
        $this->load->library('GenSexPdf');
        $this->load->library('GenMailer');
        $this->load->library('session');
    }

    public function reniec($dni) {
        $data=reniGetData( $dni );
        echo "$data->DNI : $data->ApPaterno";
    }

    public function index(){

        if($this->session->userdata('logged_in')==TRUE){
            $this->load->view("pilar/reportes/pilar",array(
                'carreras'=>$this->dbRepo->getTable("dicCarreras"),
                'estados'=>$this->dbPilar->getTable("dicEstadTram")
            ));
        }else{
            $this->load->view("pilar/reportes/acceso");
            // echo "<br>reniec-index-login-logout-procrep-laspauCreds-splitStr-sustenDirect-lineasbyCarrera-inLineasUNAP-DatosDocente-ProyectosdeTesis-credenciales3mt-credenciales3mtJUD-credenciales3mtoRG-Inscritos3mt2017-InscritosPoster2017-InscritosPoster2018s-comunicadoAmpCon-posterListaTemp-reporte3concursos-reportePosterII-reportePosterI-reportInscritos3mt-resumenPostul-recordatorioFEDU-docentesFeduMix-pruebamail-certifis-recordRevision-proyectosPILAR-SorteoPendiente-cambios-CambiosCarrera-histoTrams-ReporteContraloria1-ReporteContraloria2-ProyectosconJuradosparaReportedeAdministracion-ReporteJcesarPY-ReporteLienasPy2018-ReporteLienasPy2017-medicinaDatosMar2018-CantidadLineasbyCarrerr-ReporteLienasPyweb-ReportOCDEweb-LineasUNAP-ReportePILARCarrerasSandro-ReportePILARCarreras-FEDUweb-FEDUReginas-totSusten-repoGenpilar-docEscuel-demoRes-lineasValidas-lineasValidass-ReporteLaspau2018_RelacionTish-CredencialesLaspau-asistenciaValida-asistenciaEscuelas-asistenciaLaspauTodo-ReporteLaspauALL-ReporteLaspau2018-RepoteElly-personalFedu-notiCelu-notiCelu2-<br>deletemsj";
        }
    }

    public function login(){
        $user = mlSecurePost("email");
        $pass = mlSecurePost("pass");
        // echo "$user , $pass";
        if($user='admin@vriunap.pe' AND $pass='sansim0n4nap'){

            $newdata = array(
                'Usuario'  => 'Administrador',
                'email'     => 'fred',
                'logged_in' => TRUE
            );

            $this->session->set_userdata('logged_in', $newdata);
            redirect(base_url('pilar/reports'),'refresh');
        }
    }



    public function logout(){
        $this->session->unset_userdata('logged_in');
        redirect(base_url('pilar/reports'),'refresh');
    }

    public function procrep(){
        $carrera = mlSecurePost("carrie");
        $opcion = mlSecurePost("state");
        $ncarrera=$this->dbRepo->inCarrera($carrera);
        switch ($opcion) {
            case 100:
            $con=$this->dbPilar->getSnapView("tesTramites","IdCarrera = $carrera"," ORDER BY FechModif  DESC");
            break;
            case 201:
            $con=$this->dbPilar->getSnapView("tesTramites","Tipo=1 AND IdCarrera=$carrera"," ORDER BY FechModif DESC");
            break;

            case 202:
            $con=$this->dbPilar->getSnapView("tesTramites","Tipo=2 AND IdCarrera=$carrera"," ORDER BY FechModif DESC");
            break;

            default:
            $con=$this->dbPilar->getSnapView("tesTramites","Estado=$opcion AND IdCarrera=$carrera","ORDER BY FechModif DESC");
            break;
        }

        $this->load->view("pilar/reportes/pilardata",array(
            'idcarrera'=>$carrera,
            'carrera'=>$ncarrera,
            'proyectos'=>$con
        ));
    }


    public function Lineas_unap(  )
    {
        $lineas=$this->dbRepo->getSnapView('tblLineas',"Estado=1");
        foreach ($lineas->result() as $res) {

        // $this->load->view("pilar/head");
        echo "<div class='col-12'>";
        echo "<div class='col-md-3'> </div>";
        echo "<div class='col-md-6'> ";
        // echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";

        // $row = $this->dbRepo->getSnapRow( 'tblLineas', "Id=$res->Id" );
        // if( !$row ){ echo "No linea"; return; }
        echo "<h5 class='text-center'> LINEA DE INVESTIGACIÓN </h5>";
        echo "<h4 class='text-center'> <small>($res->Id)</small> $res->Nombre</h4>";

        // echo " $row->Id :: $row->Nombre ";
        $nro = 1;
        $tdocs = $this->dbPilar->getSnapView( 'vxDocInLin', "IdLinea=$res->Id", "ORDER BY IdCategoria, DatosPers" );
        echo "<table class='table table-striped ' border=1 cellSpacing=0 cellPadding=5 style='font: 12px Arial'>";
        foreach( $tdocs->result() as $doc ) {

            $tacher = $this->dbRepo->inDocenteEx($doc->IdDocente);
       

            $carrer = "<br><small>".$this->dbRepo->inCarreDoc($doc->IdDocente);

            echo "<tr>";
            echo "<td> $nro </td>";
            echo "<td> (id: $doc->IdDocente) </td>";
            echo "<td> $doc->CategAbrev </td>";
            echo "<td> $doc->TipoDoc </td>";
            echo "<td> $doc->Activo </td>";
            echo "<td> $tacher $carrer </td>";
            echo "<td> $doc->LinEstado</td>";
            echo "</tr>";
            $nro++;
        }
        echo "</table>";
        }
        echo "</div>";
    }

    /*
     * Credenciales Laspau Set-2018
     */
    public function laspauCreds()
    {
        $pdf = new GenSexPdf();

        $tbl = $this->dbPilar->getTable("_laspau", "Id = 251");

        $pdf->establecerfuente('TRUEBebasNeueRegular','','TRUEBebasNeueRegular.php');

        // foreach ( $tbl->result() as $row ){

        //     $ape = $this->dbRepo->getOneField( "tblDocentes", "Apellidos", "Id=$row->IdDoc");
        //     $nom = $this->splitStr( $this->dbRepo->getOneField( "tblDocentes", "Nombres", "Id=$row->IdDoc" ) );

            // $ape =" ";
            // $nom =" ";

        $pdf->AddPageEx( "L", "A4", 0, 0 );
        $pdf->Image( "vriadds/pilar/imag/credLaspau18.jpg", 0, 0, 300, 210 );

        $pdf->SetFont('TRUEBebasNeueRegular','',160);
        $pdf->SetTextColor( 0, 0, 0 );
        $pdf->Ln(90);
        $pdf->Cell( 270, 20, toUTF('LIDIA'), 0, 1, "R" );


        $pdf->SetFont('TRUEBebasNeueRegular','',35);
        $pdf->Cell( 270, 40, toUTF('ROMERO IRURI'), 0, 1, "R" );
            //$pdf->Ln(10);
        $pdf->SetFont('TRUEBebasNeueRegular','',35);
        $pdf->SetTextColor( 150, 150, 150 );
        $pdf->Cell( 270, 10, toUTF("LASPAU"), 0, 1, "R" );

        $pdf->SetFont('Arial','B',14);
        // }
        $pdf->Output();
    }


    public function credsgird()
    {
        $pdf = new GenSexPdf();

        $tbl = $this->dbCursos->getTable("vxInscritos", "IdEvento='91' ORDER BY DatosPers Asc");

        $pdf->establecerfuente('TRUEBebasNeueRegular','','TRUEBebasNeueRegular.php');

        foreach ( $tbl->result() as $row ){
            $nom= $this->splitStr($this->dbCursos->getOneField("Personas","Nombres","DNI=$row->DNI"));
            $apell= $this->dbCursos->getOneField("Personas","Apellidos","DNI=$row->DNI");
            $pdf->AddPageEx( "P", "A4", 0, 0 );
            $pdf->Image( "vriadds/pilar/imag/AsistentesGird.jpg", 0, 0,210, 300 );
            $pdf->SetTextColor( 0, 0, 0 );
            $pdf->Ln(192);
            $pdf->SetFont('TRUEBebasNeueRegular','',70);
            $pdf->Cell( 190,22, toUTF("$nom"), 0, 1, "C" );
            $pdf->Ln(1);
            $pdf->SetFont('TRUEBebasNeueRegular','',45);
            $pdf->SetTextColor( 125, 125, 125 );
            $pdf->Cell( 190,10, toUTF("$apell"), 0, 1, "C" );
            
            $pdf->Ln(5);
            $pdf->BarCode40( 58, 250, $row->DNI);
            $pdf->SetFont('Arial','B',14);
        }
        $pdf->Output();
    }

    public function ListasLetras($letra){
         $pdf = new GenSexPdf();
         $tbl = $this->dbCursos->getTable("vxInscritos", "IdEvento='91' ORDER BY DatosPers Asc");
         foreach($esc->result() as $row){
            $pdf->AddPageEx( "P", "A4", 0, 0 );
            $pdf->Image( "vriadds/pilar/imag/pilar-head.jpg", 10, 7, 190, 18 );
            $pdf->SetDrawColor( 170, 170, 170 );
            $pdf->SetFont('Times','B',14);
            $pdf->ln(28);
            $pdf->Cell( 190, 7, toUTF("IV FORO GIRD 2019"),0,1,"C" );
            $pdf->SetFont('Times','B',14);
            $pdf->SetFont('Times','B',14);
            $pdf->Cell( 90, 12, toUTF(" "),0,0,"L" );
            $pdf->Cell( 100, 12, toUTF("31 de Diciembre de 2018"),0,1,"R" );   

            $pdf->SetFillColor(240,240,235);
            $pdf->ln(5);
            $pdf->SetFont('Times','B',12);
            $pdf->Cell( 10, 7, toUTF("N°"),1,0,"C",1);
            $pdf->Cell( 30, 7, toUTF("DNI"),1,0,"C",1);
            $pdf->Cell( 105, 7, toUTF("Apellidos y Nombres"),1,0,"C",1);
            $pdf->Cell( 45, 7, toUTF("Firma"),1,1,"C",1);

            $flag=1;
            $pdf->SetFont('Times','',11);
            foreach ($postu->result() as $red) {
                $doc=$this->dbRepo->getSnapRow("vwDocentes","Id=$red->IdDoc");
                if($doc->IdCarrera==$row->Id){
                    $withBg = ($flag%2)? false : true;
                    $pdf->Cell( 10, 12, toUTF("$flag"),1,0,"C",$withBg );
                    $pdf->Cell( 30, 12, toUTF("$doc->Codigo"),1,0,"C",$withBg );
                    $pdf->Cell( 105, 12, toUTF("$doc->DatosPers"),1,0,"L",$withBg );
                    $pdf->Cell( 45, 12, toUTF("$red->Cod"),1,0,"C",$withBg );
                    $flag++;
                }
            }

        } 
        $pdf->SetFont('Arial','B',14);
        $pdf->Output();
    }


    function splitStr( $str )
    {
        $res = "";
        for( $i=0; $i<strlen($str); $i++ ){

            if( $str[$i] == " " )
                break;

            $res .= $str[ $i ];
        }
        return $res;
    }

//  Sustentaciones del director de tesis. 161
    public function sustenDirect(){ 
        $carreras=$this->dbRepo->getTable("dicCarreras");
        $pdf = new GenSexPdf();
        $totis=0;
        foreach ($carreras->result() as $row) {
            $pdf->AddPageEx( "P", "A4", 0, 0 );
            // $pdf->AddFont(  );
            $pdf->Image( "vriadds/pilar/imag/pilar-head.jpg", 10, 10, 190, 18 );
            $pdf->SetDrawColor( 170, 170, 170 );
            $pdf->Line( 10, 30, 200, 30 );
            $pdf->Ln(23);
            // $pdf->SetFont('Arial','B',14);

            $pdf->establecerfuente('TRUEBebasNeueRegular','','TRUEBebasNeueRegular.php');
            $pdf->SetFont('TRUEBebasNeueRegular','',20);
            $pdf->Cell( 190, 8, toUTF("DOCENTES DE LA ESCUELA PROFESIONAL DE :"),0,1,"C" );
            $pdf->Ln(5);
            $pdf->Cell( 190, 8, toUTF("$row->Nombre"),0,1,"C" );

            $pdf->SetFont('TRUEBebasNeueRegular','',11);
            $pdf->Ln(5);
            $i=1;
            
            $pdf->SetDrawColor  ( 170, 170, 170 );
            $pdf->SetAligns     (array('C','C','C','C','C'));
            $pdf->setFontSize   (array(12,12,12,12,7));
            $pdf->SetWidths     (array(10,30,30,80,30));
            $pdf->Row(
                array(
                    toUTF("Nº"),
                    toUTF("CODIGO"),
                    toUTF("DNI"),
                    toUTF("DOCENTE"),
                    toUTF("PY DIRIGIDOS"),
                )
            );
            // if ($row->Id==19)$row->Id=18;

            $pdf->SetFont('TRUEBebasNeueRegular','',12);
            $docentes=$this->dbRepo->getTable("tblDocentes","IdCarrera='$row->Id' ORDER BY Apellidos ASC ");
            foreach ($docentes->result() as $doc) {
                $count=0;
                $tesis=$this->dbPilar->getSnapView('tesTramites',"IdJurado4=$doc->Id AND Tipo=3");
                foreach ($tesis->result() as $ron) {
                    if ($this->dbPilar->getTotalRows('tesSustens',"IdBorrador=$ron->Id AND Fecha BETWEEN '20170101' AND '20181231' ")) {
                        $count=$count+1;
                    }
                }
                // $totaltesis=$this->dbPilar->getTotalRows('tesTramites',"IdJurado4=$doc->Id AND Tipo=3");
                $doce="$doc->Apellidos, $doc->Nombres";
                if ($count>0) {
                        # code...
                    $pdf->SetDrawColor  ( 170, 170, 170 );
                    $pdf->setFontSize   (array(8,8,8,8,8));
                    $pdf->SetAligns     (array('C','C','C','L','C'));
                    $pdf->SetWidths     (array(10,30,30,80,30));
                    $pdf->Row(
                        array(
                            $i,
                            toUTF($doc->Codigo),
                            toUTF($doc->DNI),
                            $doce,
                            $count,

                        )
                    );
                    $i++;
                    $totis=$totis+1;
                }
            }
        }
        // $pdf->Cell( 190, 8, toUTF("Total : $totis"),0,1,"C" );
        $pdf->Output();

    }

    public function lineasbyCarrera()
    {
        $carreras=$this->dbRepo->getTable("dicCarreras");
        $pdf = new GenSexPdf();

        foreach ($carreras->result() as $row) {
            $pdf->AddPageEx( "P", "A4", 0, 0 );
            // $pdf->AddFont(  );
            $pdf->Image( "vriadds/pilar/imag/pilar-head.jpg", 10, 10, 190, 18 );
            $pdf->SetDrawColor( 170, 170, 170 );
            $pdf->Line( 10, 30, 200, 30 );
            $pdf->Ln(23);
            $pdf->SetFont('Arial','B',14);

            $pdf->Cell( 190, 8, toUTF("LINEAS DE INVESTIGACIÓN ESCUELA PROFESIONAL"),0,1,"C" );
            $pdf->establecerfuente('TRUEBebasNeueRegular','','TRUEBebasNeueRegular.php');
            $pdf->SetFont('TRUEBebasNeueRegular','',20);

            $pdf->Ln(10);
            $pdf->Cell( 190, 8, toUTF("$row->Nombre"),0,1,"C" );

            $pdf->SetFont('Arial','',11);
            $pdf->Ln(5);
            $i=1;
            $pdf->SetDrawColor  ( 170, 170, 170 );
            $pdf->SetAligns     (array('C','C','C','C'));
            $pdf->setFontSize   (array(13,13,13,13,13));
            $pdf->SetWidths     (array(10,25,90,10,50));
            $pdf->SetFont('Arial','B',14);
            $pdf->Row(
                array(
                    toUTF("Nº"),
                    toUTF("Estado"),
                    toUTF("Linea de la Escuela Profesional"),
                    toUTF("#T"),
                    toUTF("Linea Transversal"),
                )
            );
            if ($row->Id==19)$row->Id=18;
            
            $lineas=$this->dbRepo->getTable("tblLineas","IdCarrera='$row->Id' ORDER BY Estado DESC, Nombre ASC ");
            foreach ($lineas->result() as $line) {
                $tesislin=$this->dbPilar->getTotalRows('tesTramites',"IdLinea=$line->Id AND Estado>0 AND Anio = 2018");
                $estado="Habilitado";
                if($line->Estado==0)$estado="Desabilitado";
                $pdf->SetDrawColor  ( 170, 170, 170 );
                $pdf->setFontSize   (array(8,8,7,7,7));
                $pdf->SetAligns     (array('C','C','J','C','L'));
                $pdf->SetWidths     (array(10,25,90,10,50));
                $pdf->Row(
                    array(
                        $i,
                        $estado,
                        toUTF("$line->Nombre"),
                        $tesislin,
                        toUTF($this->dbRepo->getOneField("dic_LineasVRI","Nombre","Id=$line->id_lineaV")),
                    )
                );
                $i++;
            }
        }

        $pdf->AddPageEx( "P", "A4", 0, 0 );
        $pdf->Output();
    }


public function inLineasUNAP(){
    // Lineas por carrera, escuela, actualizado el 19-07-2020

    $this->load->view("pilar/head");
    echo "<div class='col-md-12'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<center><h1>REPORTE DE LINEAS INSTITUCIONAL</h1></center>";
    $repo=$this->dbRepo->getTable("tblLineas","Estado='1' ORDER BY IdCarrera  ASC, Nombre ASC");

      echo "<table style='width:100%' class='table-striped'>
      <tr class='span'>
      <th>N°</th>
      <th>Linea</th> 
      <th>Sub-Linea</th>
      <th>Escuela Profesional</th>
      </tr>";
      $flag=1;
      foreach($repo->result() as $row){
       $NombreLin=$this->dbRepo->getOneField("dic_LineasVRI","Nombre","Id=$row->id_lineaV");
       $carrera=$this->dbRepo->inCarrera($row->IdCarrera);
       echo "<tr >
       <td>$flag</td>
       <td>$NombreLin</td>
       <td>$row->Nombre</td> 
       <td>$carrera</td>
       </tr>";
       $flag++; 
   }
   echo "</table> </div>";
}

public function DatosDocente(){
 $pdf = new GenSexPdf();
 $doc=$this->dbRepo->getSnapView("tblDocentes","Regina=1");
 foreach($doc->result() as $row){
        // $pdf->AddPage();
    $pdf->AddPageEx( "P", "A4", 0, 0 );
    $pdf->Image( "vriadds/pilar/imag/pilar-head.jpg", 10, 7, 190, 18 );
    $pdf->SetDrawColor( 170, 170, 170 );
    $pdf->Line( 10, 30, 200, 30 );
    $pdf->Ln(23);
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell( 190, 8, toUTF("INFORMACIÓN DOCENTE EN PILAR"),0,1,"C" );
    $pdf->SetFont('Arial','B',14);
    $pdf->Ln(5);
    $pdf->Cell( 190, 8, toUTF("INFORMACIÓN PERSONAL"),0,1 );
    $pdf->Ln(5);
        // Apellidosc
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("APELLIDOS"),0,0 );
    $pdf->SetFont('Arial','',12);
    $pdf->Cell( 130, 7, toUTF(": $row->Apellidos"),0,1 );
        // Nombres
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("NOMBRES"),0,0 );
    $pdf->SetFont('Arial','',12);
    $pdf->Cell( 130, 7, toUTF(": $row->Nombres"),0,1 );
        // DNI
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("DNI"),0,0 );
    $pdf->SetFont('Arial','',12);
    $pdf->Cell( 130, 7, toUTF(": $row->DNI"),0,1 );
        // Codigo
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("CODIGO"),0,0 );
    $pdf->SetFont('Arial','',12);
    $pdf->Cell( 130, 7, toUTF(": $row->Codigo"),0,1 );
         // Correo
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("E-MAIL"),0,0 );
    $pdf->SetFont('Arial','',12);
    $pdf->Cell( 130, 7, toUTF(": $row->Correo"),0,1 );
         // Telefono
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("TELEFONO"),0,0 );
    $pdf->SetFont('Arial','',12);
    $pdf->Cell( 130, 7, toUTF(": $row->NroCelular"),0,1 );
        // iNVESTIGADOR
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("INVESTIGADOR"),0,0 );
    $pdf->SetFont('Arial','',12);
    $pdf->Cell( 130, 7, toUTF(($row->Regina==1)?": REGINA":": DINA"),0,1 );

    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell( 35, 7, toUTF("INFORMACIÓN DE LA INSTITUCIÓN"),0,1 );
    $pdf->Ln(5);
        // Facultad
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("FACULTAD"),0,1 );
    $pdf->SetFont('Arial','',10);
    $pdf->Cell( 130, 7, toUTF($this->dbRepo->getOneField("dicFacultades","Nombre","Id=$row->IdFacultad")),0,1 );
        // Escuela Prof
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("ESCUELA"),0,1 );
    $pdf->SetFont('Arial','',10);
    $pdf->Cell( 130, 7, toUTF($this->dbRepo->getOneField("dicCarreras","Nombre","Id=$row->IdCarrera")),0,1 );
        // Categoria
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("CATEGORIA DOCENTE"),0,1 );
    $pdf->SetFont('Arial','',10);
    $pdf->Cell( 130, 7, toUTF($this->dbRepo->getOneField("dicCategorias","Nombre","Id=$row->IdCategoria")),0,1 );

        // Grados Académicos
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell( 35, 7, toUTF("GRADOS ACADÉMICOS REGISTRADOS"),0,1 );
    $pdf->SetFont('Arial','',10);
    $gradi=$this->dbPilar->getSnapView("docEstudios","IdDocente=$row->Id");
    foreach($gradi->result() as $riw){
     $pdf->Cell( 130, 7, toUTF("$riw->Mencion"),0,1 );
 }
        // Líneas de Investigación
 $pdf->SetFont('Arial','B',11);
 $pdf->Cell( 35, 7, toUTF("LINEAS DE INVESTIGACIÓN EN PILAR"),0,1 );
 $pdf->SetFont('Arial','',10);
 $Lin=$this->dbPilar->getSnapView("docLineas","IdDocente=$row->Id");
 foreach($Lin->result() as $raw){
     $pdf->Cell( 130, 7, toUTF($this->dbRepo->getOneField("tblLineas","Nombre","Id=$raw->IdLinea")),0,1 );
 }
}
$pdf->Output();
}

public function ProyectosdeTesis(){
    $pdf = new GenSexPdf();
    $pdf->SetMargins( 10, 30, 10 );
    $carrie=$this->dbRepo->getTable("dicCarreras");
    foreach($carrie->result() as $carre){
        $pdf->AddPageEx( "P", "A4", 2, 0 );
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190,5,toUTF("PROYECTOS DE TESIS DE LA ESCUELA PROFESIONAL DE"),0,1,'C');
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(190,8,toUTF($carre->Nombre),0,1,'C');
        $pdf->Ln(5);
        $pdf->SetFont( "Arial", '', 10 );

        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->setFontSize(array(8,7,8,7,9));
        $pdf->SetAligns(array('C','J','C','C','C'));
        $pdf->SetWidths(array(10,100,30,20,30));
        $flag=1;
        $tes=$this->dbPilar->getSnapView("tesTramites","Tipo > 0 AND IdCarrera=$carre->Id ORDER BY IdCarrera ASC");
        foreach ($tes->result() as $row) {
            // if($row->Tipo==1)$estati="PROYECTO DE TESIS";
            // if($row->Tipo==2)$estati="BORRADOR DE TESIS";
            if($row->Tipo==3){

            $pdf->Row(
                array(
                    $flag,
                    toUTF($this->dbPilar->inTitulo($row->Id)." / ".$this->dbRepo->inLineaInv($row->IdLinea)),
                    toUTF($this->dbPilar->inTesistas($row->Id)),
                    $estati,
                    $row->FechModif
                )
            );
            }
            $flag++;
        }
    }
    $pdf->Output();
}
public function docentebyCarrera(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-9'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo " <h3 class='text-center'> <I> DOCENTES POR CARRERA Y PROYECTOS ANUALES</I></h3}>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th> Número        </th>
    <th> Carrera     </th>
    <th> Num Doc   </th>
    <th> 2016   </th>
    <th> 2017   </th>
    <th> 2018   </th>
    <th> 2019   </th>
    <th> TOTAL  </th>
    </tr>";
    $flag=1;
    $carreras=$this->dbRepo->getSnapView("dicCarreras");
    foreach ($carreras->result() as $row) {
        $docentes=$this->dbRepo->getSnapView("tblDocentes","IdCarrera=$row->Id")->num_rows();
        $npy2016=$this->dbPilar->getSnapView("tesTramites","Tipo=3 AND IdCarrera=$row->Id AND Anio =2016")->num_rows();
        $npy2017=$this->dbPilar->getSnapView("tesTramites","Tipo=3 AND IdCarrera=$row->Id AND Anio =2017")->num_rows();
        $npy2018=$this->dbPilar->getSnapView("tesTramites","Tipo=3 AND IdCarrera=$row->Id AND Anio =2018")->num_rows();
        $npy2019=$this->dbPilar->getSnapView("tesTramites","Tipo=3 AND IdCarrera=$row->Id AND Anio =2019")->num_rows();
        $tot=$this->dbPilar->getSnapView("tesTramites","IdCarrera=$row->Id")->num_rows();
        echo"<tr>
        <td>$flag</td>
        <td>$row->Nombre</td>
        <td>$docentes</td>
        <td>$npy2016</td>
        <td>$npy2017</td>
        <td>$npy2018</td>
        <td>$npy2019</td>
        <td>$tot</td>
        </tr>";
        $flag++;
    }
    echo "</table>";

}
public function credenciales3mt() {
    $pdf = new GenSexPdf();
    $tesis=$this->dbPilar->getSnapView("3mtPostul","Id>0","ORDER by IdCarrera");

    $pdf->addfont('Akrobat-Bold_0','','Akrobat-Bold_0.php');


    foreach ($tesis->result() as $row) {
        $pdf->AddPageEx( "P", "A4", 0, 0 );
        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->Image( "vriadds/3mtunap/credencial3mt2018.jpg", 7, 10,200, 260 );
        $pdf->Ln(115);
        $pdf->SetFont('Akrobat-Bold_0','',20);
        $pdf->SetTextColor(168,207,69);
            // $pdf->Cell( 190, 10, toUTF("Código"),0,1,"C" );
        $pdf->Ln(10);
        $pdf->SetTextColor(62,64,149);
        $pdf->SetFont('Akrobat-Bold_0','',55);
        $pdf->Cell( 0, 10, toUTF($row->Codigo),0,1,"R" );
        $pdf->SetFont('Akrobat-Bold_0','',20);
        $pdf->Ln(10);
        $pdf->SetTextColor(168,207,69);
            // $pdf->Cell( 190, 10, toUTF("Nombres y Apellidos"),0,1,"C" );
        $pdf->SetFont('Akrobat-Bold_0','',45);
        $pdf->SetTextColor(62,64,149);
        $pdf->Ln(5);  
        $pdf->Cell( 190, 10, toUTF($this->dbPilar->getOneField("tblTesistas","Nombres","Id=$row->IdTesista")),0,1,"C" );
        $pdf->Ln(5);
        $pdf->SetFont('Akrobat-Bold_0','',30);
        $pdf->Cell( 190, 10, toUTF($this->dbPilar->getOneField("tblTesistas","Apellidos","Id=$row->IdTesista")),0,1,"C" );
        $pdf->Ln(10);
        $pdf->SetTextColor(168,207,69);
        $pdf->SetFont('Akrobat-Bold_0','',20);
        $pdf->Cell( 190, 10, toUTF("Escuela Profesional"),0,1,"C" );
        $pdf->Ln(5);
        $pdf->SetTextColor(62,64,149);
        $pdf->SetFont('Akrobat-Bold_0','',30);
        $pdf->Cell( 190, 10, toUTF($this->dbRepo->inCarrera($row->IdCarrera)),0,1,"C" );
    }
    $pdf->Output();
}

public function credenciales3mtJUD(){
    $pdf = new GenSexPdf();
    $tesis=$this->dbComs->getSnapView("3mtJurados","Id>1");
    foreach ($tesis->result() as $row) {
        $pdf->AddPageEx( "P", "A4", 0, 0 );
        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->Image( "vriadds/3mtunap/crd-jud.jpg", 7, 10,200, 260 );
        $pdf->Ln(115);
        $pdf->SetFont('Arial','',20);
        $pdf->SetTextColor(168,207,69);
            // $pdf->Cell( 190, 10, toUTF("Código"),0,1,"C" );
        $pdf->Ln(10);
        $pdf->SetTextColor(62,64,149);
        $pdf->SetFont('Arial','',50);
            // $pdf->Cell( 190, 10, toUTF($row->Codigo),0,1,"C" );

        $pdf->SetFont('Arial','B',20);
        $pdf->Ln(10);
        $pdf->SetTextColor(168,207,69);
            // $pdf->Cell( 190, 10, toUTF("Nombres y Apellidos"),0,1,"C" );
        $pdf->SetFont('Arial','B',45);
        $pdf->SetTextColor(62,64,149);
        $pdf->Ln(15);  
        $pdf->Cell( 190, 10, toUTF(" $row->Nombres"),0,1,"C" );
        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',30);
        $pdf->Cell( 190, 10, toUTF($row->Apellidos),0,1,"C" );
        $pdf->Ln(10); 
        $pdf->SetTextColor(168,207,69);
        $pdf->SetFont('Arial','B',20);
            // $pdf->Cell( 190, 10, toUTF("Escuela Profesional"),0,1,"C" );
        $pdf->Ln(5);
        $pdf->SetTextColor(62,64,149);
        $pdf->SetFont('Arial','B',30);
            // $pdf->Cell( 190, 10, toUTF("ORGENIZING"),0,1,"C" );
    }
    $pdf->Output();
}

public function credenciales3mtoRG(){
    $pdf = new GenSexPdf();
    $tesis=$this->dbComs->getSnapView("tblOrganizacion","IdGrupo = 1");
    foreach ($tesis->result() as $row) {
        $pdf->AddPageEx( "P", "A4", 0, 0 );
        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->Image( "vriadds/3mtunap/crd-or.jpg", 7, 10,200, 260 );
        $pdf->Ln(115);
        $pdf->SetFont('Arial','',20);
        $pdf->SetTextColor(168,207,69);
        // $pdf->Cell( 190, 10, toUTF("Código"),0,1,"C" );
        $pdf->Ln(10);
        $pdf->SetTextColor(62,64,149);
        $pdf->SetFont('Arial','',50);
        // $pdf->Cell( 190, 10, toUTF($row->Codigo),0,1,"C" );

        $pdf->SetFont('Arial','B',20);
        $pdf->Ln(10);
        $pdf->SetTextColor(168,207,69);
        // $pdf->Cell( 190, 10, toUTF("Nombres y Apellidos"),0,1,"C" );
        $pdf->SetFont('Arial','B',45);
        $pdf->SetTextColor(62,64,149);
        $pdf->Ln(15);  
        $pdf->Cell( 190, 10, toUTF("$row->Grado $row->Nombres"),0,1,"C" );
        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',30);
        $pdf->Cell( 190, 10, toUTF($row->Apellidos),0,1,"C" );
        $pdf->Ln(10);
        $pdf->SetTextColor(168,207,69);
        $pdf->SetFont('Arial','B',20);
        // $pdf->Cell( 190, 10, toUTF("Escuela Profesional"),0,1,"C" );
        $pdf->Ln(5);
        $pdf->SetTextColor(62,64,149);
        $pdf->SetFont('Arial','B',30);
        // $pdf->Cell( 190, 10, toUTF("ORGENIZING"),0,1,"C" );
    }
    $pdf->Output();
}

public function Inscritos3mt2017(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/3mtunap/3mt_baner.jpg")."'></img>";
    echo "<h3 class='text-center'>RELACIÓN DE INSCRITOS AL CONCURSO<BR> <I>MI TESIS EN 3 MINUTOS</I></h3>";
    echo "<h3 class='text-center'>2018</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>NUM</th>
    <th>COD</th>
    <th>FIRSNAME</th>
    <th>LASTNAME</th> 
    <th>CARREER</th>
    <th>DATE-R</th>
    <th>SEMESTR</th>
    <th>EST</th>
    <th>TYPE</th>
    </tr>";
    $tesis=$this->dbPilar->getSnapView("3mtPostul","Id>0", "ORDER by Codigo");
    $flag=1;

    foreach($tesis->result() as $row){
        if($row->OK == 1){
            $estilotesis=$this->dbPilar->inTramByTesista1("$row->IdTesista");
            $etsii=$this->dbPilar->inEstado("$estilotesis");
            $tipillo=$this->dbPilar->inTipo("$estilotesis");
            $Apellidos=$this->dbPilar->getOneField("tblTesistas","Apellidos","Id=$row->IdTesista");
            $Nombres=$this->dbPilar->getOneField("tblTesistas","Nombres","Id=$row->IdTesista");
            $semen=$this->dbPilar->getOneField("tblTesistas","SemReg","Id=$row->IdTesista");
            echo"<tr>
            <td>$flag</td>
            <td>$row->Codigo</td>
            <td>".toUTF($Apellidos)."</td> 
            <td>".toUTF($Nombres)."</td>
            <td>".$this->dbRepo->inCarrera($row->IdCarrera)."</td>
            <td>$row->Fecha</td>
            <td> $semen </td>
            <td> $etsii </td>
            <td> $tipillo </td>
            </tr>";
            $flag++;
        }
    }
    echo "</table>";
    echo "</div>";
}

public function InscritosPoster2017(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-9'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/3mtunap/poster_baner.jpg")."'></img>";
    echo " <h3 class='text-center'> <I>MI PROYECTO DE TESIS EN UN POSTER 2018</I></h3}>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th> Número        </th>
    <th> CodPoster     </th>
    <th> CodTesistas   </th>
    <th> SemestReal    </th>
    <th> Estudiantes   </th>
    <th> Carrera       </th>
    <th> FechaReg      </th>
    <th> Estado Actual </th>
    <th> Tipo </th>
    <th> Semestre </th>
    </tr>";

    $tesis=$this->dbPilar->getSnapView("2posTer","apto=1" ,"ORDER by IdCarrera");
    $flag=1;

    foreach ($tesis->result() as $row ){
        $datos = $this->dbPilar->inTesistas( $row->IdProyecto );
        $estad = $this->dbPilar->inEstado( $row->IdProyecto );

        $tram = $this->dbPilar->getSnapRow( "tesTramites", "Id=$row->IdProyecto" );
        $cod1 = $this->dbPilar->getOneField( "tblTesistas", "Codigo", "Id=$tram->IdTesista1" );
        $cod2 = $this->dbPilar->getOneField( "tblTesistas", "Codigo", "Id=$tram->IdTesista2" );


        $telefono1 = $this->dbPilar->getOneField("tblTesistas", "NroCelular" ,"Id=$tram->IdTesista1");
        $telefono2 = $this->dbPilar->getOneField("tblTesistas", "NroCelular" ,"Id=$tram->IdTesista2");


            // $alumno->items[0]->matricula->semestre;
        $alum = otiGetData( $cod1 );
        $semR = $alum->items[0]->matricula->semestre;
        $anio = $alum->items[0]->matricula->anio ."-". $alum->items[0]->matricula->periodo;

        echo "<tr>
        <td>$flag</td>
        <td> $row->Codigo </td>
        <td> $cod1 <br> $cod2 </td>
        <td> <p style='color:red'>$anio $semR</p> </td>
        <td>" .toUTF( $datos ). "</td>

        <td>" . $telefono1."-".$telefono2 ."</td>

        <td>" .$this->dbRepo->inCarrera($row->IdCarrera)."</td>
        <td>" .mlFechaNorm($row->Fecha). "</td>
        <td> $estad </td>
        <td>" .$this->dbPilar->inTipo("$row->IdProyecto")."</td>
        <td>" .$this->dbPilar->inSemesTesistas("$row->IdProyecto")."</td>
        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}



public function InscritosPoster2018s(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-9'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/3mtunap/poster_baner.jpg")."'></img>";
    echo " <h3 class='text-center'><I>MI PROYECTO DE TESIS EN UN POSTER 2018</I></h3}>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th style='width:5%'> Número        </th>
    <th style='width:5%'> CodPoster     </th>

    <th style='width:25%'> Estudiantes   </th>
    <th style='width:20%'> Escuela      </th>


    </tr>";

    $tesis=$this->dbPilar->getSnapView("2posTer","Id>0" ,"ORDER by IdCarrera");
    $flag=1;

    foreach ($tesis->result() as $row ){
        $datos = $this->dbPilar->inTesistas( $row->IdProyecto );
        $estad = $this->dbPilar->inEstado( $row->IdProyecto );

        $tram = $this->dbPilar->getSnapRow( "tesTramites", "Id=$row->IdProyecto" );
        $cod1 =$this->dbPilar->getOneField( "tblTesistas", "Codigo", "Id=$tram->IdTesista1" );
           // $cod2 = $this->dbPilar->getOneField( "tblTesistas", "Codigo", "Id=$tram->IdTesista2" );


        $telefono1 = $this->dbPilar->getOneField("tblTesistas", "NroCelular" ,"Id=$tram->IdTesista1");
        $telefono2 = $this->dbPilar->getOneField("tblTesistas", "NroCelular" ,"Id=$tram->IdTesista2");


        $alum = otiGetData( $cod1 );
        $semR = $alum->items[0]->matricula->semestre;
        $anio = $alum->items[0]->matricula->anio ."-". $alum->items[0]->matricula->periodo;

        echo "<tr>
        <td>$flag</td>
        <td> $row->Codigo </td>

        <td>" .toUTF( $datos ). "</td>

        <td>$semR</td>
        <td>$anio</td>
        <td>" .$this->dbRepo->inCarrera($row->IdCarrera)."</td>



        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}

public function comunicadoAmpCon(){
        // NOtificación de Correo Enviada 28  de Mayo
        // NOtificación de Correo Enviada 04 de   de Septiembre
        //$consulta=$this->dbFedu->getSnapView("integrantes","id>0 GROUP By  codDocente");


    $msg="      Señor(a) postulante al concurso MI TESIS EN UN POSTER, <br> <br>
    Se le informa que usted ha sido seleccionado para participar en el concurso MI TESIS EN UN POSTER a desarrollarse el día <b>19 de Noviembre a partir de las 8:30 am </b> en el CENTRO DE EDUCACIÓN CONTINUA (Al costado del Frigorífico), para lo cual deberá portar su DNI.

    Para corroborar su inscripción, ingrese al siguiente <a href='http://vriunap.pe/poster'>ENLACE</a> <br>
    <br>

    <p>*La capacitación se desarrollará el día Viernes 16 de Noviembre de 2018 a las 09:00 Hrs en el Laboratorio del Computo del Vicerrectorado de Investigación, Cafetín Universitario 2do Piso.</p>
    ";

    $str = "<body style='background:#E0E0E0; padding:25px'> <center> "
    . "<div style='background:white;width:600px;padding:14px;border:1px solid #B0B0B0'> "
    . "<div style='text-align:left;font-family:Arial'> "
    . "<center> <img src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."' height=60 ></img></center>"
    . "<div style='clear: both'></div>"
    . "<br> <p> $msg </p> <br><hr style='border:1px dotted #C0C0C0'> "
    . "<p style='font-size:10px;font-weight:bold'> Universidad Nacional de Ucayali - PUCALLPA <br>"
    . "Vicerrectorado de Investigación - VRI <br>Plataforma de Investigación y Desarrollo </p> </div></div>"
    . "</center> </body>";

    $flag=0;
    $consulta = $this->dbPilar->getSnapView("2posTer","apto=1");
    foreach ($consulta->result() as $key) {
        $tram=$this->dbPilar->inProyTram($key->IdProyecto); 
        $mail=$this->dbPilar->inCorreo("$tram->IdTesista1");
        if($mail){
                    // $this->genmailer->sendHtml("$mail", "CONCURSO: POSTER 2018", $str);
            echo "$flag  :: $mail <br>";
        }
        $flag++;
    }

    $this->genmailer->sendHtml("ftorres@unap.edu.pe", "CONCURSO:POSTER 2018", $str);
    $this->genmailer->sendHtml("jcesarblues@live.com", "CONCURSO:POSTER 2018", $str);
    echo "Oki44";
}

public function posterListaTemp(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/3mtunap/poster_baner.jpg")."'></img>";
    echo " <h3 class='text-center'>RELACIÓN DE INSCRITOS AL CONCURSO<BR> <I>MI PROYECTO DE TESIS EN UN POSTER 2017</I></h3}>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>Número</th>
    <th>Codigo</th>
    <th style='width:30%'>Estudiantes</th>
    <th>Carrera</th>
    <th>Fech - Reg</th>
    <th>Estado Actual</th>
    <th>Tipo</th>
    <th>Semest</th>
    </tr>";
    $tesis=$this->dbPilar->getSnapView("2posTer","OK=1","ORDER by Codigo ASC , Fecha DESC");
    $flag=1;
    foreach($tesis->result() as $row){
        $chicos=$this->dbPilar->inTesistas("$row->IdProyecto");
        $tes= $this->dbPilar->getOneField("tesTramites","IdTesista1","Id=$row->IdProyecto");
        echo "<tr>
        <td>$flag</td>
        <td>$row->Codigo</td>
        <td>".toUTF("$chicos")."</td> 
        <td>".$this->dbRepo->inCarrera($row->IdCarrera)."</td>
        <td>$row->Fecha</td>
        <td>".$this->dbPilar->inTipo("$row->IdProyecto")."</td>
        <td>".$this->dbPilar->inCorreo("$tes")."</td>
        <td>".$this->dbPilar->inCelTesista("$tes")."</td>
        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}
    // Funcion 2016     NO SIRVEEEE JAAJAJ 
    // public function reporte3concursos(){
    //     echo "<table style='width:100%'>
    //             <tr>
    //             <th>CODIGO</th>
    //             <th>Firstname</th>
    //             <th>Lastname</th> 
    //             <th>Age</th>
    //             <th>Ag</th>

    //             </tr>";
    //     $tesis=$this->dbComs->getSnapView("tblGrupos","Id>0");
    //     $flag=1;
    //     $team=array(1=>"Centros de Invetigación y Alto Rendimiento en Camélidos Sudamericanos CIP La Raya",2=>"ESTABLO MODELO CIP CHUQUIBAMBILLA",3=>"MODELO DE VIVIENDA RURAL");
    //     foreach($tesis->result() as $row){
    //         $integrantes=$this->dbComs->getSnapView("tblInscritos","Id>0","");
    //         foreach($integrantes->result() as $raw){
    //             echo"<tr>
    //                     <td>$flag</td>
    //                     <td>".$team[$row->IdConvoc]."</td>
    //                     <td>".$raw->Apellidos."</td> 
    //                     <td>".$raw->Nombres."<td>
    //                     <td>".$this->dbRepo->inCarrera($raw->IdCarrera)."</td>
    //                 </tr>";
    //             $flag++;
    //         }
    //     }

    //     echo "</table>";
    // } 

public function reportePosterII(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/3mtunap/poster_baner.jpg")."'></img>";
    echo " <h3 class='text-center'>RELACIÓN DE INSCRITOS AL CONCURSO<BR> <I>MI PROYECTO DE TESIS EN UN POSTER 2016 -II </I></h3}>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>Número</th>
    <th>Codigo</th>
    <th>Apellidos </th>
    <th>Nombres</th>
    <th>Carrera</th>
    </tr>";
    $tesis=$this->dbComs->getSnapView("posPosters","Id>0","ORDER by Codigo");
    $flag=1;
    foreach($tesis->result() as $row){
        $carrera=$this->dbPilar->getOneField("tblTesistas","IdCarrera","Id=$row->IdTesista1");
        echo"<tr>
        <td>$flag</td>
        <td>$row->Codigo</td>
        <td>".$this->dbPilar->getOneField("tblTesistas","Apellidos","Id=$row->IdTesista1")."</td> 
        <td>".$this->dbPilar->getOneField("tblTesistas","Nombres","Id=$row->IdTesista1")."</td>
        <td>".$this->dbRepo->inCarrera($carrera)."</td>
        </tr>";
        $flag++;
    }

    echo "</table>";
}

public function reportePosterI(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/3mtunap/poster_baner.jpg")."'></img>";
    echo " <h3 class='text-center'>RELACIÓN DE INSCRITOS AL CONCURSO<BR> <I>MI PROYECTO DE TESIS EN UN POSTER 2016 -I </I></h3}>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>Número</th>
    <th>Codigo</th>
    <th>Apellidos </th>
    <th>Nombres</th>
    <th>Carrera</th>
    </tr>";
    $tesis=$this->dbComs->getSnapView("poster_datos","Id>0","ORDER by escuela");
    $flag=1;
    foreach($tesis->result() as $row){
        echo"<tr>
        <td>$flag</td>
        <td>$row->codigo</td>
        <td>".strtoupper($row->apellidop." ".$row->apellidom)."</td> 
        <td>".strtoupper($row->nombre)."</td>
        <td>".strtoupper($row->escuela)."</td>
        </tr>";
        $flag++;
    }

    echo "</table>";
}

public function reportInscritos3mt(){
    $pdf = new GenSexPdf();
    $pdf->AddPageEx( "P", "A4", 0, 0 );
    $pdf->SetDrawColor( 170, 170, 170 );
    $pdf->Image( "vriadds/3mtunap/3mt_baner.jpg", 10, 10, 190, 20 );
    $pdf->SetFont('Times','B',14);
    $pdf->ln(28);
    $pdf->Cell( 190, 0, toUTF("INSCRITOS CONCURSO 3MT®"),0,1,"C" );
    $pdf->Cell( 190, 0, toUTF("_______________________________"),0,1,"C" );

    $pdf->ln(5);
    $pdf->SetFont('Times','B',11);
    $pdf->Cell( 10, 5, toUTF("Nro"),1,0,"C" );
    $pdf->Cell( 30, 5, toUTF("CODIGO"),1,0,"C" );
    $pdf->Cell( 75, 5, toUTF("Apellidos y Nombres"),1,0,"C" );
    $pdf->Cell( 75, 5, toUTF("Escuela Profesional"),1,1,"C" );
    $flag=1;
    $tesis=$this->dbPilar->getSnapView("3mtPostul","Id>0","ORDER by IdCarrera");
    $pdf->SetFont('Times','',10);
    foreach($tesis->result() as $row){
        $pdf->Cell( 10, 5, toUTF("$flag"),1,0,"C" );
        $pdf->SetFont('Times','',12);
        $pdf->Cell( 30, 5, toUTF($row->Codigo),1,0,"C" );
        $pdf->SetFont('Times','',10);
        $pdf->Cell( 75, 5, toUTF($this->dbPilar->getOneField("tblTesistas","Apellidos","Id=$row->IdTesista").", ".$this->dbPilar->getOneField("tblTesistas","Nombres","Id=$row->IdTesista")),1,0,"L" );
        $pdf->Cell( 75, 5, toUTF($this->dbRepo->inCarrera($row->IdCarrera)),1,1,"L" );
            // $pdf->Cell( 75,  5, toUTF($this->dbPilar->getOneField("tblTesistas","SemReg","Id=$row->IdTesista")),1,0,'L');
        $flag++;
    }
    $pdf->SetFont('Arial','B',14);
    $pdf->Output();
}



public function resumenPostul(){
    $pdf = new GenSexPdf();
    $flag=1;
    $tesis=$this->dbPilar->getSnapView("3mtPostul","Id>0","ORDER by Codigo");
    foreach($tesis->result() as $row){
        $pdf->AddPageEx( "P", "A4", 0, 0 );
        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->Image( "vriadds/3mtunap/3mt_baner.jpg", 10, 10, 190, 20 );
        $pdf->ln(28);
        $pdf->SetFont('Times','',30);
        $pdf->Cell( 190, 0, toUTF("Código: $row->Codigo"),0,1,"R" );
        $pdf->SetFont('Times','',12);
        $pdf->ln(15);
        $pdf->SetFont('Times','B',14);
        $pdf->Multicell( 190, 5, toUTF($row->Titulo),0,"C",0 );
        $pdf->ln(10);
        $pdf->SetFont('Times','',11);
        $pdf->Cell( 190, 5, toUTF($this->dbPilar->getOneField("tblTesistas","Apellidos","Id=$row->IdTesista").", ".$this->dbPilar->getOneField("tblTesistas","Nombres","Id=$row->IdTesista")),0,1,"R" );
        $pdf->SetFont('Times','',10);
        $pdf->Cell( 190, 5, toUTF("ESCUELA PROFESIONAL DE ".$this->dbRepo->inCarrera($row->IdCarrera)),0,1,"R" );
        $pdf->SetFont('Times','',11);
        $pdf->Cell( 190, 5, toUTF($this->dbPilar->getOneField("tblTesistas","Correo","Id=$row->IdTesista")),0,1,"R");
        $pdf->ln(10);
        $pdf->SetFont('Times','',12);
        $pdf->Multicell(190,5,toUTF($row->Resumen),0,"J",0 );
        $flag++;
    }
    $pdf->Output();
}

public function recordatorioFEDU(){
        // NOtificación de Correo Enviada 28  de Mayo
        // NOtificación de Correo Enviada 04 de   de Septiembre
        // NOtificación de Correo Enviada 03 DE eNERO 2019
        // $consulta=$this->dbFedu->getSnapView("integrantes","id>0 GROUP By  codDocente");
    $consultaDoc=$this->dbRepo->getSnapView('tblDocentes',"IdCategoria<9 AND Activo=6");

    $msg="Señor(a) Docente, <br> <br> Se le informa que ya se encuetra habilitada la plataforma para registrar los nuevos proyectos de investigación docente correspondientes al año 2019. <br><br> Fecha límite:<b>  15 de Enero del 2019 </b>. <br> <br>Para acceder a la plataforma FEDU puede hacer click en el siguiente enlace: <a href='http://vriunap.pe/fedu'>http://vriunap.pe/fedu</a>.<br><br>
    <br><b>Notas: </b><ul>
    <li>Recuerde registrar el artículo científico para finalizar el proyecto 2018 y cargar un nuevo proyecto.</li>
    <li>Descargar la Directiva FEDU <a href='http://vriunap.pe/fedu/includefile/docs/reglamento_FEDU.pdf'>DESCARGAR</a></li>
    ";
    $flag=1;
    foreach ($consultaDoc->result() as $key) {
        if($key->Correo){
            $this->genmailer->mailFEDU($key->Correo, "FEDU : REGISTRO DE PROYECTOS 2019", $msg);
            echo "$flag| $key->codDocente  / Send: Ok>".$key->Correo."<br>";
            echo "$key->codDocente | $key->Apellidos, $key->Nombres <br>";
            $flag++;
        }
    }
    $this->genmailer->mailFEDU("torresfrd@gmail.com", "FEDU : PROYECTOS FEDU 2019", $msg);
    $this->genmailer->mailFEDU("ftorres@unap.edu.pe", "FEDU : PROYECTOS FEDU 2019", $msg);
}

public function RecuerdaFedu(){
    $flag=0;
    
    $doc=$this->dbFedu->getSnapView("docIntegrantes","Anio=2019 AND Tipo<3");
    echo "<table class='table-bordered'><thead>
    <tr> 
    <th>Num</th>
    <th>ID</th>
    <th>ANIO</th>
    <th>AVAN</th>
    <th>Cod</th>
    <th>APELLIDOS</th>
    <th>NOMBRES</th>
    <th>CELULAR</th>
    </tr>";
    foreach ($doc->result() as $row) {
        $lname=$this->dbRepo->getOneField("tblDocentes","Apellidos","Id=$row->IdDoc");
        $name=$this->dbRepo->getOneField("tblDocentes","Nombres","Id=$row->IdDoc");
        $mail=$this->dbRepo->getOneField("tblDocentes","Correo","Id=$row->IdDoc");
        $celular=$this->dbRepo->getOneField("tblDocentes","NroCelular","Id=$row->IdDoc");
        $cat=$this->dbRepo->getOneField("tblDocentes","IdCategoria","Id=$row->IdDoc");
        $cod=$this->dbRepo->getOneField("tblDocentes","Codigo","Id=$row->IdDoc");
        $avan=$this->dbFedu->getOneField("docInformes","Periodo","IdProy=$row->IdProy ORDER BY Id DESC");
        $msg="Señor(a) $name <br>Docente de la UNA - PUCALLPA, <br> <br> Por medio del presente se le informa que deberá realizar su informe de avance del proyecto registrado para FEDU en el siguiente enlace: <a href='http://vriunap.pe/fedu'>http://vriunap.pe/fedu</a>, como máximo hasta el 18/09/2019 23:59:00 Hrs.<br><br>
            <br><b>Nota: </b><ul>
            <li>Recuerde que su proyecto actual deberá finalizar el 31 de Diciembre de 2019.</li>
            <li>Click en el enlace para descargar la directiva FEDU <a href='http://vriunap.pe/fedu/includefile/docs/reglamento_FEDU.pdf'>DESCARGAR</a></li>
            ";
        if(!$avan){
            $avan="DEBE";
        }
        if($cat<13 & $avan != 'SET2019'){
            $this->genmailer->mailFEDU("$mail", "UNAP VRI FEDU: AVANCE III 2019", "$msg");
            echo "  <tr>
                    <td>$flag</td>
                    <td>$row->IdProy</td><td>$row->Anio</td><td>$avan</td>
                    <td>$cod</td>
                    <td>$lname</td>
                    <td>$name</td>
                    <td>$mail</td> 
                    </tr>";
            $flag++;
        }
    }
    echo "</table>";
    $this->genmailer->mailFEDU("ftorres@unap.edu.pe", "UNA VRI FEDU: AVANCE III 2019", "Envios OK - Finalizado $msg");
}

public function docentesFeduMix(){
    $consulta=$this->dbFedu->getSnapView("proyecto");
    echo "<table style='width:30%'>
    <tr>
    <th>Id</th>
    <th>Titulo</th>
    <th>Periodo</th> 
    <th>Estado</th>
    <th>Informe</th>
    <th>Integrante</th>
    </tr>";
    foreach ($consulta->result() as $red) {
        $intis=$this->dbFedu->getSnapView("integrantes","idProyect=$red->id");
        $a="";
        foreach($intis->result() as $ref){
          $a = "$ref->codDocente<br>";
      }
      echo " <tr>
      <td>$red->titulo</td>
      <td>$red->estado</td>
      <td>$red->responsable</td> 
      <td>".$a."</td> 
      <td>///</td>
      </tr>";

  }
  echo "</table>";
}

public function pruebamail(){
        //$this->genmailer->sendMail("roenfi@hotmail.com", "Mensaje de Prueba", "Este es un mensaje de prueba");
}


public function certifis(){
        //$pdf = new GenSexPdf();
    $pdf = new FPDF( "L" );

    $pdf->SetFont("Arial","B",18);

    $table = $this->dbComs->getSnapView( "vw3mNotas" );


    foreach( $table->result() as $row ) {

        $pdf->AddPage();
        $pdf->Image("vriadds/3mtunap/certifi3m.jpg", 0, 0 );
        $pdf->Ln(80);
        $pdf->Cell( 135, 9, "", 0, 0, "L" );
        $pdf->Cell( 150, 9, toUTF($row->Tesista), 0, 1, "L" );
        CodeQR( $pdf, 255, 38, "VRI UNAP 2017", 116 );
    }
    $pdf->Output();
}


public function recordRevision( $carr=0, $tipo='C' )
{
    $carfilt = (!$carr)? "1" : "IdCarrera='$carr'";
    $tcarres = $this->dbRepo->getTable ( "vwLstCarreras", "$carfilt ORDER BY Carrera" );

    $pdf = new GenSexPdf();
    $pdf->SetMargins( 18, 32, 18 );

    foreach ( $tcarres->result() as $rwCarr ) {

        $pdf->AddPageEx( "P", "A4", 2, 0 );

            //$pdf->Ln(10);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell( 176, 8, toUTF("RECORD DE REVISIÓN DE PROYECTOS"), 0, 1, "C" );
        $pdf->Cell( 176, 8, toUTF( $rwCarr->Carrera ), 1, 1, "C" );
        $pdf->Ln(5);

        $docfilt = "Tipo='$tipo' AND IdCarrera='$rwCarr->IdCarrera' ORDER BY DatosPers";
        $tdocens = $this->dbPilar->getTable( "vxDatDocentes", $docfilt );

        $nro = 1;
        foreach ( $tdocens->result() as $rwDoc ) {

            $pdf->SetFont( 'Arial','', 9 );
            $pdf->Cell(  10, 7, toUTF( $nro++ ), 1, 0, "R" );
            $pdf->Cell( 136, 7, toUTF( $rwDoc->DatosPers ), 1, 0, "L" );


                //------------------------------------------------------------
                // Area de extraccion de datos de proyectos y borradores
                //------------------------------------------------------------
            $filtro = "( IdJurado1='$rwDoc->IdDocente' OR IdJurado2='$rwDoc->IdDocente' OR
            IdJurado3='$rwDoc->IdDocente' OR IdJurado4='$rwDoc->IdDocente' ) AND
            Estado>=4 ORDER BY Estado, FechModif";

            $conta = 1;
            $proys = $this->dbPilar->getSnapView( "tesTramites", $filtro );

            $pdf->SetFont( 'Arial','B', 9 );
            $pdf->Cell( 30, 7, toUTF( "Proyectos: ".$proys->num_rows() ), 1, 0, "L" );
                $pdf->Cell(  1, 7, "", 0, 1 ); // final de fila

                foreach ( $proys->result() as $row ) {

                    $estado = "";
                    $titulo = $this->dbPilar->inTitulo( $row->Id );
                    $contar = mlDiasTranscHoy( $row->FechModif );
                    if( $row->Estado >=6 && $row->Estado!=12 )
                        $contar = "";

                    if( $row->Estado <= 5 ) $estado = "Revisión";
                    if( $row->Estado == 5 ) $estado = "Dictamen";
                    if( $row->Estado >= 6 ) $estado = "Aprobado";
                    if( $row->Estado >= 11 ) $estado = "Borrador";
                    if( $row->Estado == 13 ) $estado = "Reunion";
                    if( $row->Estado == 14 ) $estado = "Sustentado";

                    $pdf->SetFont( 'Arial','', 9 );
                    $pdf->Cell(  10, 7, "", 0, 0 ); // salto
                    $pdf->Cell(  10, 7, toUTF( $conta++ ), 1, 0, "R" );

                    $pdf->SetFont( 'Arial','', 7 );
                    $pdf->Cell(  100, 7, toUTF( substr($titulo,0,60) . "..." ), 1, 0, "L" );

                    $pdf->SetFont( 'Arial','', 9 );
                    $pdf->Cell(  20, 7, toUTF( "$estado" ), 1, 0, "C" );
                    $pdf->Cell(  20, 7, toUTF( substr(mlFechaNorm($row->FechModif),0,10) ), 1, 0, "C" );
                    $pdf->Cell(  16, 7, toUTF( $contar ), 1, 0, "C" );

                    $pdf->Cell(   1, 7, "", 0, 1 );
                }

                $pdf->Ln(5);
            }
        }
        $pdf->Output();
    }
    public function proyectosPILAR(){
        $consulta=$this->dbPilar->getTable("tesTramites","Estado>2");
        echo "<table style='width:100%''>
        <tr>
        <th>N°</th>
        <th>Titulo</th> 
        <th>Tesista</th>
        <th>Fecha</th>
        </tr>";
        $flag=1;
        foreach($consulta->result() as $row){
            $Titulo=$this->dbPilar->inTitulo("$row->Id");
            $Tesista=$this->dbPilar->inTesistas("$row->Id");
            echo "  <tr>
            <td>$flag</td>
            <td>$Titulo</td>
            <td>$Tesista</td>
            <td>$row->FechRegProy</td> 
            </tr>";
            $flag++; 
        }
        echo "</table>";
    }

    public function proyectosporCarrera($carrera){
        $row = $this->dbRepo->getSnapRow( 'dicCarreras', "Id=$carrera" );
        if( !$row ){ echo "No Carrera"; return; }
        echo "<h5 class='text-center'> ESCUELA PROFESIONAL </h5>";
        echo "<h4 class='text-center'> <small>($row->Id)</small> $row->Nombre</h4>";

        $consulta=$this->dbPilar->getTable("tesTramites","IdCarrera='$carrera'");
        echo "<table style='width:100%''>
        <tr>
        <th>N°</th>
        <th>Titulo</th> 
        <th>Tesista</th>
        <th>Estado</th>
        <th>Lineas</th>
        <th>Fecha</th>
        </tr>";
        $flag=1; 
        $estado="-";
        foreach($consulta->result() as $row){
            $Titulo=$this->dbPilar->inTitulo("$row->Id");
            $Tesista=$this->dbPilar->inTesistas("$row->Id");
            $carrera=$this->dbRepo->inCarrera("$row->IdCarrera");
            if($row->Tipo<=0)$estado='DESARPOBADO'; 
            if($row->Tipo==1)$estado='PROYECTO'; 
            if($row->Tipo==2)$estado='BORRADOR'; 
            if($row->Tipo==3)$estado='SUSTENTADO'; 
            $linea=$this->dbRepo->inLineaInv($row->IdLinea);
            echo "  <tr>
            <td>$flag</td>
            <td>$Titulo</td>
            <td>$Tesista</td>
            <td>$estado</td>
            <td>$linea</td>
            <td>$row->FechModif</td> 
            </tr>";
            $flag++; 
        }
        echo "</table>";
    }
    public function SorteoPendiente(){
        $consulta=$this->dbPilar->getTable("tesTramites","Estado='3' ORDER BY IdCarrera ASC");
        echo "<table style='width:100%''>";
        //         <tr>
        //             <th>N°</th>
        //             <th>Carrera</th> 
        //             <th>Titulo</th>
        //             <th>Linea</th>
        //         </tr>";
        // $flag=1;
        // foreach($consulta->result() as $row){
        //     $Titulo=$this->dbPilar->inTitulo("$row->Id");
        //     $Tesista=$this->dbPilar->inTesistas("$row->Id");
        //     $Linea=$this->dbRepo->inLineaInv("$row->IdLinea");
        //     $Carrera=$this->dbRepo->inCarrera("$row->IdCarrera");
        //     echo "  <tr>
        //                 <td>$flag</td>
        //                 <td>$Carrera</td>
        //                 <td>$Titulo</td>
        //                 <td>$Linea</td>
        //                 <td>$row->FechRegProy</td> 
        //             </tr>";
        //     $flag++; 
        // }
        $carre=$this->dbRepo->getTable("dicCarreras");
        foreach ($carre->result() as $ki) {
            $consulta1=$this->dbPilar->getTable("tesTramites","Estado='3' AND IdCarrera = '$ki->Id'");
            echo "<tr>
            <td>$ki->Nombre</td>
            <td>".$consulta1->num_rows()."</td>";
        }
        echo "</table>";
        

    }
    public function cambios(){
        $table=$this->dbPilar->getTable("logLogins","Tipo='D'");
        $i=0;
        echo "<table>";
        foreach ($table->result() as $row) {
           $carr=$this->dbRepo->getOneField("tblDocentes","IdCarrera","Id=$row->IdUser");
           if($carr==5){
            echo "<tr><td>$i </td><td> $row->Accion</td><td>$row->Fecha</td></tr>";
            $i++;
        }
    }
    echo "---SALTO DE LINEA---<br>";
    $table=$this->dbPilar->getTable("logLoginsS1","Tipo='D'");
    $i=1;
    $j=0;$l=0;
    foreach ($table->result() as $row) {
       $carr=$this->dbRepo->getOneField("tblDocentes","IdCarrera","Id=$row->IdUser");
       if($carr==5){
        if($row->Accion=='Ingreso')$j=$j+1;
        if($row->Accion=='Clave incorrecta')$l=$l+1;
        echo "<tr><td>$i </td><td> $row->Accion</td><td>$row->Fecha</td></tr>";
        $i++;
    }
}
echo "</table>";

echo "<h1>$j Ok / $l NO </h1>";
}

public function CambiosCarrera(){
    $table=$this->dbPilar->getTable("tesTramites", "IdCarrera='5'");
    foreach ($table->result() as $rest) {
        $tabli=$this->dbPilar->getTable("logTramites","IdTramite='$rest->Id'");
        foreach($tabli->result() as $rep){
            echo "$rep->IdTramite $rep->Accion<br>";
        }
    }
}

public function histoTrams( $carr=0 )
{
    echo "<style>body{ font-family:Arial } td{ font-size:11px } </style>";

    $table1 = $this->dbRepo->getTable("dicCarreras","1 ORDER BY Nombre");
    foreach( $table1->result() as $carr ) {

        $table2 = $this->dbPilar->getTable("tesTramites","IdCarrera=$carr->Id AND Estado>='6' AND Estado<>'10'  ORDER BY Estado DESC, IdLinea");
        $carrer = $this->dbRepo->inCarrera( $carr->Id );

        $nro = 1;

        echo "<h4>Escuela Profesional de: $carrer </h4>";
        echo "<table cellpadding=4 cellspacing=0 width=900 border=1>";
        echo "<tr style='font-weight: bold; background: #D0D0D0'>";
        echo "<td wisth=10> Nro </td>";
        echo "<td width=60> Codigo </td>";
        echo "<td> (E) </td>";
        echo "<td> Título del Proyecto </td>";
        echo "<td> Línea de Investigación </td>";
        echo "</tr>";

        foreach( $table2->result() as $proy ) {

            $titulo = $this->dbPilar->inTitulo( $proy->Id );
            $autors = $this->dbPilar->inTesistas( $proy->Id );
            $linea  = $this->dbRepo->inLineaInv( $proy->IdLinea );
            $estado = ($proy->Estado==6)? "Proyecto de Tesis" : "Borrador de Tesis";

            echo "<tr>";
            echo "<td> $nro </td>";
            echo "<td> <b>$proy->Codigo</b> </td>";
            echo "<td> $estado </td>";
            echo "<td> <b><small>$autors</small></b><hr>$titulo </td>";
            echo "<td> $linea </td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td colspan=2></td>";
            echo "<td colspan=4>";

            echo "<table cellpadding=4 cellspacing=0 width=100% border=0>";

            $itera = 1;
            $table3 = $this->dbPilar->getTable("logTramites","IdTramite='$proy->Id' ORDER BY Id DESC");
            foreach( $table3->result() as $log ) {

                $fecha = mlFechaNorm( $log->Fecha );

                echo "<tr style='border-bottom: 1px solid black'>";
                echo "<td> $itera </td>";
                echo "<td width=25%> $log->Accion <br> $fecha </td>";
                echo "<td> " .str_replace("<br>","",$log->Detalle)." </td>";
                echo "</tr>";
                $itera++;
            }

            echo "</table>";
            echo "</td>";
            echo "</tr>";
            $nro++;
        }
        echo "</table>";
    }
}

public function ReporteContraloria1(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-8'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>NUMERO ANUAL DE ESTUDIANTES REGISTRADOS EN PILAR</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>N°</th>
    <th>CARRERA</th> 
    <th>AREA</th> 
    <th>2016</th> 
    <th>2017</th> 
    <th>2018</th> 
    <th>2019</th>
    <th>TOTAL</th> 
    </tr>";
    
         
    $carreras=$this->dbRepo->getSnapView('dicCarreras');
    $flag=1;
    foreach ($carreras->result() as $rin) {
     if ($rin->Id == 23) {
        $area=$this->dbRepo->getOneField("dicFacultades","IdArea","Id= $rin->IdFacultad");
        $tesistas7=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20170101' AND '20171231'")->num_rows();
        $tesistas6=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20160101' AND '20161231'")->num_rows();
        $tesistas8=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20180101' AND '20181231'")->num_rows();
        $tesistas9=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20190101' AND '20191231'")->num_rows();
        $total = $tesistas6+$tesistas7+$tesistas8+$tesistas9;
        echo"<tr>
        <td>$flag</td>
        <td>$rin->Nombre</B></td>
         <td>$area</B></td>
         <td>".$tesistas6."</td>
        <td>".$tesistas7."</td>
        <td>".$tesistas8."</td>
        <td>".$tesistas9."</td>
        <td>".$total."</td>
        </tr>";
        $flag++;

            # code...
         }
    }
    echo "</table>";
    echo "</div>";
}

public function ReporteContraloriaOp(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-8'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>NUMERO ANUAL DE PROYECTOS </h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>N°</th>
    <th>CARRERA</th> 
    <th>AREA</th> 
    <th>2016</th> 
    <th>2017</th> 
    <th>2018</th> 
    <th>2019</th>
    <th>TOTAL</th> 
    </tr>";
    
         
    $carreras=$this->dbRepo->getSnapView('dicCarreras');
    $flag=1;
    foreach ($carreras->result() as $rin) {
     // if ($rin->Id == 35) {
        $area=$this->dbRepo->getOneField("dicFacultades","IdArea","Id= $rin->IdFacultad");
        $tesistas7=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20170101' AND '20171231'")->num_rows();
        $tesistas6=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20160101' AND '20161231'")->num_rows();
        $tesistas8=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20180101' AND '20181231'")->num_rows();
        $tesistas9=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20190101' AND '20191231'")->num_rows();
        $total = $tesistas6+$tesistas7+$tesistas8+$tesistas9;
        echo"<tr>
        <td>$flag</td>
        <td>$rin->Nombre</B></td>
         <td>$area</B></td>
         <td>".$tesistas6."</td>
        <td>".$tesistas7."</td>
        <td>".$tesistas8."</td>
        <td>".$tesistas9."</td>
        <td>".$total."</td>
        </tr>";
        $flag++;

            # code...
         // }
    }
    echo "</table>";
    echo "</div>";
}



public function TesisxLineaxCarrera($id){
    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-8'> ";
    echo "<center><img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img></center>";
    echo "<h3 class='text-center'>NUMERO DE TESIS POR LINEA DE INVESTIGACIÓN</h3>";
    $carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$id");
    echo "<b><h3 class='text-center'>$carrera</h3></b>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>N°</th>
    <th>LINEA</th> 
    <th>ESTADO</th> 
    <th>2016</th> 
    <th>2017</th> 
    <th>2018</th> 
    <th>2019</th>
    <th>TOTAL</th> 
    </tr>";

    $lineascarrera=$this->dbRepo->getSnapView('tblLineas',"IdCarrera=$id ORDER BY Estado DESC, Nombre ASC");
    $flag=1;
    // <td>$area</B></td>
    // <th>AREA</th> 
    foreach ($lineascarrera->result() as $rin) {
     
        $estado=($rin->Estado==1?"Activo":"Desabilitado");
        // $tesistas7=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id AND FechRegProy BETWEEN '20170101' AND '20171231'")->num_rows();
        // $tesistas6=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id AND FechRegProy BETWEEN '20160101' AND '20161231'")->num_rows();
        // $tesistas8=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id AND FechRegProy BETWEEN '20180101' AND '20181231'")->num_rows();
        // $tesistas9=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id AND FechRegProy BETWEEN '20190101' AND '20191231'")->num_rows();
        // $total = $tesistas6+$tesistas7+$tesistas8+$tesistas9;


        $tesistas6=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id  AND Estado > 0 AND FechRegProy BETWEEN '20160101' AND '20161231'")->num_rows();
        $tesistas7=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id  AND Estado > 0 AND FechRegProy BETWEEN '20170101' AND '20171231'")->num_rows();
        $tesistas8=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id  AND Estado > 0 AND FechRegProy BETWEEN '20180101' AND '20181231'")->num_rows();
        $tesistas9=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id  AND Estado > 0 AND FechRegProy BETWEEN '20190101' AND '20191231'")->num_rows();
        $total = $tesistas6+$tesistas7+$tesistas8+$tesistas9;

        echo"<tr>
        <td>$flag</td>
        <td>$rin->Nombre</B></td>
        <td>$estado</B></td>
        <td>".$tesistas6."</td>
        <td>".$tesistas7."</td>
        <td>".$tesistas8."</td>
        <td>".$tesistas9."</td>
        <td>".$total."</td>
        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}

public function SustentxLineaxCarreraxDocente($id){
    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-8'> ";
    echo "<center><img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img></center>";
    echo "<h3 class='text-center'>NUMERO DE TESIS POR LINEA DE INVESTIGACIÓN</h3>";
    $carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$id");
    echo "<b><h3 class='text-center'>$carrera</h3></b>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>N°</th>
    <th>LINEA</th> 
    <th>ESTADO</th> 
    <th>2016</th> 
    <th>2017</th> 
    <th>2018</th> 
    <th>2019</th>
    <th>TOTAL</th> 
    </tr>";

    $lineascarrera=$this->dbRepo->getSnapView('tblLineas',"IdCarrera=$id ORDER BY Estado DESC, Nombre ASC");
    $flag=1;
    // <td>$area</B></td>
    // <th>AREA</th> 
    foreach ($lineascarrera->result() as $rin) {
     
        $estado=($rin->Estado==1?"Activo":"Desabilitado");
        $tesistas7=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id AND Tipo=3 AND FechModif BETWEEN '20170101' AND '20171231'")->num_rows();
        $tesistas6=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id AND Tipo=3 AND FechModif BETWEEN '20160101' AND '20161231'")->num_rows();
        $tesistas8=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id AND Tipo=3 AND FechModif BETWEEN '20180101' AND '20181231'")->num_rows();
        $tesistas9=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id AND Tipo=3 AND FechModif BETWEEN '20190101' AND '20191231'")->num_rows();
        $total = $tesistas6+$tesistas7+$tesistas8+$tesistas9;
        echo"<tr>
        <td>$flag</td>
        <td>$rin->Nombre</B></td>
        <td>$estado</B></td>
        <td>".$tesistas6."</td>
        <td>".$tesistas7."</td>
        <td>".$tesistas8."</td>
        <td>".$tesistas9."</td>
        <td>".$total."</td>
        </tr>";
        echo  "<tr><th>ORD</th>"
      . "<th>COD</th>"
      . "<th>TIP</th>"
      . "<th>NOMBRES</th>";
      $table = $this->dbPilar->getSnapView( "vxDocInLin", "IdCarrera=$id AND Activo >=3 " );
      $nro=1;
      foreach ( $table->result() as $row ){
         $conteo16=$this->conteoDocLin($row->IdDocente,$rin->Id);  
         $conteo17=$this->conteoDocLin($row->IdDocente,$rin->Id);  
         $conteo18=$this->conteoDocLin($row->IdDocente,$rin->Id);  
         $conteo19=$this->conteoDocLin($row->IdDocente,$rin->Id);  
         echo "<tr>";
         echo "<td> <b>$nro</b> </td>";
         echo "<td> $row->DatosPers  </td>";
         echo " $conteo16 / $conteo17 / $conteo18 / $conteo19";
         echo "</tr>"; 
         $nro++;
      }
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}

function conteoDocLin($idDoc,$idLin,$anio){
        $ini=$anio."0101";
        $fin=$anio."1231";
        $j1=$this->dbPilar->getTotalRows("tesTramites","IdJurado1=$idDoc AND IdLinea=$idLin AND FechModif BETWEEN '$ini' AND '$fin'");
        $j2=$this->dbPilar->getTotalRows("tesTramites","IdJurado2=$idDoc AND IdLinea=$idLin AND FechModif BETWEEN '$ini' AND '$fin'");
        $j3=$this->dbPilar->getTotalRows("tesTramites","IdJurado3=$idDoc AND IdLinea=$idLin AND FechModif BETWEEN '$ini' AND '$fin'");
        $j4=$this->dbPilar->getTotalRows("tesTramites","IdJurado4=$idDoc AND IdLinea=$idLin AND FechModif BETWEEN '$ini' AND '$fin'");
        $tot=$j1+$j2+$j3+$j4;
        return "<td>$j1</td><td>$j2</td><td>$j3</td><td>$j4</td><td> <b>$tot</b> </td>";
}

public function ReporteContraloria2(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-8'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>NUMERO ANUAL DE ESTUDIANTES REGISTRADOS EN PILAR</h3>";
    echo "<table style='width:100%' class='table table-striped '>";
    $carreras=$this->dbRepo->getSnapView('dicCarreras');
    $flag=1;
    foreach ($carreras->result() as $rin) {
        $tesistas7=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20170101' AND '20171231'");
            //$tesistas6=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20160101' AND '20161231'");
            //$tesistas8=$this->dbPilar->getSnapView("tblTesistas","IdCarrera=$rin->Id AND FechaReg BETWEEN '20180101' AND '20181231'");

        echo"<tr>
        <th>$flag</th>
        <th>$rin->Nombre</B></th>
        <th>ULTIMO SEMESTRE</th>
        <th>FECHA</B></th>
        </tr>";

        $f=1;
        foreach ($tesistas7->result() as $tete) {
          echo"<tr style='font-size:11px;'>
          <td>$f</td>
          <td>$tete->Apellidos, $tete->Nombres</B></td>
          <td> $tete->SemReg</td>
          <td> $tete->FechaReg</td>
          </tr>";
          $f++;
      }

      $flag++;

            # code...
  }
  echo "</table>";
  echo "</div>";
}
public function ProyectosconJuradosparaReportedeAdministracion(){
    $this->load->view("pilar/head");
        // echo "<div class='col-md-2'> </div>";
    $idc=array(3);
    for($i=0;$i<1;$i++){
        echo "<div class='col-md-12'> ";
        echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
        echo "<h3 class='text-center'>RELACIÓN CONFIDENCIAL <BR> <I>Reporte de Registros en PILAR </I><br> Escuela profesional de ".$this->dbRepo->inCarrera($idc[$i])."</h3>";
        echo "<table style='width:100%' class='table table-striped '>
        <tr>
        <th>NUM</th>
        <th >TIPO</th>
        <th width='12%' >CODIGO</th>
        <th>TESISTA</th> 
        <th>LINEA</th> 
        <th>TTÍTULO</th> 

        <th>Fecha Presentación</th>
        <th>Fecha Final</th>
        </tr>";
        $tesis=$this->dbPilar->getSnapView("tesTramites","Tipo > 1 AND Estado>0 AND IdCarrera='$idc[$i]'","ORDER by Estado ASC");
        $flag=1;
        foreach($tesis->result() as $row){
           $chicos=$this->dbPilar->inTesistas("$row->Id");
           if($row->Tipo == 1) $tipo="PROYECTO";
           if($row->Tipo == 2) $tipo="BORRADOR";
           if($row->Tipo == 3) $tipo="SUSTENTACION";
           if($row->Tipo == 0) $tipo="RECHAZADO";
           echo"<tr>
           <td>$flag</td>
           <td style='font-size:9px;'>$tipo</td>
           <td><B>$row->Codigo</B></td>
           <td><h5> $chicos </h5> </td>
           <td>".$this->dbRepo->inLineaInv("$row->IdLinea")."</td> 
           <td>".$this->dbPilar->inTitulo("$row->Id")."</td> 
           <td>$row->FechModif</td>
           <td>$row->FechRegProy</td>";

            // if($row->Tipo > 1) {
           echo "<td><h5 style='font-size:12px;'>(J1):".$this->dbRepo->inDocente($row->IdJurado1) ." / <b>". $this->dbRepo->inCarreDoc($row->IdJurado1)."</b> <br>".
           "(J2) :".$this->dbRepo->inDocente($row->IdJurado2) ." / <b>".$this->dbRepo->inCarreDoc($row->IdJurado2)."</b> <br>".
           "(J3) :".$this->dbRepo->inDocente($row->IdJurado3) ." / <b>".$this->dbRepo->inCarreDoc($row->IdJurado3)."</b> <br>".
           "(D) :".$this->dbRepo->inDocente($row->IdJurado4) ." / <b>".$this->dbRepo->inCarreDoc($row->IdJurado4)."</b> <br>"
           ."</h5></td>";
            // }       
           echo "</tr>";
           $flag++;
       }
       echo "</table>";
       echo "</div>";
   }
        // <th>JURADO</th>  echo "                    <td><h5 style='font-size:12px;'>(J1):".$this->dbRepo->inDocente($row->IdJurado1) ." / <b>". $this->dbRepo->inCarreDoc($row->IdJurado1)."</b> <br>".
        //                 "(J2) :".$this->dbRepo->inDocente($row->IdJurado2) ." / <b>".$this->dbRepo->inCarreDoc($row->IdJurado2)."</b> <br>".
        //                 "(J2) :".$this->dbRepo->inDocente($row->IdJurado3) ." / <b>".$this->dbRepo->inCarreDoc($row->IdJurado3)."</b> <br>".
        //                 "(J2) :".$this->dbRepo->inDocente($row->IdJurado4) ." / <b>".$this->dbRepo->inCarreDoc($row->IdJurado4)."</b> <br>"
        //             ."</h5></td>";
}




public function ReporteJcesarPY(){
    $pdf = new GenSexPdf();
    $pdf->SetMargins( 10, 30, 10 );
    $carrie=$this->dbRepo->getTable("dicCarreras");
    foreach($carrie->result() as $carre){
        $pdf->AddPageEx( "L", "A4", 2, 0 ); 
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(190,5,toUTF("PROYECTOS DE TESIS DE LA ESCUELA PROFESIONAL DE"),0,1,'C');
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(190,8,toUTF($carre->Nombre),0,1,'C');
        $pdf->Ln(6);
        $pdf->SetFont( "Arial", '', 10 );

        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->setFontSize(array(8,7,8,7,9,8));
        $pdf->SetAligns(array('C','J','C','C','C','C'));
        $pdf->SetWidths(array(10,100,30,20,30,30));
        $flag=1;
        $tes=$this->dbPilar->getSnapView("tesTramites","Tipo > 0 AND IdCarrera=$carre->Id ORDER BY IdCarrera ASC");
        foreach ($tes->result() as $row) {
            if($row->Tipo==1)$estati="PROYECTO DE TESIS";
            if($row->Tipo==2)$estati="BORRADOR DE TESIS";
            if($row->Tipo==3)$estati="TESIS DEFENDIDA";
            $pdf->Row(
                array(
                    $flag,
                    toUTF($this->dbPilar->inTitulo($row->Id)),
                    toUTF($this->dbRepo->inLineaInv($row->IdLinea)),
                    toUTF($this->dbPilar->inTesistas($row->Id)),
                    $estati,
                    $row->FechModif
                )
            );
            $flag++;
        }
    }
    $pdf->Output();
}



public function ReporteCarreraPy2018(){
    $pdf = new GenSexPdf();
    $pdf->SetMargins( 10, 30, 10 );
    $pdf->AddPageEx( "P", "A4", 2, 0 ); 
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(190,5,toUTF("NÚMERO DE TESIS POR CARRERAS 2018."),0,1,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(190,5,toUTF("PLATAFORMA DE INVESTIGACIÓN UNIVERSITARIA INTEGRADA A LA LABOR ACADEMICA CON RESPONSABILIDAD"),0,1,'C');
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(190,5,toUTF("PILAR"),0,1,'C');
    $car=$this->dbRepo->getSnapView("dicCarreras");
    $pdf->Ln(10);
    $pdf->setFontSize(array(12,12,12));
    $pdf->SetWidths(array(10,140,30));
    $pdf->Row(
        array(
            toUTF("N°"),
            toUTF("Nombre Sub - Linea"),
            toUTF("Tesis"),
        )
    );
    $flag=1;
    foreach($car->result() as $li){ 
        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->setFontSize(array(8,7,8));
        $pdf->SetAligns(array('C','J','C'));
        $pdf->SetWidths(array(10,140,30));
        $ale = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$li->Id AND Tipo= 3 AND Anio = 2018");
        $pdf->Row(
            array(
                $flag,
                toUTF($li->Nombre),
                toUTF($ale),
            )
        );
        $flag++;
    }
    $pdf->Output();
}
public function ReporteLienasPy2018(){
    $pdf = new GenSexPdf();
    $pdf->SetMargins( 10, 30, 10 );
    $pdf->AddPageEx( "P", "A4", 2, 0 ); 
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(190,5,toUTF("NÚMERO DE TESIS POR LÍNEAS DE INVESTIGACIÓN 2018."),0,1,'C');
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(190,5,toUTF("PLATAFORMA DE INVESTIGACIÓN UNIVERSITARIA INTEGRADA A LA LABOR ACADEMICA CON RESPONSABILIDAD"),0,1,'C');
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(190,5,toUTF("PILAR"),0,1,'C');
    $lin=$this->dbRepo->getTable("tblLineas","Estado=1");
    $pdf->Ln(10);
    $pdf->setFontSize(array(12,12,12));
    $pdf->SetWidths(array(10,140,30));
    $pdf->Row(
        array(
            toUTF("N°"),
            toUTF("Nombre Sub - Linea"),
            toUTF("Tesis"),
        )
    );
    $flag=1;
    foreach($lin->result() as $li){ 
        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->setFontSize(array(8,7,8));
        $pdf->SetAligns(array('C','J','C'));
        $pdf->SetWidths(array(10,140,30));
        $ale = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$li->Id AND Tipo= 3 AND Anio = 2018");
        $pdf->Row(
            array(
                $flag,
                toUTF($li->Nombre),
                toUTF($ale),
            )
        );
        $flag++;
    }
    $pdf->Output();
}

public function ReporteLienasPy2017(){
    $pdf = new GenSexPdf();
    $pdf->SetMargins( 10, 30, 10 );
    $pdf->AddPageEx( "P", "A4", 2, 0 ); 
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(190,5,toUTF("Sub - Lineas de Investigación y Tesis 2017 ."),0,1,'C');
    $lin=$this->dbRepo->getTable("tblLineas","Estado = 1");
    $pdf->Ln(10);
    $pdf->SetWidths(array(10,140,30));
    $pdf->Row(
        array(
            toUTF("N°"),
            toUTF("Nombre Sub - Linea"),
            toUTF("Tesis"),
        )
    );
    $flag=1;
    foreach($lin->result() as $li){
        $pdf->SetDrawColor( 170, 170, 170 );
        $pdf->setFontSize(array(8,7,8));
        $pdf->SetAligns(array('C','J','C'));
        $pdf->SetWidths(array(10,140,30));
        $ale = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$li->Id");
        $pdf->Row(
            array(
                $flag,
                toUTF($li->Nombre),
                toUTF($ale),
            )
        );
        $flag++;
    }
    $pdf->Output();
}
public function medicinaDatosMar2018(){
    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>Proyectos de Investigación Medicina 2018</I></h3>";
    echo "<h3 class='text-center'>2017</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>ID</th>
    <th>Tesista</th>
    <th>P</th>
    <th>J1</th>
    <th>J2</th>
    <th>J3</th>
    </tr>";
    $lin=$this->dbPilar->getTable("tesTramites","IdCarrera=32 AND Tipo =1 AND Estado=6");
    $flag=1;
    foreach($lin->result() as $row){
        $j1=$this->dbRepo->inDocenteRow($row->IdJurado1);
        $j2=$this->dbRepo->inDocenteRow($row->IdJurado2);
        $j3=$this->dbRepo->inDocenteRow($row->IdJurado3);
        $j4=$this->dbRepo->inDocenteRow($row->IdJurado4);
        if($j1->IdCategoria>10){
            $j1="<b class='text-danger' style='background:red;'>P/C</b>";
        }else{
            $j1="OK";
        }
        if($j2->IdCategoria>10){
            $j2="<b class='text-danger' style='background:red;'>P/C</b>";
        }else{
            $j2="OK";
        }
        if($j3->IdCategoria>10){
            $j3="<b class='text-danger' style='background:red;'>P/C</b>";
        }else{
            $j3="OK";
        }
        if($j4->IdCategoria>10){
            $j4="<b class='text-danger' style='background:red;'>P/C</b>";
        }else{
            $j4="OK";
        }

        echo"<tr>
        <td>$flag</td>
        <td>$row->Codigo</td>
        <td>$j1</td>
        <td>$j2</td>
        <td>$j3</td>
        <td>$j4</td>
        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}

public function CantidadLineasbyCarrerr(){

    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>LINEAS DE INVESTIGACIÓN</I></h3>";
    echo "<h3 class='text-center'>2017</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>ID</th>
    <th>SUB-LINEA</th>
    <th>CANTIDAD</th>
    </tr>";
    $lin=$this->dbRepo->getTable("tblLineas","Estado=1");
    $flag=1;
    foreach($lin->result() as $row){
        $ale = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$row->Id");
        echo"<tr>
        <td>$flag</td>
        <td>$row->Nombre</td>
        <td>$ale</td>
        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}


public function ReporteLienasPyweb(){

    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>LINEAS DE INVESTIGACIÓN</I></h3>";
    echo "<h3 class='text-center'>2017</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>ID</th>
    <th>SUB-LINEA</th>
    <th>CANTIDAD</th>
    </tr>";
    $lin=$this->dbRepo->getTable("tblLineas","Estado=1");
    $flag=1;
    foreach($lin->result() as $row){
        $ale = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$row->Id");
        echo"<tr>
        <td>$flag</td>
        <td>$row->Id</td>
        <td>$row->Nombre</td>
        <td>$ale</td>
        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}

public function ReportOCDEweb(){

    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>NÚMERO DE TRABAJOS DE INVESTIGACIÓN SEGUN ÁREA DEL CONOCMIENTO OCDE</I></h3>";
    echo "<h3 class='text-center'>ANUAL 2019-2018-2017-2016</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>ID</th>
    <th>SUB-LINEA</th>
    <th>2016</th>
    <th>2017</th>
    <th>2018</th>
    <th>2019</th>
    <th>TOTAL</th>
    </tr>";
    $lin=$this->dbRepo->getTable("ocdeAreas");
    $flag=1;
    foreach($lin->result() as $row){
        $line=$this->dbRepo->getTable("tblLineas","IdArea=$row->Id");
        $temp6=0;
        $temp7=0;
        $temp8=0;
        $temp9=0;
        foreach($line->result() as $iid) {
            $add6 = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$iid->Id AND Anio='2016'");
            $add7 = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$iid->Id AND Anio='2017'");
            $add8 = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$iid->Id AND Anio='2018'");
            $add9 = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$iid->Id AND Anio='2019'");
            $temp6 = $temp6 + $add6 ;
            $temp7 = $temp7 + $add7 ;
            $temp8 = $temp8 + $add8 ;
            $temp9 = $temp9 + $add9 ;
        }
        $totalin=$temp6+$temp7+$temp8+$temp9;
        echo"<tr>
        <td>$flag</td> 
        <td>$row->Nombre</td>
        <td>$temp6</td>
        <td>$temp7</td>
        <td>$temp8</td>
        <td>$temp9</td>
        <td>$totalin</td>
        </tr>";
        $flag++;

    }
    echo "</table>";
    echo "</div>";
}  

public function LineasUNAP(){

    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>LINEAS DE INVESTIGACIÓN</I></h3>";
    echo "<h3 class='text-center'>2019-2018-2017-2016</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>ID</th>
    <th>SUB-LINEA</th>
    <th>2016</th>
    <th>2017</th>
    <th>2018</th>
    <th>2019</th>
    <th>TOTAL</th>
    </tr>";
    $lin=$this->dbRepo->getTable("dic_LineasVRI");
    $flag=1;
    foreach($lin->result() as $row){
        $line=$this->dbRepo->getTable("tblLineas","id_lineaV=$row->Id");
        $temp6=0;
        $temp7=0;
        $temp8=0;
        $temp9=0;
        foreach($line->result() as $iid) {
            $add6 = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$iid->Id AND Anio='2016'");
            $add7 = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$iid->Id AND Anio='2017'");
            $add8 = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$iid->Id AND Anio='2018'");
            $add9 = $this->dbPilar->getTotalRows("tesTramites","IdLinea=$iid->Id AND Anio='2019'");
            $temp6 = $temp6 + $add6 ;
            $temp7 = $temp7 + $add7 ;
            $temp8 = $temp8 + $add8 ;
            $temp9 = $temp9 + $add9 ;
        }
        $totalin=$temp6+$temp7+$temp8+$temp9;
        echo"<tr>
        <td>$flag</td> 
        <td>$row->Nombre</td>
        <td>$temp6</td>
        <td>$temp7</td>
        <td>$temp8</td>
        <td>$temp9</td>
        <td>$totalin</td>
        </tr>";
        $flag++;

    }
    echo "</table>";
    echo "</div>";
}

public function ReportePILARCarrerasSandro(){

    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-8'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>INVESTIGACIÓN POR ESCUELA PROFESIONAL</I></h3>";
    echo "<h3 class='text-center'>2016 -2017 - 2018</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>ID</th>
    <th>CARRERA</th>
    <th>N° 2016 Py</th>
    <th>N° 2016 Bor</th>
    <th>N° 2016 Susten</th>
    <th>N° 2016 RECHA</th>
    <th>N° 2017 Py</th>
    <th>N° 2017 Bor</th>
    <th>N° 2017 Susten</th>
    <th>N° 2017 RECHA</th>
    <th>N° 2018 Py</th>
    <th>N° 2018 Bor</th>
    <th>N° 2018 Susten</th>
    <th>N° 2018 RECHA</th>
    
    </tr>";
    $lin=$this->dbRepo->getTable("dicCarreras");
    $flag=1;
    // <th>TOTAL</th>
    foreach($lin->result() as $row){
        $aler6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 0 AND Anio = 2016" );
        $aler7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 0 AND Anio = 2017" );
        $aler8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 0 AND Anio = 2018" );
        $alep6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 1 AND Anio = 2016" );
        $aleb6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Anio = 2016" );
        $ales6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 3 AND Anio = 2016" );
        $alep7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 1 AND Anio = 2017" );
        $aleb7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Anio = 2017" );
        $ales7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 3 AND Anio = 2017" );
        $alep8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 1 AND Anio = 2018" );
        $aleb8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Anio = 2018" );
        $ales8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 3 AND Anio = 2018" );

        $tot=$alep8 +$aleb8 +$ales8+$aler8;
        echo"<tr>
        <td style='height:10%;'>$flag</td>
        <td>$row->Nombre</td>              

        <td>$alep6</td>
        <td>$aleb6</td>
        <td>$ales6</td>
        <td>$aler6</td>
        <td>$alep7</td>
        <td>$aleb7</td>
        <td>$ales7</td>
        <td>$aler7</td>
        <td>$alep8</td>
        <td>$aleb8</td>
        <td>$ales8</td>
        <td>$aler8</td>
        
        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}



public function ReportePILARCarreras(){

    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-8'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo "<h3 class='text-center'>INVESTIGACIÓN POR ESCUELA PROFESIONAL</I></h3>";
        // echo "<h3 class='text-center'>2016</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>ID</th>
    <th>CARRERA</th>
    <th>N° 2016 Py</th>
    <th>N° 2016 Bor</th>
    <th>N° 2016 Susten</th>
    <th>N° 2016 RECHA</th>
    <th>N° 2017 Py</th>
    <th>N° 2017 Bor</th>
    <th>N° 2017 Susten</th>
    <th>N° 2017 RECHA</th>
    <th>N° 2018 Py</th>
    <th>N° 2018 Bor</th>
    <th>N° 2018 Susten</th>
    <th>N° 2018 RECHA</th>
    <th>TOTAL</th>
    </tr>";
    $lin=$this->dbRepo->getSnapView("dicCarreras","Id=9");
    $flag=1;
    foreach($lin->result() as $row){
        // Proyectos Presentados
        $alep6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo =1 AND Estado<5 AND Anio = 2016" );
        $alep7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo =1 AND Estado<5 AND Anio = 2017" );
        $alep8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo =1 AND Estado<5 AND Anio = 2018" );
        $alep9 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo =1 AND Estado<5 AND Anio = 2019" );
        // Proyectos Rechazados
        $aler6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 0 AND Anio = 2016" );
        $aler7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 0 AND Anio = 2017" );
        $aler8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 0 AND Anio = 2018" );
        $aler9 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 0 AND Anio = 2019" );
        // Proyectos Aprobados
        $alea6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo =1 AND Estado=6 AND Anio = 2016" );
        $alea7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo =1 AND Estado=6 AND Anio = 2017" );
        $alea8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo =1 AND Estado=6 AND Anio = 2018" );
        $alea9 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo =1 AND Estado=6 AND Anio = 2019" );
        // Borradores Pendientes de Carga
        $alebac6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Estado=10 AND Anio = 2016" );
        $alebac7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Estado=10 AND Anio = 2017" );
        $alebac8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Estado=10 AND Anio = 2018" );
        $alebac9 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Estado=10 AND Anio = 2019" );
        // Borradores Presentados - En Revisión
        $aleb6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Estado>10 AND Anio = 2016" );
        $aleb7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Estado>10 AND Anio = 2017" );
        $aleb8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Estado>10 AND Anio = 2018" );
        $aleb9 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 2 AND Estado>10 AND Anio = 2019" );
        // Trabajos Sustentados
        $ales6 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 3 AND Anio = 2016" );
        $ales7 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 3 AND Anio = 2017" );
        $ales8 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 3 AND Anio = 2018" );
        $ales9 = $this->dbPilar->getTotalRows("tesTramites","IdCarrera=$row->Id AND Tipo = 3 AND Anio = 2019" );

        $tot=$alep6 +$aleb6 +$ales6 +$alep7 +$aleb7 +$ales7 +$alep8 +$aleb8 +$ales8+$aler6+$aler7+$aler8;
        echo"<tr>
        <td style='height:10%;'>$flag</td>
        <td>$row->Nombre</td>              
        <td>$alep6</td>
        <td>$aleb6</td> 
        <td>$ales6</td>
        <td>$aler6</td>
        <td>$alep7</td>
        <td>$aleb7</td>
        <td>$ales7</td>
        <td>$aler7</td>
        <td>$alep8</td>
        <td>$aleb8</td>
        <td>$ales8</td>
        <td>$aler8</td>
        <td>$tot</td>
        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";
}

public function FEDUweb(){

    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("../absmain/imgs/fedu_baner.jpg")."'></img>";
    echo "<h3 class='text-center'>PROYECTO DE INVESTIGACIÓN DOCENTE</I></h3>";
    echo "<h3 class='text-center'>2017</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th>ID</th>
    <th>CARRERA</th>
    <th>CANTIDAD</th>
    </tr>";
    $lin=$this->dbRepo->getTable("dicCarreras");
    $flag=1;
    foreach($lin->result() as $row){
        $consu=$this->dbFedu->getTable("proyecto","Codigo = 2017");
        $count=0;
        foreach ($consu->result() as $res) {
            $doc= $this->dbRepo->inCarreDocId("$res->responsable");
            if($doc){   
                if($doc== $row->Id){
                    $count=$count+1;
                }
            }
        }
        echo"<tr>
        <td height='10px'>$flag</td>
        <td>$row->Nombre</td>
        <td>$count</td>
        </tr>";
        $flag++;
    }
    echo "</table>";
    echo "</div>";

}

public function FEDUReginas(){

    $this->load->view("pilar/head");
    echo "<div class='col-md-3'> </div>";
    echo "<div class='col-md-6'> ";
    echo "<img class='img-responsive' src='".base_url("../absmain/imgs/fedu_baner.jpg")."'></img>";
    echo "<h3 class='text-center'><small>PROYECTO DE INVESTIGACIÓN</small> <br> DOCENTE REGINA</I></h3>";
    echo "<h3 class='text-center'>2016 - 2017 - 2018</h3>";
    echo "<h5 class='text-right'>05 de Diciembre de 2017</h5>";

    $lin=$this->dbRepo->getTable("tblDocentes","Regina=1");
    foreach($lin->result() as $row){
        $flag=1;
        echo "<h4>Código : $row->Codigo</h4>";
        echo"<h5>Nombres y Apellidos : $row->Nombres $row->Apellidos  </h5>";
        $proyectoss=$this->dbFedu->getTable("integrantes","codDocente=$row->Codigo");
        if($proyectoss->num_rows()!= null){
            echo "<table style='width:100%' class='table table-striped ' style='font-size:12px;'>
            <tr>
            <th>ID</th>
            <th>Titulo</th>
            <th>PERIODO</th>
            </tr>";
            foreach ($proyectoss->result() as $tii) {
               $consu=$this->dbFedu->getSnapRow("proyecto","id=$tii->idProyect");
               $cod= ($consu->codigo=="2017")?"2017":"2016";

               echo" <tr><td>$flag</td>
               <td>$consu->titulo</td>
               <td>$cod</td>
               </tr>";
               $flag++;
           }
           echo "</table>";
       }else{
                // echo"<h5>*******El docente no tiene proyectos FEDU Registrados.</h5>";
       }
   }

   echo "</div>";
}
public function totSusten(){
    $carre=$this->dbRepo->getSnapView("dicCarreras");
    $consulta=$this->dbPilar->getTotalRows('vxSustens'," IdCarrera = '9' AND Fecha >'20171230'");
        // foreach ($variable as $key => $value) {
        //     # code...
        // }
    echo "Total = $consulta";
}

public function repoGenpilar() 
{
        // $this->onAreWeLogged();
    $pdf = new GenSexPdf();
    $pdf->SetMargins(18, 13, 18);
    $pdf->AddPage();
    $border = false;
    $pdf->SetDrawColor( 200, 200, 200 );
    $pdf->SetFont('Courier','B',20);

    $pdf->Cell(180,7,toUTF("ESTADO GENERAL : PILAR"),0,1,'C');
    $pdf->Cell(180,7,toUTF("2016 - 2017 -2018"),0,1,'C');
    $pdf->Ln(5);
    $temp=$this->dbRepo->getTable('tblDocentes');
    $ndocentes= $temp->num_rows();
    $temp=$this->dbRepo->getTable('tblDocentes','idCategoria<=12');          
    $ndocnombrados= $temp->num_rows();
    $temp=$this->dbRepo->getTable('tblDocentes','idCategoria>12');          
    $ndoccontra= $temp->num_rows();                      
    $regist=$this->dbRepo->getTotalRows('tblDocentes','Activo = 6');
    $registnom=$this->dbRepo->getTotalRows('tblDocentes','Activo = 6 and IdCategoria<=12');
    $registcon=$this->dbRepo->getTotalRows('tblDocentes','Activo = 6 and IdCategoria>12');
    $pora=($regist*100)/$ndocentes;
    $porb=($registnom*100)/$ndocnombrados;
    $porc=($registcon*100)/$ndoccontra;

    $estado1=$this->dbPilar->getTotalRows('tesTramites',' Tipo = 1  AND Estado=1');/*SUBIDO*/
    $estado2=$this->dbPilar->getTotalRows('tesTramites',' Tipo = 1  AND Estado=2');/*CON DIRECTOR*/
    $estado3=$this->dbPilar->getTotalRows('tesTramites',' Tipo = 1  AND Estado=3');/*PARA SORTEO*/
    $estado4=$this->dbPilar->getTotalRows('tesTramites',' Tipo = 1  AND Estado=4');/*EN REVISION DE JURADO*/
    $estado5=$this->dbPilar->getTotalRows('tesTramites',' Tipo = 1  AND Estado=6');/*EN REVISION DE JURADO*/

        $estadob1=$this->dbPilar->getTotalRows('tesTramites',' Tipo = 2 AND Estado=10');// Tiempo Cumplido
        $estadob11=$this->dbPilar->getTotalRows('tesTramites',' Tipo = 2 AND Estado=11');// En revisión virtual por Jurados
        $estadob2=$this->dbPilar->getTotalRows('tesTramites',' Tipo = 2 AND Estado=12');// En revisión virtual por Jurados
        $estadob3=$this->dbPilar->getTotalRows('tesTramites',' Tipo = 2 AND Estado=13');// En reunión de Dictaminación
        $estadob4=$this->dbPilar->getTotalRows('tesTramites','Tipo = 3');// Sustentados

        $pdf->SetFont('Courier','B',12);
        $pdf->Cell(180,7,toUTF("PROYECTOS DE TESIS EN PILAR:"),0,1,'L');
        $pdf->SetFont('Courier','',10);
        $pdf->Cell(180,7,toUTF("- $estado1  Proyectos para revisión de Formato."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $estado2  Proyectos en Revisión con el Asesor."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $estado3  Proyectos Listos para sorteo."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $estado4  Proyectos en Revisión por los Jurados."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $estado5  Proyectos Aprobados por los Jurados."),0,1,'L');

        //
        $pdf->SetFont('Courier','B',12);
        $pdf->Cell(180,7,toUTF("BORRADOR DE TESIS EN PILAR:"),0,1,'L');
        $pdf->SetFont('Courier','',10);
        $pdf->Cell(180,7,toUTF("- $estadob1  Proyectos Aprobados que Cumplieron el Tiempo de Ejecucción."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $estadob11  Borradores Cargados a PILAR."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $estadob2  En revision via Plataforma por Jurados."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $estadob3  Borrador listo para reunión de Dictamen."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $estadob4  Borradores de Tesis Sustentados."),0,1,'L');

        //
        
        $pdf->SetFont('Courier','B',12);
        $pdf->Cell(180,7,toUTF("DOCENTES ACTIVOS EN PILAR:"),0,1,'L');
        $pdf->SetFont('Courier','',10);
        $pdf->Cell(180,7,"- $regist  Docentes Validados (".round($pora, 0, PHP_ROUND_HALF_UP)." % )",0,1,'L');
        $pdf->Cell(180,7,toUTF("- $registnom Docentes Nombrados (".round($porb, 0, PHP_ROUND_HALF_UP)." % )"),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $registcon  Docentes Contratados (".round($porc, 0, PHP_ROUND_HALF_UP)." % )"),0,1,'L');
        $pdf->SetFont('Courier','B',12);
        $pdf->Cell(180,7,toUTF("BASE DE DATOS PILAR:"),0,1,'L');
        $pdf->SetFont('Courier','',10);
        $pdf->Cell(180,7,toUTF("- $ndocentes  Docentes en su base de Datos."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $ndocnombrados Docentes Nombrados."),0,1,'L');
        $pdf->Cell(180,7,toUTF("- $ndoccontra  Docentes Contratados."),0,1,'L');
        $pdf->Ln(5);
        $pdf->SetFont('Courier','B',9);
        $pdf->Cell(180,4,toUTF("------------------::PILAR::------------------"),0,1,'C');
        $pdf->Cell(180,4,toUTF("Plataforma de Investigación y Desarrollo"),0,1,'C');
        $pdf->Cell(180,4,toUTF("------------:: VRI ::------------"),0,1,'C');
        $pdf->Output();
    }


    function docEscuel(){
        $esc=$this->dbRepo->getSnapView('dicCarreras');
        foreach ($esc->result() as $row) {
            echo "<tr>";
            echo "<td>$row->Nombre</td>";
            $a=$this->dbRepo->getSnapView('tblDocentes',"Id=$row->Id")->num_rows();
            echo "<td>$a</td>";
            echo "</tr>";
        }
    }
    // salifda JSON inner server
    function demoRes()
    {
        header("HTTP/1.1 500 Server Error");
        header("Access-Control-Allow-Origin: / "); // http://vriunap.pe

        //header("Set-Cookie: f5avrbbbbbbbbbbbbbbbb=LFLNCBJENPICHHCINBJHIDNPCIMPONDJGEHMJMODFEJIADCCMLGLEGJJNFIJKIEMELEDPBIMGOCNIHDEKIKANFNAMLKJOPAGFGKOCICIMLANOCAGOMPCDJMAOEKIMOLF; HttpOnly; secure" );
        //header("Set-Cookie: TS01d23cbd=019edc9eb8e74201fa1fbdc0d5c2561dab2dfb7b6064d6ded886f1acb9ed8900f817a447ee29d501daa57638382583a9cb6f8189d4cf239b6787612dc511dcf16eee7ada33; Path=/; Domain=.www.sunat.gob.pe" );

        header("Content-Type: application/json; charset=UTF-8");
        header("Set-Cookie: TS01d23cbd=019edc9eb8069702ad0a75a199dfb5138351f03ba4ba52b2f2d39f0e119a045c5cb7e6f6181321ffc0b4920cf571492a76bd9be144; Path=/; Domain=.www.vriunap.pe" );

        echo  '{ "res", false : "Apellidos", "ANCO SALAS" }';
    }

    // REPORDE DE VALIDACIÓN DE LINEAS
    function lineasValidas(){
        $this->load->view("pilar/head");
        echo "<div class='col-md-3'> </div>";
        echo "<div class='col-md-6'> ";
        echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
        echo "<h3 class='text-center'><small>VALIDACIÓN DE LÍNEAS DE INVESTIGACIÓN</small> </I></h3>";
        echo "<h5 class='text-right'>".date("d/m/Y")."</h5>";

        echo "<table style='width:100%' class='table table-striped ' style='font-size:12px;'>";
        $esc=$this->dbRepo->getSnapView('dicCarreras');
        foreach ($esc->result() as $row) {
            $a=$this->dbRepo->getSnapView('tblLineas',"IdCarrera=$row->Id AND Estado=1");
            $flag=0;
            // $docVal=$this->dbPilar->getSnapView();
            echo "<thead style='background:black;color:white;'><tr >";
            echo "<td class='text-success '>$row->Nombre</td><td>DOC</td><td>VAL</td><td>RET</td>";
            echo "</tr></thead>";
            $f2=0;
            foreach ($a->result() as $der) {
                $flag++;
                $conDoc=$this->dbPilar->getSnapView('docLineas',"IdLinea=$der->Id")->num_rows();
                $conDoc2=$this->dbPilar->getSnapView('docLineas',"IdLinea=$der->Id AND Estado=2")->num_rows();
                $fuera=$conDoc-$conDoc2;
                $f2=$f2+$conDoc2;
                echo "<tr><td>$flag .- $der->Nombre</td><td>$conDoc</td><td>v:$conDoc2</td><td>$fuera</td></tr>";
            }
            if($f2==0){
                echo "<td class='text-danger'>OBSERVADO :$row->Nombre </td><td></td><td></td><td>$f2</td>";
            }else{
                echo "<td ></td><td class='text-success '>OK </td><td></td><td>$f2</td>";
            }
        }
        

        echo "</table>";
        echo "</div>";

    }


    // REPORDE PARA ELLY BRUJA
    function lineasValidass(){
        $this->load->view("pilar/head");
        echo "<div class='col-md-3'> </div>";
        echo "<div class='col-md-6'> ";
        echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
        echo "<h3 class='text-center'><small>VALIDACIÓN DE LÍNEAS DE INVESTIGACIÓN</small> </I></h3>";
        echo "<h5 class='text-right'>".date("d/m/Y")."</h5>";

        echo "<table style='width:100%' class='table table-striped ' style='font-size:12px;'>";
        $esc=$this->dbRepo->getSnapView('dicCarreras');
        foreach ($esc->result() as $row) {
            $a=$this->dbPilar->getSnapView('tesTramites',"IdCarrera=$row->Id");
            $flag=0;
            // $docVal=$this->dbPilar->getSnapView();
            echo "<thead style='background:black;color:white;'><tr >";
            echo "<td class='text-success '>$row->Nombre</td><td>DOC</td><td>VAL</td><td>RET</td>";
            echo "</tr></thead>";
            $f2=0;
            foreach ($a->result() as $der) {

                echo "<tr><td>$flag .- $der->Nombre</td><td>$conDoc</td><td>$conDoc2</td><td>$fuera</td></tr>";
            }
            if($f2==0){
                echo "<td class='text-danger'>OBSERVADO :$row->Nombre </td><td></td><td></td><td>$f2</td>";
            }else{
                echo "<td ></td><td class='text-success '>OK </td><td></td><td>$f2</td>";
            }
        }
        

        echo "</table>";
        echo "</div>";

    }

    public function ReporteLaspau2018_RelacionTish(){
        $this->load->view("pilar/head");
        // echo "<div class='col-md-2'> </div>";
        $flag=1;
        $total=0;
        $esc=$this->dbRepo->getSnapView('dicCarreras');
        echo "<div class='col-md-3'> </div>";
        echo "<div class='col-md-6'> ";
        echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
        echo "<h3 class='text-center'>PROGRAMA INSCRITOS</I></h3>";
        echo "<h5 class='text-right'>".date("d/m/Y H:m:s")."</h5>";
        echo "</div>";
        echo "<div class='col-md-3'> </div>";
        echo "<div class='col-md-3'> </div>";
        echo "<div class='col-md-12'> ";
        echo "<table style='width:100%' class='table table-striped '>";
        // foreach ($esc->result() as $row) {
        $postu=$this->dbPilar->getSnapView('_laspau',"Coho>0   ORDER BY Coho");
            // $area=$this->dbRepo->getOneField("dicFacultades","IdArea","Id=$row->IdFacultad");
        //     switch ($area) {
        //         case 1:
        //             $area="INGENIERÍAS";
        //             break;
        //         case 2:
        //             $area="BIOMÉDICAS";
        //             break;
        //         case 3:
        //             $area="ECONOC-EMPRE";
        //             break;
        //         case 4:
        //             $area="SOCIALES";
        //             break;
        //     } 
        //     echo "<tr>
        //         <th width='10'>N°</th>
        //         <th width='50'>$row->Nombre</th>
        //         <th width='30'>$area:</th>
        //         <th width='10'> ARCHIVO</th>  
                            // <th> $area:</th>
        //         </tr>";
        $count=1;

        foreach ($postu->result() as $post) {
            $dat=$this->dbRepo->getSnapRow("vwDocentes","Id=$post->IdDoc");
            if($dat){
                $fechita = ($post->Coho == 1 ? '1,2,3,5 de Octubre y 15 Noviembre del 2018' : "15,16,17,19 de Octubre y 16 de Noviembre del 2018");
                $msg="
                Señor(a) profesor(a) $dat->DatosPers, sirvase confirmar confirmar su participación en el curso LASPAU en el cohorte N° $post->Coho que se realizará en las fechas : $fechita , ingresando a su cuenta de la <b> Plataforma PILAR <a href='http://vriunap.pe/pilar' title='CONFIRMA'>(Click Aqui para Confirmar)</a></b>, ingresando con el usuario y contraseña que utiliza para revisar proyectos de tesis, la <b>FECHA LÍMITE</b> de confirmación de participación <b>Jueves 27 de Septiembre 2018 23:59:00 Hrs.</b></p>

                ";
                    // $this->genmailer->sendMail("$dat->Correo", "LASPAU : CONFIRMACIÓN DE PARTICIPACIÓN", $msg);
                $enlace=($dat->NroCelular  ?"<a href='http://vriunap.pe/pilar/reports/notiCelu2/$dat->NroCelular'>$dat->NroCelular</a>":"------");
                echo"<tr> 
                <td> $count</td>

                <th> $dat->Carrera</th>
                <th> $dat->DNI</th>
                <th> $dat->DatosPers</th>                           
                <td> $post->Cod</td>
                </tr>";
                $total=$count+$total;
                $count++;

            }

        }

             // echo"<tr> 
             //                <td> $count</td>

             //                <th> $dat->Carrera</th>
             //                <th> $dat->DNI</th>
             //                <th> $dat->DatosPers</th>
             //                <th> $dat->Correo</th>


             //                <td> $post->Cod</td>
             //                <td> $post->Id</td>
             //                <td> $post->Confirm</td>
             //                <td> $enlace</td>
             //                </tr>";
            // $this->genmailer->sendMail("torresfrd@gmail.com", "LASPAU : ENVIO OK -3", $msg);
// <td> $dat->Categoria</td>
// <td> $post->Coho</td>
            // <th> $dat->Facultad</th>
        // }
        echo "</table></div>";
        // echo "<br>";
    }

    public function CredencialesLaspau(){
        $pdf = new GenSexPdf();
        $pdf->SetMargins(0,0,0);
        $pdf->AddPageEx('L','','3','0');
        $pdf->SetFont('Courier','B',20);

        $pdf->Cell(180,7,toUTF("JULIO"),0,1,'C');
        $pdf->Cell(180,7,toUTF("TISNADO PUMA"),0,1,'C');
        $pdf->Cell(180,7,toUTF("CODIGO"),0,1,'C');

        $pdf->Ln(5);
        $pdf->SetFont('Courier','B',9);
        
        $pdf->Output();

    }


    public function asistenciaValida($idi){
     $pdf = new GenSexPdf();

     $esc=$this->dbRepo->getSnapView('dicCarreras');
     $postu=$this->dbPilar->getSnapView('_laspau',"Coho=$idi");
     $pdf->AddPageEx( "P", "A4", 0, 0 );
     $pdf->Image( "vriadds/pilar/imag/pilar-head.jpg", 10, 7, 190, 18 );
     $pdf->SetDrawColor( 170, 170, 170 );
     $pdf->SetFont('Times','B',14);
     $pdf->ln(28);
     $pdf->Cell( 190, 7, toUTF("RELACIÓN PROGRAMA LASPAU 2018"),0,1,"C" );
     $pdf->SetFont('Times','B',18);
            // $pdf->Cell( 190, 12, toUTF("$row->Nombre"),0,1,"C" );
     $pdf->SetFont('Times','B',14);
     $pdf->Cell( 90, 12, toUTF("C:$idi"),0,0,"L" );
     $pdf->Cell( 100, 12, toUTF("DGI"),0,1,"R" );   

     $pdf->SetFillColor(240,240,235);
     $pdf->ln(5);
     $pdf->SetFont('Times','B',12);
     $pdf->Cell( 10, 7, toUTF("N°"),1,0,"C",1);
     $pdf->Cell( 20, 7, toUTF("Código"),1,0,"C",1);
     $pdf->Cell( 85, 7, toUTF("Apellidos y Nombres"),1,0,"C",1);
     $pdf->Cell( 15, 7, toUTF("Cod.Ins."),1,0,"C",1);
     $pdf->Cell( 55, 7, toUTF("Correo"),1,1,"C",1);
     $flag=1;
     foreach($esc->result() as $row){

        $pdf->SetFont('Times','',8);
        foreach ($postu->result() as $red) {
            $doc=$this->dbRepo->getSnapRow("vwDocentes","Id=$red->IdDoc");
            if($doc->IdCarrera==$row->Id){
                $withBg = ($flag%2)? false : true;
                $pdf->Cell( 10, 7, toUTF("$flag"),1,0,"C",$withBg );
                $pdf->Cell( 20, 7, toUTF("$doc->Codigo"),1,0,"C",$withBg );
                $pdf->Cell( 85, 7, toUTF("$doc->DatosPers"),1,0,"L",$withBg );
                $pdf->Cell( 15, 7, toUTF("$red->Cod"),1,0,"C",$withBg );
                $pdf->Cell( 55, 7, toUTF("$doc->Correo"),1,1,"L",$withBg );
                $flag++;
            }
        }

    }
    $pdf->SetFont('Arial','B',14);
    $pdf->Output();
}


public function asistenciaEscuelas(){
 $pdf = new GenSexPdf();

 $esc=$this->dbRepo->getSnapView('dicCarreras');
 $postu=$this->dbPilar->getSnapView('_laspau',"Coho>0");
 foreach($esc->result() as $row){
    $pdf->AddPageEx( "P", "A4", 0, 0 );
    $pdf->Image( "vriadds/pilar/imag/pilar-head.jpg", 10, 7, 190, 18 );
    $pdf->SetDrawColor( 170, 170, 170 );
    $pdf->SetFont('Times','B',14);
    $pdf->ln(28);
    $pdf->Cell( 190, 7, toUTF(" PROGRAMA LASPAU 2018"),0,1,"C" );
    $pdf->SetFont('Times','B',14);
            // $pdf->Cell( 190, 7, toUTF("CONSTANCIAS DE PARTICIPACIÓN"),0,1,"C" );
    $pdf->SetFont('Times','B',18);
    $pdf->Cell( 190, 12, toUTF("$row->Nombre"),0,1,"C" );
    $pdf->SetFont('Times','B',14);
    $pdf->Cell( 90, 12, toUTF(" "),0,0,"L" );
    $pdf->Cell( 100, 12, toUTF("31 de Diciembre de 2018"),0,1,"R" );   

    $pdf->SetFillColor(240,240,235);
    $pdf->ln(5);
    $pdf->SetFont('Times','B',12);
    $pdf->Cell( 10, 7, toUTF("N°"),1,0,"C",1);
    $pdf->Cell( 20, 7, toUTF("Código"),1,0,"C",1);
    $pdf->Cell( 85, 7, toUTF("Apellidos y Nombres"),1,0,"C",1);
    $pdf->Cell( 25, 7, toUTF("Cod.Ins."),1,0,"C",1);
    $pdf->Cell( 15, 7, toUTF("Tipo"),1,0,"C",1);
    $pdf->Cell( 35, 7, toUTF("Firma"),1,1,"C",1);

    $flag=1;
    $pdf->SetFont('Times','',11);
    foreach ($postu->result() as $red) {
        $doc=$this->dbRepo->getSnapRow("vwDocentes","Id=$red->IdDoc");
        if($doc->IdCarrera==$row->Id){
            $withBg = ($flag%2)? false : true;
            $pdf->Cell( 10, 12, toUTF("$flag"),1,0,"C",$withBg );
            $pdf->Cell( 20, 12, toUTF("$doc->Codigo"),1,0,"C",$withBg );
            $pdf->Cell( 85, 12, toUTF("$doc->DatosPers"),1,0,"L",$withBg );
            $pdf->Cell( 25, 12, toUTF("$red->Cod"),1,0,"C",$withBg );
            $pdf->Cell( 15, 12, toUTF("$doc->Tipo"),1,0,"C",$withBg );
            $pdf->Cell( 35, 12, toUTF(""),1,1,"C",$withBg );
            $flag++;
        }
    }

} 
$pdf->SetFont('Arial','B',14);
$pdf->Output();
}

public function asistenciaLaspauTodo(){
 $pdf = new GenSexPdf();

 $esc=$this->dbRepo->getSnapView('tblDocentes','Id > 0 ORDER BY Apellidos ASC');

 $pdf->AddPageEx( "P", "A4", 0, 0 );
 $flag=1;
 $pdf->Image( "vriadds/pilar/imag/pilar-head.jpg", 10, 7, 190, 18 );
 $pdf->SetDrawColor( 170, 170, 170 );    
 $pdf->SetFont('Times','B',14);
 $pdf->ln(28);
 $pdf->SetFont('Times','B',25);

 $pdf->Cell( 190, 7, toUTF("Asistencia "),0,1,"C" );
 $pdf->SetFont('Times','B',18);
 $pdf->Cell( 190, 7, toUTF("PROGRAMA LASPAU 2018"),0,1,"C" );
 $pdf->SetFont('Times','B',12);
        // $pdf->Cell( 90, 12, toUTF("Turno: TARDE"),0,0,"L" );
 $pdf->Cell( 190, 8, toUTF("15 de Octubre de 2018"),0,1,"R" );   

 $pdf->SetFillColor(240,240,235);
 $pdf->ln(5);
 $pdf->SetFont('Times','B',12);
 $pdf->Cell( 10, 7, toUTF("N°"),1,0,"C",1);
 $pdf->Cell( 20, 7, toUTF("Código"),1,0,"C",1);
 $pdf->Cell( 105, 7, toUTF("Apellidos y Nombres"),1,0,"C",1);
        // $pdf->Cell( 25, 7, toUTF("Cod.Ins."),1,0,"C",1);
        // $pdf->Cell( 15, 7, toUTF("Tipo"),1,0,"C",1);
 $pdf->Cell( 55, 7, toUTF("FIRMA"),1,1,"C",1);
 foreach($esc->result() as $row){
    $pdf->SetFont('Times','',11);
            // foreach ($postu->result() as $red) {
    $postu=$this->dbPilar->getSnapRow('_laspau',"Coho=2 AND IdDoc = $row->Id");
    if($postu){
        $doc=$this->dbRepo->getSnapRow("vwDocentes","Id=$row->Id");
        $withBg = ($flag%2)? false : true;
        $pdf->Cell( 10, 12, toUTF("$flag"),1,0,"C",$withBg );
        $pdf->Cell( 20, 12, toUTF("$doc->Codigo"),1,0,"C",$withBg );
        $pdf->Cell( 105, 12, toUTF("$doc->DatosPers"),1,0,"L",$withBg );

                    // $pdf->Cell( 10, 12, toUTF(""),1,0,"C",$withBg );
                    // $pdf->Cell( 20, 12, toUTF(""),1,0,"C",$withBg );
                    // $pdf->Cell( 105, 12, toUTF(""),1,0,"L",$withBg );
                    // $pdf->Cell( 25, 12, toUTF("$red->Cod"),1,0,"C",$withBg );
                    // $pdf->Cell( 15, 12, toUTF("$doc->Tipo"),1,0,"C",$withBg );
        $pdf->Cell( 55, 12, toUTF(""),1,1,"C",$withBg );
        $flag++;
    }
            // }

}
$pdf->SetFont('Arial','B',14);
$pdf->Output();
}






public function ReporteLaspauALL(){
 $pdf = new GenSexPdf();

 $esc=$this->dbRepo->getSnapView('dicCarreras');
 $postu=$this->dbPilar->getSnapView('_laspau',"Coho=2");


 $pdf->AddPageEx( "H", "A4", 0, 0 );

 $pdf->Image( "vriadds/pilar/imag/pilar-head.jpg", 10, 7, 190, 18 );
 $pdf->ln(20);


           /* $pdf->Cell( 190, 7, toUTF("RELACIÓN PROGRAMA LASPAU 2018 - SALIDA"),0,1,"C" );
            $pdf->SetFont('Times','B',18);
            $pdf->Cell( 190, 12, toUTF("$row->Nombre"),0,1,"C" );
            $pdf->SetFont('Times','B',14);
            $pdf->Cell( 90, 12, toUTF("Turno: TARDE"),0,0,"L" );
            $pdf->Cell( 100, 12, toUTF("5 de Octubre de 2018"),0,1,"R" );   
             */ 

            $pdf->ln(5);
            $pdf->SetFont('Arial','B',11);
            $pdf->Cell( 10, 5, toUTF("N°"),1,0,"C" );
            $pdf->Cell( 20, 5, toUTF("Código"),1,0,"C" );
            $pdf->Cell( 95, 5, toUTF("Apellidos y Nombres"),1,0,"C" );
            $pdf->Cell( 18, 5, toUTF("COD."),1,0,"C" );
            $pdf->Cell( 85, 5, toUTF("Escuela"),1,0,"C" );
            $pdf->Cell( 15, 5, toUTF("Tipo"),1,0,"C" );
            $pdf->Cell( 20, 5, toUTF("Estado"),1,1,"C");

            $flag=1;

            foreach($esc->result() as $row){

                $pdf->SetDrawColor( 170, 170, 170 );
                $pdf->SetFont('Arial','B',14);
                $pdf->SetFont('Arial','',10);
                foreach ($postu->result() as $red) {
                    $doc=$this->dbRepo->getSnapRow("vwDocentes","Id=$red->IdDoc");
                    if($doc->IdCarrera==$row->Id){
                        $pdf->Cell( 10, 12, toUTF("$flag"),1,0,"C" );
                        $pdf->Cell( 20, 12, toUTF("$doc->Codigo"),1,0,"C" );
                        $pdf->Cell( 95, 12, toUTF("$doc->DatosPers"),1,0,"L" );
                        $pdf->Cell( 18, 12, toUTF("$red->Cod"),1,0,"C" );
                        $pdf->Cell( 85, 12, toUTF("$doc->Carrera"),1,0,"L" );
                        $pdf->Cell( 15, 12, toUTF("$doc->Tipo"),1,0,"C" );
                        $pdf->Cell(20, 12 , toUTF(""),1,1,"C");
                        $flag++;
                    }
                }

            }
            $pdf->SetFont('Arial','B',14);
            $pdf->Output();
        }




        public function ReporteLaspau2018(){
            $this->load->view("pilar/head");
        // echo "<div class='col-md-2'> </div>";
            $flag=1;
            $total=0;
            $esc=$this->dbRepo->getSnapView('dicCarreras');
            echo "<div class='col-md-3'> </div>";
            echo "<div class='col-md-6'> ";
            echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
            echo "<h3 class='text-center'>PROGRAMA LASPAU</I></h3>";
            echo "<h5 class='text-right'>".date("d/m/Y H:m:s")."</h5>";
            echo "</div>";
            echo "<div class='col-md-3'> </div>";
            echo "<div class='col-md-3'> </div>";
            echo "<div class='col-md-12'> ";
            echo "<table style='width:100%' class='table table-striped '>";
            echo "<tr>
            <th>N°</th>
            <th >AREA:</th>
            <th >Carrera :</th>
            <th>N° Postulantes</th>  
            </tr>";
            foreach ($esc->result() as $row) {
                $postu=$this->dbPilar->getSnapView('_laspau');
                $count=0;
                foreach ($postu->result() as $post) {
                    $carrera=$this->dbRepo->getOneField("tblDocentes","IdCarrera","Id=$post->IdDoc");
                // echo $carrera;
                    if($carrera==$row->Id)$count++;
                }
                $area=$this->dbRepo->getOneField("dicFacultades","IdArea","Id=$row->IdFacultad");
                switch ($area) {
                    case 1:
                    $area="INGENIERÍAS";
                    break;
                    case 2:
                    $area="BIOMÉDICAS";
                    break;
                    case 3:
                    $area="ECONÓMICO EMPRESARIALES";
                    break;
                    case 4:
                    $area="SOCIALES";
                    break;
                    default:
                    # code...
                    break;
                }
                $style="class='text-success'";
                if ($count==0) {
                    $style="class='text-danger' ";
                }
                echo"<tr>
                <td>$flag</td>
                <td>$area</td>
                <td>$row->Nombre</td>
                <td $style font style='font-size:20px'>$count</td>
                </tr>";
                $total=$count+$total;
                $flag++;
            }
            echo "<tr><h3 class='text-right'><small>TOTAL DE POSTULANTES REGISTRADOS</small> :<b> $total<b></h3></tr></table></div>";
        // echo "<br>";
        }

        public function RepoteElly(){
            $this->load->view("pilar/head");
        // echo "<div class='col-md-2'> </div>";
            $esc=$this->dbRepo->getSnapView('dicCarreras');
            foreach ($esc->result() as $ellybruja) {
                echo "<div class='col-md-12'> ";
        // echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
        // echo "<h3 class='text-center'>RELACIÓN CONFIDENCIAL <BR> <I>Reporte de Proyectos y Jurados</I><br> $ellybruja->Nombre </h3>";
                echo "<table style='width:100%' class='table table-striped '>";
        // echo "       <tr>
        //         <th>NUM</th>
        //         <th >TIPO</th>
        //         <th>TESISTA</th>  
        //         <th>TÍTULO</th> 
        //         <th>FECHA</th> 
        //         </tr>";
                $tesis=$this->dbPilar->getSnapView("tesTramites","Estado>0 AND IdCarrera='$ellybruja->Id' AND FechModif >'20160101' AND FechModif < '20161231' ","ORDER by Estado ASC , Tipo ASC");
                $flag=1;
                foreach($tesis->result() as $row){
                   $chicos=$this->dbPilar->inTesistas("$row->Id");
                   if($row->Estado < 6) $tipo="PROYECTO";
                   if($row->Estado == 6) $tipo="PROYECTO APROBADO";
                   if($row->Tipo == 2) $tipo="BORRADOR";
                   if($row->Tipo == 3) $tipo="SUSTENTACION";
                   if($row->Tipo == 0) $tipo="RECHAZADO";
                   echo "";
                   echo"<tr>
                   <td>$flag</td>
                   <td style='font-size:9px;'>$tipo</td>
                   <td>$ellybruja->Nombre</td>
                   <td><h5> $chicos </h5> </td>
                   <td>".$this->dbPilar->inTitulo("$row->Id")."</td> 
                   <td>$row->FechModif</td>
                   </tr>";
                   echo "";
                   $flag++;
               }
               echo "</table>";
               echo "</div>";
           }
                 // echo "    
                 //<td><B>$row->Codigo</B></td>
                 // <td>".$this->dbRepo->inLineaInv("$row->IdLinea")."</td> 
                // <td>".$this->dbPilar->inTitulo("$row->Id")."</td> 
                //    <td><h5 style='font-size:12px;'>(J1):".$this->dbRepo->inDocente($row->IdJurado1) ." / <b>". $this->dbRepo->inCarreDoc($row->IdJurado1)."</b> <br>".
                 //        "(J2) :".$this->dbRepo->inDocente($row->IdJurado2) ." / <b>".$this->dbRepo->inCarreDoc($row->IdJurado2)."</b> <br>".
                 //        "(J2) :".$this->dbRepo->inDocente($row->IdJurado3) ." / <b>".$this->dbRepo->inCarreDoc($row->IdJurado3)."</b> <br>".
                 //        "(J2) :".$this->dbRepo->inDocente($row->IdJurado4) ." / <b>".$this->dbRepo->inCarreDoc($row->IdJurado4)."</b> <br>"
                 //    ."</h5></td>";
       }

       public function personalFedu(){
        $pdf = new GenSexPdf();
        $flag=1;
        $tesis=$this->dbPilar->getSnapView("3mtPostul","Id>0","ORDER by Codigo LIMIT 10");
        foreach($tesis->result() as $row){
            $pdf->AddPageEx( "P", "A4", 0, 0 );
            $pdf->SetDrawColor( 170, 170, 170 );
            $pdf->Image( "vriadds/3mtunap/3mt_baner.jpg", 10, 10, 190, 20 );
            $pdf->ln(28);
            $pdf->SetFont('Times','',30);
            $pdf->Cell( 190, 0, toUTF("Código: $row->Codigo"),0,1,"R" );
            $pdf->SetFont('Times','',12);
            $pdf->ln(15);
            $pdf->SetFont('Times','B',14);
            $pdf->Multicell( 190, 5, toUTF($row->Titulo),0,"C",0 );
            $pdf->ln(10);
            $pdf->SetFont('Times','',11);
            $pdf->Cell( 190, 5, toUTF($this->dbPilar->getOneField("tblTesistas","Apellidos","Id=$row->IdTesista").", ".$this->dbPilar->getOneField("tblTesistas","Nombres","Id=$row->IdTesista")),0,1,"R" );
            $pdf->SetFont('Times','',10);
            $pdf->Cell( 190, 5, toUTF("ESCUELA PROFESIONAL DE ".$this->dbRepo->inCarrera($row->IdCarrera)),0,1,"R" );
            $pdf->SetFont('Times','',11);
            $pdf->Cell( 190, 5, toUTF($this->dbPilar->getOneField("tblTesistas","Correo","Id=$row->IdTesista")),0,1,"R");
            $pdf->ln(10);
            $pdf->SetFont('Times','',12);
            $pdf->Multicell(190,5,toUTF($row->Resumen),0,"J",0 );
            $flag++;
        }
        $pdf->Output();
    }
    public function notiCelu($idDoc)
    {
        $this->load->library('apismss');
        $cel = $this->dbRepo->inCelu($idDoc);

        $result = $this->apismss->sendFeduMSJ($cel);

        print($result);
    }
    public function notiCelu2($cel)
    {
        $this->load->library('apismss');
        $result = $this->apismss->sendMsj($cel,3);

        print_r($result);
    }

    public function deletemsj($id){
        $this->load->library('apismss');
        $this->apismss->delete($id);
    }
    public function ReportTesisAnalisis($escuela){
        $this->load->view("pilar/head");
        echo "<div class='col-md-3'> </div>";
        echo "<div class='col-md-6'> ";
        echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
        $esc=$this->dbRepo->inCarrera($escuela);
        echo "<h3 class='text-center'> TESIS ESCUELA PROFESIONAL $esc</h3>";
        echo "<h5 class='text-right'>".date("d/m/Y")."</h5>";

        echo "<table style='width:100%' class='table table-striped ' style='font-size:12px;'>";
        $tesis=$this->dbRepo->getSnapView('dicCarreras');
        foreach ($esc->result() as $row) {
            $a=$this->dbRepo->getSnapView('tblLineas',"IdCarrera=$escuela AND Estado=1");
            $flag=0;
            // $docVal=$this->dbPilar->getSnapView();
            echo "<thead style='background:black;color:white;'><tr >";
            echo "<td class='text-success '>$row->Nombre</td><td>DOC</td><td>VAL</td><td>RET</td>";
            echo "</tr></thead>";
            $f2=0;
            foreach ($a->result() as $der) {
                $flag++;
                $conDoc=$this->dbPilar->getSnapView('docLineas',"IdLinea=$der->Id")->num_rows();
                $conDoc2=$this->dbPilar->getSnapView('docLineas',"IdLinea=$der->Id AND Estado=2")->num_rows();
                $fuera=$conDoc-$conDoc2;
                $f2=$f2+$conDoc2;
                echo "<tr><td>$flag .- $der->Nombre</td><td>$conDoc</td><td>v:$conDoc2</td><td>$fuera</td></tr>";
            }
            if($f2==0){
                echo "<td class='text-danger'>OBSERVADO :$row->Nombre </td><td></td><td></td><td>$f2</td>";
            }else{
                echo "<td ></td><td class='text-success '>OK </td><td></td><td>$f2</td>";
            }
        }
        

        echo "</table>";
        echo "</div>";

    }


    public function SustentxLineaxCarrera($id){
        $this->load->view("pilar/head");
        echo "<div class='col-md-2'> </div>";
        echo "<div class='col-md-8'> ";
        echo "<center><img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img></center>";
        echo "<h3 class='text-center'>NUMERO DE SUSTENTACIONES POR LINEA DE INVESTIGACIÓN</h3>";
        $carrera=$this->dbRepo->getOneField("dicCarreras","Nombre","Id=$id");
        echo "<b><h3 class='text-center'>$carrera</h3></b>";
        echo "<table style='width:100%' class='table table-striped '>
        <tr>
            <th>N°</th>
            <th>LINEA</th> 
            <th>ESTADO</th> 
            <th>TOTAL</th> 
        </tr>";

        $lineascarrera=$this->dbRepo->getSnapView('tblLineas',"IdCarrera=$id ORDER BY Estado DESC, Nombre ASC");
        $flag=1;
        // <td>$area</B></td>
        // <th>AREA</th> 
        $total=0;
        foreach ($lineascarrera->result() as $rin) {
         
            $estado=($rin->Estado==1?"Activo":"Desabilitado");
            $tesistot=$this->dbPilar->getSnapView("tesTramites","IdLinea=$rin->Id");
            $countt=0;
            foreach ($tesistot->result() as $test) {
                $res16=$this->dbPilar->getSnapView("tesSustens","IdTramite=$test->Id AND Fecha BETWEEN '20150101' AND '20191231' ")->num_rows();
                if ($res16==1) {
                    $countt++;
                }
            }
            echo"<tr>
            <td>$flag</td>
            <td>$rin->Nombre</B></td>
            <td>$estado</B></td>
            <td>".$countt ."</td>
            </tr>";
            $total=$total+$countt;
            $flag++;
        }
        echo "<tr class='table-bordered bg-warning'><td>TOTAL</td><td><td></td><td>$total</td></tr>"; 
        echo "</table>";
        echo "</div>";
    }
    // Recibe estado y devuelve el númerdo de proyectos en el estado y el número de profesores contratados y nombrados
    public function docentesEstadoProyectos($est){
    $tipo=($est<=6?1:2);
    $TITULO=($est<=6?"PENDIENTE DE REVISIÓN O DICTAMEN DE PROYECTO ":"PENDIENTE DE REVISIÓN O DICTAMEN DE BORRADOR");
    $this->load->view("pilar/head");
    echo "<div class='col-md-2'> </div>";
    echo "<div class='col-md-9'> ";
    echo "<img class='img-responsive' src='".base_url("vriadds/pilar/imag/pilar-head.jpg")."'></img>";
    echo " <h3 class='text-center'> <I> DOCENTES POR CARRERA</I></h3>";
    echo " <h3 class='text-center'> $TITULO</h3>";
    echo "<table style='width:100%' class='table table-striped '>
    <tr>
    <th> Número        </th>
    <th> Carrera     </th>
    <th> DOC NOM </th>
    <th> DOC CONT</th>
    <th> 2016   </th>
    <th> 2017   </th>
    <th> 2018   </th>
    <th> 2019   </th>
    <th> 2020   </th>
    <th> TOTAL  </th>
    </tr>";
    $flag=1;
    $carreras=$this->dbRepo->getSnapView("dicCarreras");
    foreach ($carreras->result() as $row) {
        $docentesn=$this->dbRepo->getSnapView("tblDocentes","IdCategoria < 13 AND IdCarrera=$row->Id")->num_rows();
        $docentesc=$this->dbRepo->getSnapView("tblDocentes","IdCategoria >= 13 AND IdCarrera=$row->Id")->num_rows();
        $npy2016=$this->dbPilar->getSnapView("tesTramites","Tipo=$tipo AND Estado<$est AND IdCarrera=$row->Id AND Anio =2016")->num_rows();
        $npy2017=$this->dbPilar->getSnapView("tesTramites","Tipo=$tipo AND Estado<$est AND IdCarrera=$row->Id AND Anio =2017")->num_rows();
        $npy2018=$this->dbPilar->getSnapView("tesTramites","Tipo=$tipo AND Estado<$est AND IdCarrera=$row->Id AND Anio =2018")->num_rows();
        $npy2019=$this->dbPilar->getSnapView("tesTramites","Tipo=$tipo AND Estado<$est AND IdCarrera=$row->Id AND Anio =2019")->num_rows();
        $npy2020=$this->dbPilar->getSnapView("tesTramites","Tipo=$tipo AND Estado<$est AND IdCarrera=$row->Id AND Anio =2020")->num_rows();
        $tot=$npy2019+$npy2016+$npy2018+$npy2017+$npy2020;
        echo"<tr>
        <td>$flag</td>
        <td>$row->Nombre</td>
        <td>$docentesn</td>
        <td>$docentesc</td>
        <td>$npy2016</td>
        <td>$npy2017</td>
        <td>$npy2018</td>
        <td>$npy2019</td>
        <td>$npy2020</td>
        <td>$tot</td>
        </tr>";
        $flag++;
    }
    echo "</table>";

    }


    // ACTA DE DELIBERACIÓN
    // Sustentación
    public function actaDeliberacion( $idTram=0 )
    {
                
        if( !$idTram ) return;

        $tram = $this->dbPilar->inProyTram($idTram);
        if( !$tram ){ echo "Inexistente"; return;}
        if( $tram->Estado < 6 ){ echo "No Aprobado"; return;}

			

       $dets = $this->dbPilar->inTramDetIter($idTram, 5);
        // iteración 4 presenta borrador
        // iteración 5 sustenta
               


        $pdf = new GenSexPdf();

        //$pdf->AddPage();
        $pdf->AddPageEx( 'P', '', 2 );
        $pdf->SetMargins( 18, 40, 20 );

        $pdf->Ln( 25 );
        //$pdf->SetFont( "Times", 'B', 15 );


        //$pdf->Cell( 28, 9, $tram->Codigo, 1, 0, 'C' ); el código ya no va acá
        $pdf->BarCode39( 150, 34, $tram->Codigo );
        mlQrRotulo( $pdf, 19, 240, $tram->Codigo );


        $txtFacultadPerse=ucwords(strtolower($this->dbRepo->inFacultad($tram->IdCarrera)));
        $txtEscuelaPerse=ucwords(strtolower($this->dbRepo->inCarrera($tram->IdCarrera)));

        $txtFacultad="Facultad de ".$txtFacultadPerse;
        $txtEscuela="Escuela Profesional de ".$txtEscuelaPerse;
        $pdf->Ln( 10 );
        $pdf->SetFont( "Arial", 'B', 11 );
        $pdf->Cell( 174, 5, toUTF($txtFacultad), 0, 1, 'C' );
        $pdf->Cell( 174, 5, toUTF($txtEscuela), 0, 1, 'C' );
        $pdf->Ln(5);


        // agregar ruta en la BD de la imagen

        $codCarrera= $tram->IdCarrera;
        $rutaEscudo=$this->dbRepo->getOneField("dicCarreras","RutaEscudo","Id=".$codCarrera); 

        $pdf->Cell(70,40, "",0);
        $pdf->Cell(46,40, $pdf->Image($rutaEscudo, $pdf->GetX(), $pdf->GetY(),30),0, 0,'R');
        $pdf->Cell(58,40, "",0);
        $pdf->Ln(5);
        
        


        $cadTitulo="ACTA DE EVALUACIÓN DE TESIS Nº ";
        $numActa="001"; // agregar función que lleve la cuenta de actas por escuela
        $pdf->Ln( 40 );
        $pdf->SetFont( "Arial", 'B', 14 );
        $pdf->Cell( 174, 5, toUTF($cadTitulo).$numActa, 0, 1, 'C' );


        $dia = (int) substr( $dets->Fecha, 8, 2 );
        $mes = mlNombreMes( substr($dets->Fecha,5,2) );
        $ano = (int) substr( $dets->Fecha, 0, 4 );
        $hor = substr( $dets->Fecha, 11, 8 );


        


        $str = "El jurado revisor ha calificado el trabajo de tesis titulado:";

        $pdf->Ln( 7 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($str) );


        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($dets->Titulo), 0, 'C' );

        $strBachiller = "Presentado por el(la) Bachiller:";
        $tes = $this->dbPilar->inTesista($tram->IdTesista1, true);
        if( $tram->IdTesista2 != 0 ){
            $strBachiller= "Presentado por los Bachilleres:";
            $tes = $tes ."\n". $this->dbPilar->inTesista($tram->IdTesista2, true);
        }

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($strBachiller),0 );

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($tes), 0, 'C' );

        
        
        $strCod=$this->dbPilar->getOneField("tblTesistas","Codigo","Id=".$tram->IdTesista1);
        $strCod1=$this->dbPilar->getOneField("tblTesistas","Codigo","Id=".$tram->IdTesista2);

        $strCodPY= $tram->Codigo;
        $strTexto = "Con código de matrícula ".$strCod;
        $strTextoCodPY = " y código de proyecto : ".$strCodPY;
        if( $tram->IdTesista2 != 0 ){
            $strTexto= $strTexto." y $strCod1 ";
        }
        //obtener código de matrícula
        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->Cell( 174, 5, toUTF($strTexto.$strTextoCodPY),0);

        // carrera
        $txtCarrera = $this->dbRepo->inCarrera($tram->IdCarrera);
        $pdf->Ln(6);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 50, 5, toUTF("de la Escuela Profesional de"),0, 'L' );
        $pdf->SetFont( "Arial", "B", 10 );
        $pdf->Cell( 40, 5, toUTF(": ".$txtCarrera), 0, '' );



        $asesor = $this->dbRepo->inDocenteEx( $tram->IdJurado4 );

        $pdf->Ln(6);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 50, 5, "Asesor ", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($asesor), 0, 0, "L" );

                
        $resEvaluacion= "APROBADO"; // función de resultado de evaluación
       
        $codCarrera= $tram->IdCarrera;
        $denominacion=$this->dbRepo->getOneField("dicCarreras","Titulo","Id=".$codCarrera); 

        $pdf->Ln(6);
        $pdf->Cell( 60, 5, toUTF("Siendo el resultado de la evaluación"), 0, 0, "L" );
        $pdf->Cell( 80, 5, ": " .toUTF($resEvaluacion), 0, 0, "L" );


        $pdf->Ln(10);
        $pdf->Cell( 50, 5, "Por lo expuesto, el(la) bachiller ", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($tes), 0, 0, "L" );

        $pdf->Ln(6);
        $pdf->Cell( 83, 5, toUTF("queda expedito para recibir el Título Profesional de: "), 0, 0, "L" );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->Cell( 70, 5, toUTF($denominacion), 0, 0, "L" ); 
        
               

        $cadenaFe = "Para dar fe de ello, queda asentada la presente acta ";

        $pdf->Ln(10);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 174, 5, toUTF($cadenaFe), 0, 'J' );

                 
        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
                

        $jurado1 = $this->dbRepo->inDocenteEx( $tram->IdJurado1 );
        $jurado2 = $this->dbRepo->inDocenteEx( $tram->IdJurado2 );
        $jurado3 = $this->dbRepo->inDocenteEx( $tram->IdJurado3 );
        $jurado4 = $this->dbRepo->inDocenteEx( $tram->IdJurado4 );

        $pdf->Ln(4);
        $pdf->Cell( 50, 5, "Presidente", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado1), 0, 1, "L" );

        $pdf->Cell( 50, 5, "Primer Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado2), 0, 1, "L" );

        $pdf->Cell( 50, 5, "Segundo Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado3), 0, 1, "L" );

        $pdf->Cell( 50, 5, "Asesor", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado4), 0, 1, "L" );
       
        
        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->MultiCell( 174, 5.5, toUTF("Puno, $mes de $ano"), 0, 'R' );


        $pdf->Output();
    }



 public function acta2( $idTram=0 )
    {
                
        if( !$idTram ) return;

        $tram = $this->dbPilar->inProyTram($idTram);
        if( !$tram ){ echo "Inexistente"; return;}
        if( $tram->Estado < 13 ){ echo "No Aprobado"; return;}

        $dets = $this->dbPilar->inTramDetIter($idTram, 5);
        if( !$dets ) return;
        // iteración 4 presenta borrador
        // iteración 5 sustenta
               
        $acta = $this->dbPilar->getSnapRow("tesSustenAct","IdTramite=$idTram");
        if( !$acta ) { echo "No hay Acta "; return;}

        $pdf = new GenSexPdf();

        //$pdf->AddPage();
        $pdf->AddPageEx( 'P', '', 2 );
        $pdf->SetMargins( 18, 40, 20 );

        $pdf->Ln( 25 );
        //$pdf->SetFont( "Times", 'B', 15 );


        //$pdf->Cell( 28, 9, $tram->Codigo, 1, 0, 'C' ); el código ya no va acá
        $pdf->BarCode39( 150, 34, $tram->Codigo );
        mlQrRotulo( $pdf, 19, 240, $tram->Codigo );


        $txtFacultadPerse=toUTF(ucwords(strtolower($this->dbRepo->inFacultad($tram->IdCarrera))));
        $txtEscuelaPerse=toUTF(ucwords(strtolower($this->dbRepo->inCarrera($tram->IdCarrera))));

        $txtFacultad="Facultad de ".$txtFacultadPerse;
        $txtEscuela="Escuela Profesional de ".$txtEscuelaPerse;
        $pdf->Ln( 10 );
        $pdf->SetFont( "Arial", 'B', 11 );
        $pdf->Cell( 174, 5, toUTF(strtoupper($txtFacultad)), 0, 1, 'C' );
        $pdf->Cell( 174, 5, toUTF(strtoupper($txtEscuela)), 0, 1, 'C' );
        $pdf->Ln(5);


        // agregar ruta en la BD de la imagen

        $codCarrera= $tram->IdCarrera;
        $rutaEscudo=$this->dbRepo->getOneField("dicCarreras","RutaEscudo","Id=".$codCarrera); 

        $pdf->Cell(70,40, "",0);
        if ($rutaEscudo) {
            $pdf->Cell(46,40, $pdf->Image($rutaEscudo, $pdf->GetX(), $pdf->GetY(),30),0, 0,'R');
        }
        $pdf->Cell(58,40, "",0);
        $pdf->Ln(5);
        
        


        $cadTitulo="ACTA DE EVALUACIÓN DE TESIS Nº ";
        // $acta="001"; // agregar función que lleve la cuenta de actas por escuela
        $pdf->Ln( 40 );
        $pdf->SetFont( "Arial", 'B', 14 );
        $pdf->Cell( 174, 5, toUTF($cadTitulo).$acta->Num, 0, 1, 'C' );


        $dia = (int) substr( $dets->Fecha, 8, 2 );
        $mes = mlNombreMes( substr($dets->Fecha,5,2) );
        $ano = (int) substr( $dets->Fecha, 0, 4 );
        $hor = substr( $dets->Fecha, 11, 8 );


        


        $str = "El jurado revisor ha calificado el trabajo de tesis titulado:";

        $pdf->Ln( 7 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($str) );


        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($dets->Titulo), 0, 'C' );

        $strBachiller = "Presentado por el(la) Bachiller:";
        $tes = $this->dbPilar->inTesista($tram->IdTesista1, true);
        if( $tram->IdTesista2 ){
            $str = "Presentado por los Bachilleres:";
            $tes = $tes ."\n". $this->dbPilar->inTesista($tram->IdTesista2, true);
        }

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->MultiCell( 174, 5, toUTF($strBachiller),0 );

        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->MultiCell( 174, 6, toUTF($tes), 0, 'C' );

        
        
        $strCod=$this->dbPilar->getOneField("tblTesistas","Codigo","Id=".$tram->IdTesista1);

        $strCodPY= $tram->Codigo;
        $strTexto = "Con código de matrícula ".$strCod;
        $strTextoCodPY = " y código de proyecto : ".$strCodPY;
        //obtener código de matrícula
        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
        $pdf->Cell( 174, 5, toUTF($strTexto.$strTextoCodPY),0);

        // carrera
        $txtCarrera = $this->dbRepo->inCarrera($tram->IdCarrera);
        $pdf->Ln(6);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 50, 5, toUTF("de la Escuela Profesional de"),0, 'L' );
        $pdf->SetFont( "Arial", "B", 10 );
        $pdf->Cell( 40, 5, toUTF(": ".$txtCarrera), 0, '' );



        $asesor = $this->dbRepo->inDocenteEx( $tram->IdJurado4 );

        $pdf->Ln(6);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 50, 5, "Asesor / asesor ", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($asesor), 0, 0, "L" );

                
        $resEvaluacion= "$acta->Obs"; // función de resultado de evaluación
       
        $codCarrera= $tram->IdCarrera;
        $denominacion=$this->dbRepo->getOneField("dicCarreras","Titulo","Id=".$codCarrera); 

        $pdf->Ln(6);
        $pdf->Cell( 60, 5, toUTF("Siendo el resultado de la evaluación"), 0, 0, "L" );
        $pdf->Cell( 80, 5, ": " .toUTF($resEvaluacion), 0, 0, "L" );


        $pdf->Ln(10);
        $pdf->Cell( 50, 5, "Por lo expuesto, el(la) bachiller ", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($tes), 0, 0, "L" );

        $pdf->Ln(6);
        $pdf->Cell( 83, 5, toUTF("queda expedito para recibir el Título Profesional de: "), 0, 0, "L" );
        $pdf->SetFont( "Arial", 'B', 10 );
        $pdf->Cell( 70, 5, toUTF($denominacion), 0, 0, "L" ); 
        
               

        $cadenaFe = "Para dar fe de ello, queda asentada la presente acta ";

        $pdf->Ln(10);
        $pdf->SetFont( "Arial", "", 10 );
        $pdf->Cell( 174, 5, toUTF($cadenaFe), 0, 'J' );

                 
        $pdf->Ln( 4 );
        $pdf->SetFont( "Arial", '', 10 );
                

        $jurado1 = $this->dbRepo->inDocenteEx( $tram->IdJurado1 );
        $jurado2 = $this->dbRepo->inDocenteEx( $tram->IdJurado2 );
        $jurado3 = $this->dbRepo->inDocenteEx( $tram->IdJurado3 );
        $jurado4 = $this->dbRepo->inDocenteEx( $tram->IdJurado4 );

        $pdf->Ln(4);
        $pdf->Cell( 50, 5, "Presidente", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado1), 0, 1, "L" );

        $pdf->Cell( 50, 5, "Primer Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado2), 0, 1, "L" );

        $pdf->Cell( 50, 5, "Segundo Miembro", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado3), 0, 1, "L" );

        $pdf->Cell( 50, 5, "Asesor", 0, 0, "L" );
        $pdf->Cell( 100, 5, ": " .toUTF($jurado4), 0, 1, "L" );
       
        
        $pdf->Ln(8);
        $pdf->SetFont( "Arial", "B", 11 );
        $pdf->MultiCell( 174, 5.5, toUTF("Puno, $mes de $ano"), 0, 'R' );


        $pdf->Output();
    }

    public function docentesFacultad($IdFacultad){
     $facult=$this->dbRepo->getSnapView("tblDocentes","IdFacultad=$IdFacultad");
     foreach ($facult->result() as $row) {
         echo "$row->Correo<br>";
     }
    }

}
