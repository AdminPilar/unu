<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//
// add our Base Model
//
require_once APPPATH."models/PedrixAdo.php";


//
// our Composed Model to WebApp
//

class DbRepo extends PedrixAdo
{

    public function __construct()
    {
        parent::__construct();

        //
        // set the local class DB access
        //
        $this->setDB( "vriunap_absmain" ); // modificar testing-produccion
    }

    //---------------------------------------------------------------------------
    // area de funciones propias para DB manage por ROW ultima iteracion
    //---------------------------------------------------------------------------
    function inDocente( $id )
    {
        if( !$id ) return null;
        $row = $this->getSnapRow( "tblDocentes", "Id=$id" );
        return ( $row->Apellidos ." ". mb_strtoupper( $row->Nombres ) );
    }

    function inDocDni( $id )
    {
        if( !$id ) return null;
        $row = $this->getSnapRow( "tblDocentes", "Id=$id" );
        return $row->DNI;
    }

	function inCorreo( $id )
	{
		if( !$id ) return null;
		return $this->getOneField( "tblDocentes", "Correo", "Id=$id" );
	}
    function inCelu( $id )
    {
        if( !$id ) return null;
        return $this->getOneField( "tblDocentes", "NroCelular", "Id=$id" );
    }

    function inDocenteEx( $id )
    {
        if( !$id ) return null;
        $row = $this->getSnapRow( "tblDocentes", "Id=$id" );

        if( !$row ) return null;
        $datos = "";

        if( $res=$this->inGrado($id) )
            $datos = "$res " .mb_strtoupper($row->Nombres). " $row->Apellidos";
        else
            $datos = mb_strtoupper($row->Nombres) . " $row->Apellidos";
        return $datos;
    }

    // id de docente
    function inGrado( $id )
    {
        if( !$id ) return null;

        $this->db->where( "IdDocente='$id' ORDER BY IdGrado" );
        $table = $this->db->get( "vriunap_pilar3.docEstudios" ); // modificar testing-produccion
		if( ! $table->num_rows() ) return null;

        return $table->row()->AbrevGrado;
    }

    function inDocenteRow( $id )
    {
        if( !$id ) return null;
        return $this->getSnapRow( "vwDocentes", "Id=$id" );
    }

    //agregado unuv2.0
    function inDocenteNom( $id )
    {
        if( !$id ) return null;
        $row =$this->getSnapRow( "vwDocentes", "Id=$id");
        if( ! $row ) return null;
        return $row->DatosNom;
    }

    function inCarrera( $id, $titu=0 )
    {
        $row = $this->getSnapRow( "dicCarreras", "Id=$id" );
        if( ! $row ) return null;

        // Titulo profesional
        if( $titu ) return $row->Titulo;

        return $row->Nombre;
    }

    function inCarreDoc( $iddoc=0 )
    {
        if( ! $iddoc ) return null;

        $idc = $this->getOneField( "tblDocentes", "IdCarrera", "Id=$iddoc" );
        $car = $this->getOneField( "dicCarreras", "Nombre", "Id=$idc" );

        return $car;
    }
    function inCarreDocIds( $iddoc=0 )
    {
        if( ! $iddoc ) return null;

        $idc = $this->getOneField( "tblDocentes", "IdCarrera", "Id=$iddoc" );
        $car = $this->getOneField( "dicCarreras", "Id", "Id=$idc" );

        return $car;
    }
    function inCarreDocId( $iddoc=0 )
    {
        if( ! $iddoc ) return null;

        $idc = $this->getOneField( "tblDocentes", "IdCarrera", "Codigo=$iddoc" );
        $car = $this->getOneField( "dicCarreras", "Id", "Id=$idc" );

        return $car;
    }

    function inFacultad( $id )
    {
        $row = $this->getSnapRow( "dicCarreras", "Id=$id" );
        if( ! $row ) return null;

        $row = $this->getSnapRow( "dicFacultades", "Id=$row->IdFacultad" );
        if( ! $row ) return null;

        return $row->Nombre;
    }

    function inAreaInv( $idLn )
    {
        if( ! $idLn ) return null;
        $IdArea = $this->getOneField( "tblLineas", "IdArea", "Id=$idLn" );
        return $this->getOneField( "ocdeAreas", "Nombre", "Id=$IdArea" );
    }

    function inLineaInv( $idLn )
    {
        if( ! $idLn ) return null;
        return $this->getOneField( "tblLineas", "Nombre", "Id=$idLn" );
    }

    function inByDatos( $data )
    {
        if( ! $data ) return null;
        return $this->getOneField( "vwDocentes", "Id", "DatosNom LIKE '%$data%' " );
    }
    //Agregado unuv1.0 - estado acta de aprobacion
    function inIdFacultad( $id )
    {
        $row = $this->getSnapRow( "dicCarreras", "Id=$id" );
        if( ! $row ) return null;

        $row = $this->getSnapRow( "dicFacultades", "Id=$row->IdFacultad" );
        if( ! $row ) return null;

        return $row->Id;
    }
}
