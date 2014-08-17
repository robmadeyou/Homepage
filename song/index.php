<?php
	if( $_POST[ "a" ] )
	{
		$dir = scandir('../music');
		if( $_POST[ "a" ] == "rand" )
		{
			print $dir[ rand( 1, sizeof( $dir ) ) ];
		}
		else
		{
			print $dir[ intval( $_POST[ "a" ] + 1 ) ];
		}
	}
	else
	{
		print "No valid post data";
	}
?>