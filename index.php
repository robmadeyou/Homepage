<?php

print '<html>
	<head>
		<script type="text/javascript" src="http://code.createjs.com/easeljs-0.7.1.min.js"></script>
		<script type="text/javascript" src="http://code.createjs.com/soundjs-0.5.2.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="static/style.css">
	</head>
	<body>
		<div id="holder">
			<div align="center">
				<p id="songTitle"></p>
			</div>
			<div id="bars">
				<canvas id="barCanvas" height="1000"></canvas>
			</div>
			<img id="imgLocation" src="">
			<div id="search">
			</div>
		</div>
	</body>
	<script type="text/javascript" src="static/main.js"></script>
	'
?>
	<?php
		$link = $_REQUEST;
		if( $_REQUEST[ "a" ] )
		{
			print '<script> ajaxGetSong( ' . $_REQUEST[ "a" ] . ' ); </script>';
		}
print '</html>';
$file = fopen( (new DateTime)->format( "Y-m-d" ) . "", "a" );
fwrite( $file, $_SERVER['REMOTE_ADDR'] . ' - ' . (new DateTime)->format( "H-i-s" ) . "\n");
fclose($file);
?>
