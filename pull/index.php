<?php
require_once( '../mysql/Mysql.php' );
	$errors = "";
	$customPrefix = "";
	$tags = "";
	$notes = "";
	$url = "";
	$usePrefix = false;
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

		if( isset( $_POST[ "usePrefix" ] ) )
		{
			$usePrefix = $_POST[ "usePrefix" ];
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
	$output = shell_exec( "youtube-dl -x --audio-quality 0 -i --add-metadata --write-thumbnail --prefer-avconv -o '../tmp/%(id)s !i! %(uploader)s !i! %(title)s.%(ext)s' " . $url );
    print $output;
	$directory = scandir( "../tmp/" );
	foreach( $directory as $item )
	{
		if( $item != '.' && $item != '..' )
		{
			$info = pathinfo( $item );
			if( $info[ "extension" ] == "mp3" || $info[ "extension" ] == "m4a" )
			{
				$songExploded = explode( " !i! ", $item );
                $songName = $songExploded[ 2 ];
                $image = str_replace( $info[ "extension" ], "jpg", $songName );
				$songUrl = $songExploded[ 0 ];
				$uploader = $songExploded[ 1 ];
				if( $customPrefix != "" )
				{
					if( $usePrefix )
					{
						if( strpos( $songName, $customPrefix ) ===  false )
						{
							$songName = $customPrefix . " - " . $songName;
							print "name made";
						}
					}
				}

				$mysql->InsertSong(
					[
						"url",
						"name",
						"genre",
						"custom_name",
						"ip",
						"uploader",
                        "notes",
                        "image"
					],
					[
						mysqli_real_escape_string( $mysql->GetMysqli(), $url ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $songName ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $tags ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $customPrefix ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $_SERVER['REMOTE_ADDR'] ),
						mysqli_real_escape_string( $mysql->GetMysqli(), $uploader ),
                        mysqli_real_escape_string( $mysql->GetMysqli(), $notes ),
                        mysqli_real_escape_string( $mysql->GetMysqli(), $image )
					]);
                rename( '../tmp/' . $item, '../music/' . $songName );
                rename( '../tmp/' . str_replace( $info[ "extension" ], "jpg", $item ), '../music/' . $image );
			}
		}
	}
	$mysql->Close();
?>
