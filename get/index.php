<?php
require_once( '../mysql/Mysql.php' );
require_once( '../song/Song.php' );
	$mysql = new Mysql();
	if( isset( $_POST[ "id" ] ) )
	{
		$song = $mysql->GetSongFromID( intval( $_POST[ "id" ] ) );
	}
	else
	{
		if( isset( $_POST[ "filter" ] ) )
		{
			$song = $mysql->GetRandomSong( $_POST[ "filter" ] );
		}
		else
		{
			$song = $mysql->GetRandomSong();
		}
	}
	print json_encode( $song );