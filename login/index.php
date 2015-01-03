<?php
/**
 * How is it going yo
 */
if( $_REQUEST[ "user" ] )
{
	$mysql = new Mysql();
	$mysql->LogIn( "Something" );
}