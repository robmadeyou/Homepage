<?php
	if( $_POST && $_POST[ "url" ] )
	{
		$_POST[ "url" ] = str_replace( ";", "", $_POST[ "url"] );
		$output = shell_exec( "cd ..; cd music/; youtube-dl -x --audio-quality 0 -i --add-metadata --write-thumbnail --prefer-avconv -o '%(uploader)s - %(title)s.%(ext)s' " . $_POST[ "url" ] );
		print nl2br( $output );
	}
?>
