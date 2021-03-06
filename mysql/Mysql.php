<?php
chdir( __DIR__ );
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

	public function GetSongList( $filter = "" )
	{
		$song = [];
		$mysql = mysqli_query( $this->mysqli, "SELECT * FROM tblSong " . $filter . " ORDER BY RAND() LIMIT 50" );
		while ( $query = mysqli_fetch_array( $mysql ) )
		{
			$song[] = $query;
		}
		return $song;
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

	/**
	 * @param $id
	 *
	 * @return null|Song
	 */
	public function GetSongFromID( $id )
	{
		$song = null;
		$query = mysqli_fetch_array( mysqli_query( $this->mysqli, "SELECT * FROM tblSong WHERE id = " . mysqli_real_escape_string( $this->mysqli, $id ) ));
		if( $query )
		{
			$song = new Song( $query[ "id" ], $query[ "url" ], $query[ "custom_name" ], $query[ "genre" ], $query[ "notes" ], $query[ "name" ], $query[ "image" ] );
		}
		return $song;
	}

	public function GetLikedSongs( $userID )
	{
		$song = [];
		$query = mysqli_fetch_array( mysqli_query( $this->mysqli, "SELECT * FROM tblSong JOIN tblPreference ON tblSong.id = tblPreference.SongID WHERE tblPreference.UserID = $userID AND tblPreference.Preference = 1" ) );
		while ( $query = mysqli_fetch_array( $mysql ) )
		{
			$song[] = $query;
		}
		return $song;
	}

	public function LogIn( $id )
	{
		$id = mysqli_real_escape_string( $this->mysqli, $id );
		$query = mysqli_fetch_array( mysqli_query( $this->mysqli, "SELECT * FROM tblUser WHERE UserName = '$id' LIMIT 1" ) );
		if( $query )
		{
			setcookie( "user", $id, time()+60*60*24*30 );
			print "Success";
			return true;
		}
		setcookie( "failed", "true", time()+60*60*24*30 );
		print "Fail";
		return false;
	}

	public function DoesUserExist( $id )
	{
		$id = mysqli_real_escape_string( $this->mysqli, $id );
		$query = mysqli_fetch_array( mysqli_query( $this->mysqli, "SELECT * FROM tblUser WHERE UserName = '$id' LIMIT 1" ) );
		if( $query )
		{
			return true;
		}
		return false;
	}

	public function GetLoggedInUser()
	{
		if( isset( $_COOKIE ) && isset( $_COOKIE[ "user" ] ) )
		{
			$query = mysqli_fetch_array( mysqli_query( $this->mysqli, "SELECT * FROM tblUser WHERE UserName = '{$_COOKIE[ "user" ]}' LIMIT 1" ) );
			if( $query )
			{
				return $query[ "UserID" ];
			}
		}
		return false;
	}

	public function PerformLikeOrDislike( $songID, $userID, $preference )
	{
		$query = mysqli_fetch_array( mysqli_query( $this->mysqli, "SELECT * FROM tblPreference WHERE UserID = $userID AND SongID = $songID" ) );
		if( $query )
		{
			$query = mysqli_fetch_array( mysqli_query( $this->mysqli, "UPDATE tblPreference SET Preference = '$preference' WHERE UserID = $userID AND SongID = $songID" ) );
		}
		else
		{
			$query = mysqli_fetch_array( mysqli_query( $this->mysqli, "INSERT INTO tblPreference ( UserID, SongID, Preference ) VALUES ( '$userID', '$songID', '$preference' )" ) );
		}
	}
}