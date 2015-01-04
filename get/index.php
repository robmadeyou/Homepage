<?php
require_once( '../mysql/Mysql.php' );
require_once( '../song/Song.php' );
require_once( 'Filter/Filter.php' );

	$mysql = new Mysql();
	if( isset( $_POST[ "id" ] ) )
	{
		$song = $mysql->GetSongFromID( intval( $_POST[ "id" ] ) );
	}
	else if( isset( $_POST[ "liked" ] ) )
	{
		$song = $mysql->GetLikedSongs( $mysql->GetLoggedInUser() );
	}
	else
	{
		if( isset( $_POST[ "list" ] ) )
		{
			if( isset( $_POST[ "filter" ] ) )
			{
				$filter = new Filter( $mysql->GetMysqli(), $_POST[ "filter" ] );
				$song = $mysql->GetSongList( $filter->ParseFiltersToMySQL() );
			}
			else
			{
				$song = $mysql->GetSongList();
			}
		}
		else
		{
			if( isset( $_POST[ "filter" ] ) )
			{
				$filter = new Filter( $mysql->GetMysqli(), $_POST[ "filter" ] );
				$song = $mysql->GetRandomSong( $filter->ParseFiltersToMySQL() );
			}
			else
			{
				$song = $mysql->GetRandomSong();
			}
		}
	}
	print json_encode( $song );