<?php
	require_once 'config/config.php';
	if(!defined('BASE_URL')) die('No Script acces is allowed');
	require_once 'inc/functions.php';
	require_once 'vendor/idiorm-master/idiorm.php';
	require_once 'vendor/wideimage-full/lib/WideImage.php';
	
	if($database['driver'] == 'sqllite')
	{
		ORM::configure('sqlite:'.$database['sqllite']);
	}
 
	ORM::configure(array(
		'connection_string'=>$database['driver'].':host='.$database['host'].';dbname='.$database['database'],
		'username'=>$database['username'],
		'password'=>$database['password']
	));
?>