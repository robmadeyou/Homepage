<?php
require_once( '..\mysql\Mysql.php' );
	$errors = "";
	$customPrefix = "";
	$tags = "";
	$notes = "";
	$url = "";
	if( isset( $_POST ) )
	{
		if( !isset( $_POST[ "url" ] ) )
		{
			$errors .= "No URL\n";
		}
		else
		{
			$url = $_POST[ "url" ];
		}

		if( !isset( $_POST[ "tags" ] ) )
		{
			$errors .= "No TAGS";
		}
		else
		{
			$tags = $_POST[ "tags" ];
		}

		if( isset( $_POST[ "notes" ] ) )
		{
			$notes = $_POST[ "notes" ];
		}

		if( isset( $_POST[ "prefix" ] ) )
		{
			$customPrefix = $_POST[ "prefix" ];
		}
	}

	if( $errors )
	{
		print nl2br( $errors );
		return;
	}

	$songList = [];
	$mysql = new Mysql();

	$url = str_replace( ";", "", $url );
	$output = shell_exec( "cd ..; cd tmp/; youtube-dl -x --audio-quality 0 -i --add-metadata --write-thumbnail --prefer-avconv -o '%(id)s |!| %(uploader)s |!| %(title)s.%(ext)s' " . $url );

	$directory = scandir( "../tmp/" );
	foreach( $directory as $item )
	{
		$mysql->InsertSong( ["name"], [ mysqli_real_escape_string( $mysql->GetMysqli(), $item ) ] );
		print nl2br( mysqli_error( $mysql->GetMysqli() ) );
	}
	$mysql->Close();
?>
