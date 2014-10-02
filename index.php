<?php

print '<html>
	<head>
		<script type="text/javascript" src="http://code.createjs.com/easeljs-0.7.1.min.js"></script>
		<script type="text/javascript" src="http://code.createjs.com/soundjs-0.5.2.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<link rel="stylesheet" href="static/style.css">
		<title id="title">Welcome</title>
		<link rel="shortcut icon" href="http://www.localfoodmap.net/images/icons/banana.ico"/>
	</head>
	<body>
		<div id="holder">
			<div id="titleBar">
				<p id="songTitle"></p> <input id="volume" type="range" min="0" max="100" onchange="changeVolume( this.value )">
			</div>
			<div id="bars">
				<canvas id="barCanvas" height="1000"></canvas>
			</div>
			<div id="interactive">	
				<div id="imgLocation" class="box" >
					<img id="img" src="">
				</div>
				<div id="searchLocation" class="box" >
					<input type="text" id="searchIn" placeholder="search" >
					<div id="search">
					</div>
				</div>
				<div id="downloadLocation" class="box">
					<input type="text" id="pull" placeholder="url to pull; press enter!">
					<div id="pullResults">
					</div>
				</div>
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
