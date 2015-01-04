<?php
	require_once( '../mysql/Mysql.php' );
	if( isset( $_REQUEST[ "songID" ] ) && isset( $_REQUEST[ "preference" ] ) )
	{
		$mysql = new Mysql();
		$mysql->PerformLikeOrDislike( $_REQUEST[ "songID" ], $mysql->GetLoggedInUser(), $_REQUEST[ "preference" ] );
		print "Done";
	}
	else
	{
		print "Sorry, missing parameters!";
	}