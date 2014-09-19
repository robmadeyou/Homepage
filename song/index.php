<?php
	if( $_POST[ "a" ] )
	{
		$dir = scandir('../music');
		$songs = [];
		foreach( $dir as $d )
		{
			if( endsWith( $d, ".m4a" ) || endsWith( $d, ".mp3" ) )//TODO make this a cron job to avoid incredibly long load times :(
			{
				$songs[] = $d;
			}
		}
		if( $_POST[ "a" ] == "rand" )
		{
			print $songs[ rand( 0, sizeof( $songs ) ) ];
		}
		else
		{
			print $songs[ intval( $_POST[ "a" ] ) ];
		}
	}
	else if( $_POST[ "songList" ] )
	{
		$dir = scandir('../music');
		$songs = [];
		foreach( $dir as $d )
		{
			if( endsWith( $d, ".m4a" ) || endsWith( $d, ".mp3" ) )//TODO make this a cron job to avoid incredibly long load times :(
			{
				$songs[] = $d;
			}
		}
		print json_encode($songs);
	}
	else
	{
		print "No valid post data";
	}


	function endsWith( $haystack, $needle )
	{
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
?>