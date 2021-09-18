<?php

	/*	Clearup, clean exit. */

	$oDB->close();
	session_write_close(); // end session, ensuring we write session data
	
?>