<?php
require_once( '../mysql/Mysql.php' );
require_once( '../song/Song.php' );
	?>
	<html>
		<head>
			<script type="text/javascript" src="http://code.createjs.com/easeljs-0.7.1.min.js"></script>
			<script type="text/javascript" src="http://code.createjs.com/soundjs-0.5.2.min.js"></script>
			<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
			<link rel="stylesheet" href="../static/style.css">
			<title id="title">Welcome</title>
			<link rel="shortcut icon" href="http://www.localfoodmap.net/images/icons/banana.ico"/>
		</head>
		<body>
			<h1 id="title">
				Now you can judge a song by it's artwork!
			</h1>
			<?php
				$mysql = new Mysql();
				foreach( $mysql->GetSongList() as $song )
				{
					print '<a href="../?a='. $song[ "id" ] .'"><img class="floatingImages" src="../music/' . $song[ "image" ] . '" /></a>';
				}
			?>
		</body>
	</html>
	<?php
?>