<?php

	openlog('squeet', LOG_PID | LOG_PERROR, LOG_LOCAL0);

	syslog(LOG_INFO, 'Starting Squeet...');

	$oDB = mysqli_init();

	do {
		syslog(LOG_INFO, 'Attempting to connect to database...');
		$oDB->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
		$bResult = @$oDB->real_connect(
			$_ENV['DB_HOST'],
			$_ENV['DB_USER'],
			$_ENV['DB_PASS']
		);
		if ($bResult === FALSE) {
			syslog(LOG_CRIT, 'Failed to connect to database ['. $oDB->connect_error . '].');
			syslog(LOG_INFO, 'Trying again in 10 seconds...');
			sleep(10);
		} else syslog(LOG_INFO, 'Connected to database.');
	} while ($bResult === FALSE);

	if (!$oDB->select_db($_ENV['DB_NAME']) || $_ENV['REBUILD'] == 1) {
		syslog(LOG_INFO, 'Database not detected (or rebuild = 1). Creating...');
		$bResult = $oDB->query('CREATE DATABASE ' . $_ENV['DB_NAME']);
		$bResult = $oDB->select_db($_ENV['DB_NAME']);
		$bResult = $oDB->query('
			CREATE TABLE `searches` (
				`id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`query` text NOT NULL,
				`page` int NOT NULL,
				`session_id` text NOT NULL,
				`results` int NOT NULL,
				`timestamp` int NOT NULL
			);
		');
		if ($bResult === FALSE) {
			syslog(LOG_CRIT, 'Failed to create database ['. $oDB->error . '].');
			while (1 == 1) {
				syslog(LOG_CRIT, 'Critical error... aborted.'); 
				sleep(10);
			}
		} else syslog(LOG_INFO, 'Database created.');
	} else syslog(LOG_INFO, 'Database already exists.');

	$oDB->close();

	syslog(LOG_INFO, 'Finished starting Squeet.');

	closelog();
	
?>