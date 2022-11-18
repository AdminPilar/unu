<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//
// add our Base Model
//
require_once APPPATH."models/PedrixAdo.php";


//
// our Composed Model to WebApp
//

class DbPilar extends PedrixAdo
{

    public function __construct()
    {
        parent::__construct();

        //
        // set the local class DB access
        //
        $this->setDB( "desarrollo_pilar3" ); // modificar testing-produccion
    }

    //---------------------------------------------------------------------------
    // area de funciones propias para DB manage por ROW ultima iteracion
    //---------------------------------------------------------------------------
	public function inCorreo( $idTes )
	{
		if( ! $idTes ) return null;
		return $this->getOneField( "tblTesistas", "Correo", "Id=$idTes" );
	}

    public function inProyTram( $idtram )
    {
        if( !$idtram ) return null;
        return $this->getSnapRow( "tesTramites", "Id=$idtram" );
    }

    // detalles de habilitación
    public function inHabilits($idtram)
    {
        if( !$idtram ) return null;

        return $this->dbPilar->getSnapView("tesProyHabs","IdTram=$idtram");
    }

    // las N filas de estado
    public function inProyDetail( $idtram )
    {
        if( !$idtram ) return null;
        return $this->getSnapView( "tesTramsDet", "IdTramite=$idtram", "ORDER BY Iteracion DESC" );
    }

    // solo el ultimo Iteracion de detTrams
    public function inLastTramDet( $idtram )
    {
        if( !$idtram ) return null;
        return $this->getSnapRow( "tesTramsDet", "IdTramite=$idtram", "ORDER BY Iteracion DESC LIMIT 1" );
    }

    public function inTramDetIter( $idtram, $iter=1 )
    {
        if( !$idtram ) return null;
        return $this->getSnapRow( "tesTramsDet", "IdTramite=$idtram AND Iteracion=$iter" );
    }

    // buscar proyTramite por tesita1 or tesita2
    public function inTramByTesista( $idUser )
    {
        if( !$idUser ) return null;
        return $this->getSnapRow( "tesTramites", "(IdTesista1=$idUser OR IdTesista2=$idUser) AND Tipo>=1" );
    }
    public function inTramByTesista1( $idUser )
    {
        if( !$idUser ) return null;
        return $this->getOneField( "tesTramites", "Id","(IdTesista1=$idUser OR IdTesista2=$idUser) AND Tipo>=1" );
    }

    public function inTramByCodigo( $codigo )
    {
        if( !$codigo ) return null;
        return $this->getSnapRow( "tesTramites", "Codigo='$codigo'" );
    }

    public function inTesistByCod( $codigo )
    {
        if( !$codigo ) return null;
        return $row = $this->getSnapRow( "vxDatTesistas", "Codigo='$codigo'" );
    }

    public function inRowTesista( $id )
    {
        if( !$id ) return null;

        // vxDatTesista  :: Det es compuesto no usar el query
        return $row = $this->getSnapRow( "vxDatTesistas", "Id=$id" );
    }

    // de Tesistas datos completos
    public function inTesista( $id, $apes=0 )
    {
        if( !$id ) return null;

        $row = $this->getSnapRow( "tblTesistas", "Id=$id" );
        if( !$row ) return null;

        if( $apes == 0 )
            return  "$row->Apellidos, $row->Nombres";

        return  "$row->Nombres $row->Apellidos";
    }

    // los 2 o 1 tesistas del Tramite
    public function inTesistas( $idtram )
    {
        if( !$idtram ) return null;

        $tram = $this->getSnapRow( "tesTramites", "Id=$idtram" );
        $autr = $this->inTesista( $tram->IdTesista1 );
        if( $tram->IdTesista2 ) {
            $otro = $this->inTesista( $tram->IdTesista2 );
            $autr = "$autr y $otro";
        }

        return $autr;
    }

    public function inSemesTesistas($idtram){

        if( !$idtram ) return null;

        $tram = $this->getSnapRow( "tesTramites", "Id=$idtram" );
        $autr = $this->getOneField("tblTesistas","SemReg","Id=$tram->IdTesista1");
        if( $tram->IdTesista2 ) {
            $otro = $this->getOneField("tblTesistas","SemReg","Id=$tram->IdTesista2");
            $autr = "$autr y $otro";
        }

        return $autr;

    }
    public function inFechSustent( $idtram )
    {
        if( !$idtram ) return null;
        return $this->getOneField( "vxSustens", "Fecha", "IdTramite=$idtram" );
    }

    public function inGradoDoc( $id )
    {
        if( !$id ) return null;
        return $this->getOneField( "docEstudios", "AbrevGrado", "IdDocente=$id ORDER BY IdGrado" );
    }

	public function inCorrecs( $idtram, $jur, $iter=1 )
	{
        if( !$idtram or !$jur ) return null;

		$id   = 0;
		$tram = $this->inProyTram( $idtram );

		if( $jur == 1 ) $id = $tram->IdJurado1;
		if( $jur == 2 ) $id = $tram->IdJurado2;
		if( $jur == 3 ) $id = $tram->IdJurado3;
		if( $jur == 4 ) $id = $tram->IdJurado4;

		$filtro = "Iteracion=$iter AND IdTramite=$idtram AND IdDocente=$id";
		return $this->getSnapView( "tblCorrects", $filtro );
	}

    public function inNCorrecs( $idtram, $idjur, $iter=1 )
    {
        $filtro = "Iteracion=$iter AND IdTramite=$idtram AND IdDocente=$idjur";
		return $this->getSnapView( "tblCorrects", $filtro ) -> num_rows();
    }

    // que posicion de jurado ocupo en el Trámite
    public function inPosJurado( $rowTram, $idJur )
    {
        if( $rowTram->IdJurado1 == $idJur ) return 1;
        if( $rowTram->IdJurado2 == $idJur ) return 2;
        if( $rowTram->IdJurado3 == $idJur ) return 3;
        if( $rowTram->IdJurado4 == $idJur ) return 4;

        return 0;
    }

    // Jcesar devuelve el número de tesis sustentadas reales
    public function tesisSTDreal()
    {
        $tbl = $this->getTable( "vxSustens", "Pendiente=0" );
        $totaltesis= $tbl->num_rows();
        return $totaltesis;

    }

    public function inTitulo($idTram)
    {
        return $this->getOneField("tesTramsDet","Titulo","IdTramite=$idTram ORDER BY Iteracion DESC");
    }

    public function inEstado($idTram)
    {
        $est = $this->getOneField("tesTramites","Estado","Id=$idTram");
        return $this->getOneField("dicEstadTram","Nombre","Id=$est");
    }

    public function inTipo($idTram)
    {
        $est = $this->getOneField("tesTramites","Tipo","Id=$idTram");

        $a = "<p style='color:red'>ERROR</p>";

        if($est==0) $a = "Rechazado";
        if($est==1) $a = "Proyecto";
        if($est==2) $a = "Borrador";
        if($est==3) $a = "Sustentacion";

        return $a;
    }

    public function Analytics( $field, $order="ORDER BY Fi DESC" ) {

        $strQuery = "SELECT $field AS Item, count(*) AS Fi
                       FROM desarrollo_pilar3.logLogins 
                      GROUP BY $field
                      $order LIMIT 12"; // modificar testing-produccion

        return $this->getQuery( $strQuery );
    }


    // Jcesar devuelve el numero de tesis en estado 6
    public function tesisSTD6()
    {
        $tbl = $this->getTable( "tesTramites", "Estado=6" );
        $totaltesis= $tbl->num_rows();

        return $totaltesis;
    }

    // Numero de Tesis por Linea de Investigación para Docentes
    public function log($idLinea , $idDoc)
    {
        $tj1=$this->getTable("tesTramites", "Estado>=1 AND IdLinea=$idLinea AND IdJurado1=$idDoc");
        $tj2=$this->getTable("tesTramites", "Estado>=1 AND IdLinea=$idLinea AND IdJurado2=$idDoc");
        $tj3=$this->getTable("tesTramites", "Estado>=1 AND IdLinea=$idLinea AND IdJurado3=$idDoc");
        $tj4=$this->getTable("tesTramites", "Estado>=1 AND IdLinea=$idLinea AND IdJurado4=$idDoc");

        return $tj1->num_rows() + $tj2->num_rows() + $tj3->num_rows() + $tj4->num_rows();
    }

    public function totProys( $idDoc )
    {
        $sql = "( Tipo=1 OR Tipo=2 ) AND " .
               "(IdJurado1=$idDoc OR IdJurado2=$idDoc OR IdJurado3=$idDoc OR IdJurado4=$idDoc)";

        return $this->getSnapView( "tesTramites", $sql )->num_rows();
    }

    public function totProysEx( $idDoc, $tipo=1, $idLin=0 )
    {
        // si se considera nueva linea
        $spec = $idLin? "IdLinea=$idLin AND" : "";

        $sql = "Tipo=$tipo AND $spec " .
               "(IdJurado1=$idDoc OR IdJurado2=$idDoc OR IdJurado3=$idDoc OR IdJurado4=$idDoc)";

        return $this->getSnapView( "tesTramites", $sql )->num_rows();
    }

    public function cuentaProys( $idDoc , $tipo )
    {
        return $this->getSnapView( "tesTramites",
                    "( Tipo=$tipo ) AND " .
                    "(IdJurado1=$idDoc OR IdJurado2=$idDoc OR IdJurado3=$idDoc OR IdJurado4=$idDoc)"
              )->num_rows();
    }

    public function inCelTesista( $idtes )
    {
        if( !$idtes ) return null;
        return $this->getOneField( "tblTesistas", "NroCelular", "Id=$idtes" );
    }

    public function inAmpliacion( $idtram )
    {
        return $this->getSnapRow( "dicAmpliaciones", "IdTram=$idtram" );
    }

    //------------------------------------------------------------------
    // innecesario pues obtienes el ROW completo arriba
    //------------------------------------------------------------------


    //  Funcion despues de algo en una cadena
    public function after ($thisi, $inthat)
    {
        if (!is_bool(strpos($inthat, $thisi)))
        return substr($inthat, strpos($inthat,$thisi)+strlen($thisi));
    }
    //  Funcion antes de la algo en una cadena
    public function before ($thisi, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $thisi));
    }

}

