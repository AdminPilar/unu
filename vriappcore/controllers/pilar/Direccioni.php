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
****************************** *********************************************/


include( "absmain/mlLibrary.php" );


define( "PILAR_CORDIS", "AdmCoords" );
define( "ANIO_PILAR", "2018" );
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


class Direccioni extends CI_Controller {
/***************************************************************************
*  Operaciones del Coordinador - PILAR V.3.0
*      (1) Actualizó el estado de un docente.
*      (2) Recepción de Ejemplares de Borrador
*      (3) Notificó a un Docente que tiene proyectos pendientes
*      (3) Revisar formato  y pasar el proyecto al Asesor.
*      (4) Validar Linea de Investigación
*
*      $operaciones=array(
1   =>'Actualizó el estado de un docente',
2   =>'Recepcionó Ejemplares',
3   =>'Publica Sustentación',
4   =>'Validar Linea de Investigación',
);
*
**************************************************************************/
public function __construct()
{
   parent::__construct();
   $this->load->model('dbPilar');
   $this->load->model('dbRepo');
   $this->load->model('dbFedu');
   $this->load->library("GenSession");
   $this->load->library('GenSexPdf');
   $this->load->library('GenMailer');
}
public function setSession(){
    // $this->gensession->SetCordLogin(PILAR_CORDIS,$row->Id,$row->Resp,$row->Id_Facultad,$row->UserLevel);

    $this->gensession->SetCordLogin(PILAR_CORDIS,36,'FRED TORRES',12,3);
    redirect('pilar/Direccioni','refresh');
}
public function index()
{
   if( mlPoorURL() )
      redirect( mlCorrectURL() );
   $sess = $this->gensession->GetSessionData(PILAR_CORDIS);
   if( !$sess ){
   //echo "Err no sess";
      redirect( base_url("pilar"), 'refresh');
      return;
   }
   // Área de Administración del Administrador
   if($sess->userLevel==1){
      $escuelas=$this->dbRepo->getTable("dicCarreras");
      $this->load->view("pilar/cord/header",array('escuelas' =>$escuelas,'sess'=>$sess ));
   }

   // Área de Administración del Asesor de Investigación
   if($sess->userLevel==2){
      $useri=$this->dbPilar->getSnapRow("tblSecres","Id=$sess->userId");
      $escuelas=$this->dbRepo->getSnapView("dicCarreras","Id=$useri->IdCarrera");
      $this->load->view("pilar/cord/header",array('escuelas' =>$escuelas,'sess'=>$sess ));
   }
   // Área de Administración del Sub Asesor de Investigación
   if($sess->userLevel==3){
      $escuelas=$this->dbRepo->getTable("dicCarreras","IdFacultad='$sess->IdFacultad'");
      $this->load->view("pilar/cord/header",array('escuelas' =>$escuelas,'sess'=>$sess ));
   }

   // Área de Administración de la Secretaría de la Direccion de Investigación.
   if($sess->userLevel==4){
      $useri=$this->dbPilar->getSnapRow("tblSecres","Id=$sess->userId");
      $escuelas=$this->dbRepo->getSnapView("dicCarreras","Id=$useri->IdCarrera");
      $this->load->view("pilar/cord/header",array('escuelas' =>$escuelas,'sess'=>$sess ));
   }
}
// Cambio de Carrera en el Panel de Coordinador 
public function setCarrera($idCarr){
   $sess = $this->gensession->GetSessionData(PILAR_CORDIS);
   mlSetGlobalVar('IdCarrera',$idCarr);
   if(mlGetGlobalVar("IdCarrera")){
      $this->load->view("pilar/cord/menu");
      $this->load->view("pilar/cord/panel",array('sess' =>$sess ));
   }
   else{
      echo "<div class='text-center'> <h4>Estimado $sess->userName, es necesario es necesario seleccionar una escuela profesional para porder ingresar al área de administración.<br><small>Esquina superior derecha.</small></h4></div>";
   }
} 
// Vista de Inicio Cordinador
public function vwInicio(){
   $this->load->view('pilar/cord/view/inicio');
}
// Vista de Docentes Cordinador
public function vwDocentes(){
   $this->load->view('pilar/cord/view/docentes');
}
// js Docente Info
public function jsmdlDocInfo($idDoc){
   $this->load->view("pilar/cord/view/docentes_infomdl",array('IdDocente' =>$idDoc));
}

 }
//- EOFs