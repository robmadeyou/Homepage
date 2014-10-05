<?php

require_once( '../song/Song.php' );
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

	public function GetMysqli()
	{
		return $this->mysqli;
	}

	public function Execute( $statement )
	{
		if ( !mysqli_query( $this->mysqli, $statement ) )
		{
			print "<br>Query failed... well done<br>";
			print mysqli_error( $this->mysqli );
		};
	}

	public function InsertSong( $columnNames, $data )
	{
		$this->Execute( "INSERT INTO tblSong " . $this->ImplodeColumnNames( $columnNames ) . " VALUES " . $this->ImplodeValues( $data ) );
	}

	/**
	 * @param $values Array
	 */
	private function ImplodeColumnNames( $values )
	{
		return "(" . implode( ",", $values ). ")";
	}

	/**
	 * @param $values Array
	 */
	private function ImplodeValues( $values )
	{
		return "('" . implode( "','", $values ). "')";
	}

	public function Close( )
	{
		mysqli_close( $this->mysqli );
	}

	/**
	 * @param string $filters MUST BEGIN WITH WHERE
	 *
	 * @return array|null
	 */
	public function GetRandomSong( $filters = "" )
	{
		$song = null;
		$query = mysqli_fetch_array( mysqli_query( $this->mysqli, "SELECT * FROM tblSong " . $filters . " ORDER BY RAND() LIMIT 1" ) );
		if( $query )
		{
			$song = new Song( $query[ "id" ], $query[ "url" ], $query[ "custom_name" ], $query[ "genre" ], $query[ "notes" ], $query[ "name" ], $query[ "image" ] );
		}
		return $song;
	}
}