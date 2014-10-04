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
		<div id="overlay">
			<div id="outerPullForm">
				<a href="#" id="close"><img src="static/images/close.png" width="32" height="32" ></a>
				<div id="innerPullForm">
					<div id="left">
						Insert URL<br>
						<input type="text" id="pullUrl" placeholder="This can be Youtube/Soundcloud playlist; single video or even an artist!"><br><br>
						Custom Name?<i>(Leave blank if none)</i><br>
						<input type="text" id="pullPrefix" placeholder="Custom name will be used to identify the song"><br><br>
						Genres/Tags<br>
						<textarea id="pullTags" placeholder="Enter the tags you would like to add to songs from the URL; you will be able to search by them later on!"></textarea>
					</div>
					<div id="right">
						Include Notes?<i>(Little self promotion is ok here :))</i><br>
						<textarea id="pullNotes" placeholder="Yo yo yo; notes are cool!"></textarea>
					</div>
				</div>
				<button type="button" id="finalizePull">Upload!</button>
			</div>
		</div>
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
					<button type="submit" id="pull">Click me to download songs to the server</button>
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
		if( isset( $_REQUEST[ "a" ] ) )
		{
			print '<script> ajaxGetSong( ' . $_REQUEST[ "a" ] . ' ); </script>';
		}
		print '</html>';
		$file = fopen( (new DateTime)->format( "Y-m-d" ) . "", "a" );
		fwrite( $file, $_SERVER[ 'REMOTE_ADDR' ] . ' - ' . ( new DateTime )->format( "H-i-s" ) . "\n");
		fclose( $file );
?>
