<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

//
// Enhanced Model for: Integrated VRI
//
// Codificado por: Dr. Ramiro Pedro Laura Murillo : 2017 - 2020
//


class PedrixAdo extends CI_Model
{

    var $innerDB;

    //-------------------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function setDB( $dbname )
    {
        $this->innerDB = $dbname;
    }

    public function getDB()
    {
        return "USE: $this->innerDB";
    }
    //-------------------------------------------------------------------------------
    public function Insert( $table, $arrData )
    {
        //$this->db->trans_start();
        $this->db->insert( "$this->innerDB.$table", $arrData );
        //$this->db->trans_complete();
        $id = $this->db->insert_id();
        return $id;
    }
    //-------------------------------------------------------------------------------
    public function Update( $table, $arrFields, $idReg )
    {
        $this->db->where( 'Id', $idReg );
        $this->db->update( "$this->innerDB.$table", $arrFields );
    }

    public function UpdateEx( $table, $arrFields, $arrFilter )
    {
        $this->db->where( $arrFilter );
        $this->db->update( "$this->innerDB.$table", $arrFields );
    }
    //-------------------------------------------------------------------------------
    public function DeleteEx( $table, $arrFilter )
    {
        $this->db->where( $arrFilter );
        $this->db->delete( "$this->innerDB.$table" );
    }

    public function Delete( $table, $id )
    {
		// interno ya procesa
        $this->DeleteEx( $table, array('Id' => $id ) );
    }
    //-------------------------------------------------------------------------------
    public function getTable( $table, $arrCriteria=null )
    {
        if( $arrCriteria != null )
            $this->db->where( $arrCriteria );

        return  $this->db->get( "$this->innerDB.$table" );
    }
    //-------------------------------------------------------------------------------
    public function getSnapView( $table, $strCriteria=null, $extra="" )
    {
        if( is_array($strCriteria) or $strCriteria==null )
            $query = "SELECT * FROM $this->innerDB.$table $extra";
        else
            $query = "SELECT * FROM $this->innerDB.$table WHERE $strCriteria $extra";

        return $this->db->query( $query );
    }

    public function getResultSet( $table, $filter )
    {
        $tbl = $this->getSnapView( $table, $filter );
        if( $tbl->num_rows() >= 1 )
            return $tbl->result();

        return false;
    }

    public function getSnapRow( $table, $strCriteria=null, $extra="" )
    {
        $table = $this->getSnapView( $table, $strCriteria, $extra );
        if( $table )
            return $table->row();

        return null;
    }
    //-------------------------------------------------------------------------------
    public function getTotalRows( $table, $arrfilter )
    {
        $tmp = $this->getSnapView( $table, $arrfilter );
        return $tmp->num_rows();
    }

    public function getOneField( $table, $field, $filter )
    {
        $tbl = $this->getSnapView( $table, $filter );
        if( $tbl->num_rows() >= 1 ){
            $row = $tbl->row_array();
            return $row[ $field ];
        }

        return  null;
    }
    //-------------------------------------------------------------------------------
    public function getQuery( $query )
    {
        // a resultset()
        //
        return $this->db->query( $query );
    }


    //-------------------------------------------------------------------------------
    // area de Metodos para Logueo y claves basadas en SHA1
    //-------------------------------------------------------------------------------
    public function loginBase( $table, $field, $value, $pass )
    {
        $row = $this->getSnapRow( $table, "$field='$value' AND Clave='$pass'" );
        return $row;
    }

    public function loginByUser( $table, $user, $pass )
    {
        return $this->loginBase( $table, "Usuario", $user, $pass );
    }

    public function loginByMail( $table, $mail, $pass )
    {
        return $this->loginBase( $table, "Correo", $mail, $pass );
    }

    // modificado para usuarios sin cifrado
    public function loginByDNI( $table, $dni, $pass )
    {
        // return $this->loginBase( $table, "DNI", $dni, $pass );
        return $this->getSnapRow( $table, "DNI='$dni' AND PASSWORD(Clave)=PASSWORD('$pass')" );
    }

}
