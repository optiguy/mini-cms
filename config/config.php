<?php
	session_start(); //Start access to seesions
	define('BASE_URL','http://mini-cms.dev/'); //Define our base url
	define('DEBUG',true); //Set error's if set to yes
	define('PER_PAGE',5);
	define('NUM_RATING',10);
	define('ADMIN_MAIL','info@minicms.dk');
	
	//Set website to danish zone
	date_default_timezone_set('Europe/Copenhagen');
	setlocale('LC_ALL','Europe/Copenhagen');

	if(DEBUG)
	{
		ini_set('display_errors', 1);
		ini_set('log_errors', 0);
		error_reporting(-1);
	} else {
		ini_set('display_errors', 0);
		ini_set('log_errors', 1);
		error_reporting(0);
	}
?>