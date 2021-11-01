<?php

	/*	Setup error reporting levels. */
	
	//error_reporting(0); 					// no errors, not recommendeed
	//error_reporting(E_ERROR);				// just nasty errors for production 
	error_reporting(E_ALL ^ E_NOTICE); 	// everything but notices for development
	//error_reporting(E_ALL); 				// hardcore for development :)

	/*	Start a session. */
	 
	session_start();

	/*	Setup database object $oDB. */
	
	$oDB = mysqli_init();
	$oDB->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
	$bResult = @$oDB->real_connect(
		$_ENV['DB_HOST'],
		$_ENV['DB_USER'],
		$_ENV['DB_PASS'],
		$_ENV['DB_NAME']
	);
	
	if (!$bResult) {
		die('Couldn\'t connect to database. ' . $oDB->error);
	}

?>