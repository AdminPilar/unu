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
define( "ANIO_PILAR", "2020" );
date_default_timezone_set('America/Lima'); //Agregado unuv1.0


class Consultas extends CI_Controller { 
/***************************************************************************
* Cambio de Roles de Usuario : 02/05/2018
* Operaciones del Coordinador - PILAR V.3.0
*      (1) Actualizó el estado de un docente.
*      (2) Recepción de Ejemplares de Borrador
*      (3) Notificó a un Docente que tiene proyectos pendientes
*      (3) Revisar formato  y pasar el proyecto al Asesor.
*      (4) Validar Linea de Investigación
*      (4) Rechazar Proyecto de Tesis por FORMATO
*
*      $operaciones=array(
      1   =>'Actualizó el estado de un docente',
      2   =>'Recepcionó Ejemplares', 
      3   =>'Publica Sustentación',
      4   =>'Validar Linea de Investigación',
      5   =>'Acceso a Cuenta'
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

public function linindex()
{
//$table = $this->dbpilar->getTable( 'tesTramites' );

//$this->load->view( "pilar/base", array( 'table'=>$table ) );
}

public function index()
{
   if( mlPoorURL() )
      redirect( mlCorrectURL() );
//
// en caso de admin crear nueva session admin por App
//

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
//      $this->load->view("pilar/cord/view/proyectos");

   }

   // Área de Administración del Asesor de Investigación
   if($sess->userLevel==2){
      $escuelas=$this->dbRepo->getTable("dicCarreras","IdFacultad='$sess->IdFacultad'");
      $this->load->view("pilar/cord/header",array('escuelas' =>$escuelas,'sess'=>$sess ));
   }
   // Área de Administración del Sub Asesor de Investigación
   if($sess->userLevel==3){
      $useri=$this->dbPilar->getSnapRow("tblSecres","Id=$sess->userId");
      $escuelas=$this->dbRepo->getSnapView("dicCarreras","Id=$useri->IdCarrera");
      $this->load->view("pilar/cord/header",array('escuelas' =>$escuelas,'sess'=>$sess ));
   }

   // Área de Administración de la Secretaría de la Direccion de Investigación.
   if($sess->userLevel==4){
      $escuelas=$this->dbRepo->getSnapView("dicCarreras","IdFacultad=$sess->IdFacultad");
      $this->load->view("pilar/cord/header",array('escuelas' =>$escuelas,'sess'=>$sess ));
   }
}


}
//- EO

