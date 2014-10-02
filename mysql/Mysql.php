<?php

class Mysql {

	private $mysqli;

	function __construct()
	{
		$ini = parse_ini_file( "../site.ini" );
		if( $ini )
		{
			$this->mysqli = mysqli_connect( $ini[ "MysqlHost" ], $ini[ "MysqlUser" ], $ini[ "MysqlPassword" ], $ini[ "MysqlDatabase" ] );
		}
	}

	public function Execute( $statement )
	{
		mysqli_query( $this->mysqli, mysqli_real_escape_string( $this->mysqli, $statement ) );
	}

	public function Close( )
	{
		mysqli_close( $this->mysqli );
	}
}