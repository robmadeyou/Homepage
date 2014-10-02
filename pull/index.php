<?php

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

	$_POST[ "url" ] = str_replace( ";", "", $_POST[ "url"] );
	$output = shell_exec( "cd ..; cd music/; youtube-dl -x --audio-quality 0 -i --add-metadata --write-thumbnail --prefer-avconv -o '%(url)s |!|  %(uploader)s |!| %(title)s |!| %(ext)s' " . $_POST[ "url" ] );
	print $output;
?>
