<?php
/*
Description: Implement ADODB methods with PDO
Version: 0.0.1
Author: Tomasz Mrozinski
Author URI: mrozinski.net
*/

class PDO_ADODB extends PDO{
	
	private $result = null;
	
	public function __construct( $dsn, $username, $passwd, $options=null ){

		parent::__construct( $dsn, $username, $passwd, $options=null );
		
		//enable exceptions for PDO by default
		parent::setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		
	}
	
	public function GetAll( $sql ){

		$this->result = $this->prepare( $sql );
		$this->result->execute();

		return $this->result->fetchAll( PDO::FETCH_ASSOC );

	}
	
	public function GetRow( $sql ){

		if( stristr( $sql, 'LIMIT 1' ) )throw new PDOException( 'You cannot use "LIMIT 1" in sql.' );
	
		$this->result = $this->prepare( $sql );
		$this->result->execute();

		return $this->result->fetch( PDO::FETCH_ASSOC );

	}
	
	public function GetOne( $sql ){
		
		if( stristr( $sql, 'LIMIT 1' ) )throw new PDOException( 'You cannot use "LIMIT 1" in sql.' );
		
		$this->result = $this->prepare( $sql . ' LIMIT 1' );
		$this->result->execute();

		return $this->result->fetchColumn( 0 );

	}
	
	public function GetCol( $sql ){

		$this->result = $this->prepare( $sql );
		$this->result->execute();

		return $this->result->fetchColumn();

	}
	
	public function GetAssoc( $sql ){

		$this->result = $this->prepare( $sql );
		$this->result->execute();

		return $this->result->fetchAll( PDO::FETCH_COLUMN|PDO::FETCH_GROUP );
	}
	
	public function Execute( $sql ){

		$this->result = $this->prepare( $sql );

		return $this->result->execute();

	}
	
	public function ErrorMsg(){

		return implode( ', ', $this->errorInfo() );

	}
	
	public function Insert_ID(){

		return $this->lastInsertId();

	}
}
?>
