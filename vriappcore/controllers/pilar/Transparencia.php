<?PHP  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//------------------------------------------------------------------------------

include( "absmain/mlLibrary.php" );
date_default_timezone_set('America/Lima'); //Agregado unuv1.0



class Transparencia extends CI_Controller {

    public function __construct()
    {
        parent:: __construct();
        $this->load->model('dbPilar');
        $this->load->model('dbRepo');
    }

    public function index2()
    {
        $estado = mlSecurePost( "estado", 6 );
        $carrer = mlSecurePost( "carrer" );

        if( $estado > 0 ) $filtro = "Estado = $estado";
        if( $carrer > 0 ) $filtro = "IdCarrera = $carrer";
        if( $estado>0 && $carrer>0 )
            $filtro = "Estado=$estado AND IdCarrera=$carrer";

        $esql = "SELECT MONTH(FechaReg), FechaReg, COUNT(*) Registrados
                   FROM tblTesistas
                  WHERE MONTH(FechaReg) IN (3,4,5,6,7,8,9,10,11)
                  GROUP BY MONTH(FechaReg)
                  ORDER BY MONTH(FechaReg)";


        $args = array(
                'estado' => $estado,
                'carrer' => $carrer,
                'Estads' => $this->dbPilar->getTable( $esql ),
                'Carreras' => $this->dbPilar->reposSnapView( "dicCarreras", "1 ORDER BY Nombre" ),
                'TesisSub' => $this->dbPilar->getSnapView( "tesTramites", "$filtro ORDER BY FechaUlt DESC" )
            );



        $this->load->view("head");
        $this->load->view("header");
        $this->load->view("pilar/transpa", $args );
        $this->load->view("webFoot");
    }

    public function index( $carr=0 )
    {
        $carr = !$carr? 1 : $carr;

        $filt = "IdCarrera='$carr' AND Tipo>='1' AND Estado>='6' ORDER BY Estado DESC, IdLinea";
        $proy = $this->dbPilar->getSnapView( "vxTesTramites", $filt );

        $this->load->view( "pilar/web/header" );
        $this->load->view( "pilar/web/tranpa", array('proy'=>$proy) );
    }

    public function jsGrafs()
    {
        echo "<canvas id='myChart' width=300 height=300></canvas>";
        echo "<script> ttraGraficar(); </script>";
    }
}