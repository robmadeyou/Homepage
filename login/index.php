<?php
/**
 * How is it going yo
 */
require_once( '../mysql/Mysql.php' );
if( $_REQUEST[ "user" ] )
{
	$mysql = new Mysql();
	$mysql->LogIn( $_REQUEST[ "user" ] );
}
?>
