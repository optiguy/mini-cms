<?php
	session_start();
	
	define('BASE_URL','http://mini-cms.dev/');
	define('DEBUG',true);

	$database['driver']   = 'mysql';
	$database['sqllite']  = './example.db';
	$database['host']     = '127.0.0.1';
	$database['database'] = 'dbminicms';
	$database['username'] = 'root';
	$database['password'] = 'root';
	
	if(DEBUG)
	{
		ini_set('display_errors', true);
  		error_reporting(-1);
	} else {
		ini_set('display_errors', false);
	}
?>