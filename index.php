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
			<p id="songTitle"></p>
			<div id="bars" style="height: 400px;">
				<canvas id="barCanvas" height="520"></canvas>
			</div>
			<img id="imgLocation" src="">
		</div>
	</body>
	<script type="text/javascript" src="static/main.js"></script>
	'
?>
	<?php
		$link = $_REQUEST;
		if( $_REQUEST )
		{
			print '<script> ajaxGetSong( ); </script>';
		}
print '</html>';
?>
