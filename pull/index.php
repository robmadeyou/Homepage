<?php
require_once( '../mysql/Mysql.php' );
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
	$output = shell_exec( "cd ../tmp/; youtube-dl -x --audio-quality 0 -i --add-metadata --write-thumbnail --prefer-avconv -o '%(id)s |!| %(uploader)s |!| %(title)s.%(ext)s' " . $url );

	$directory = scandir( "../tmp/" );
	foreach( $directory as $item )
	{
		if( $item != '.' && $item != '..' )
		{
			$info = pathinfo( $item );
			if( $info[ "extension" ] == "mp3" || $info[ "extension" ] == "m4a" )
			{
				$songExploded = explode( " !|! ", $item );
				$songUrl = $songExploded[0];
				$uploader = $songExploded[1];

				$mysql->InsertSong(
					[
						"url",
						"name",
						"genre",
						"custom_name",
						"ip",
						"uploader",
					],
					[
						mysqli_real_escape_string( $mysql->GetMysqli(), $url ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $item ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $tags ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $customPrefix ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $_SERVER['REMOTE_ADDR'] ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $uploader ),
					]);
			}
		}
	}
	$mysql->Close();
?>
