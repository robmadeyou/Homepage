<?php
require_once( '../mysql/Mysql.php' );
require_once( '../song/Song.php' );
	$mysql = new Mysql();
	$song = $mysql->GetRandomSong();
	print json_encode( $song );