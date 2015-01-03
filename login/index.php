<?php
/**
 * How is it going yo
 */
require_once( '../mysql/Mysql.php' );
if( $_REQUEST[ "user" ] )
{
	$mysql = new Mysql();
	$mysql->LogIn( "Something" );
}

?>
<html>
	<head>

	</head>
	<body>
		<p>Hello!</p>
	</body>
</html>
