<?php
	require_once 'config/config.php'; //Load startup config
	if(!defined('BASE_URL')) die('No Script access is allowed');
	require_once 'config/database.php';//Load database config
	
	//Load internal scripts
	require_once 'inc/functions.php';
	
	//Load vendor packages
	require_once 'vendor/idiorm-master/idiorm.php';
	require_once 'vendor/wideimage-full/lib/WideImage.php';
	require_once 'vendor/GUMP-master/gump.class.php';
	
	//Set database file for sqllite
	if($database['driver'] == 'sqllite')
		ORM::configure('sqlite:'.$database['sqllite']);
 
 	//Set database conntection
	ORM::configure(array(
		'connection_string'=>$database['driver'].':host='.$database['host'].';dbname='.$database['database'],
		'username'=>$database['username'],
		'password'=>$database['password']
	));
?>