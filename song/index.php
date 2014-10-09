<?php
	$songName = "";
	$postData = new stdClass();
	$postData->Song = "";
	$postData->ID = 0;
	if( isset( $_POST[ "a" ] ) )
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
			$id = rand( 0, sizeof( $songs ) );
			$songName = $songs[ $id ];
			$postData->Song = $songName;
			$postData->ID = $id;
		}
		else
		{
			$songName = $songs[ intval( $_POST[ "a" ] ) ];
			$postData->Song = $songName;
			$postData->ID = $_POST[ "a" ];
		}
		print json_encode( $postData );
	}
	else if( isset( $_POST[ "songList" ] ) )
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
	else if( isset( $_POST[ "search" ] ) )
	{
		$query = $_POST[ "search" ];

		$dir = scandir('../music');
                $songs = [];
		$filtered = [];
		foreach( $dir as $d )
                {
                        if( endsWith( $d, ".m4a" ) || endsWith( $d, ".mp3" ) )//TODO make this a cron job to avoid incredibly long load times :(
                        {
                                $songs[] = $d;
                        }
                }

                for( $i = 0; $i < count( $songs ); $i++ )
                {
                        if( stristr( $songs[ $i ], $query ))
			{
				$song = new stdClass();
				$song->Song = $songs[ $i ];
				$song->ID = $i;
                                $filtered[] = $song;
				
                        }
                }
		print json_encode( $filtered );
		//print json_encode( $songs );

	}
	else
	{
		print "No valid post data";
	}


	function endsWith( $haystack, $needle )
	{
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
	$me = "";
	if( isset( $_GET[ "u" ] ) )
	{
		$me = "me";
	}
?>
