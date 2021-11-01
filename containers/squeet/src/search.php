<?php

	require_once('vendor/autoload.php');
	require_once('includes/header.php');
	
	use Abraham\TwitterOAuth\TwitterOAuth;

	$oTwitter = new TwitterOAuth(
		$_ENV['TWITTER_CON_KEY'],
		$_ENV['TWITTER_CON_SEC'],
		$_ENV['TWITTER_ACC_KEY'],
		$_ENV['TWITTER_ACC_SEC']
	);

	$aQuery = array(
		'q' => $_GET['q']
	);

	if (intval($_GET['p']) > 1) {
		$aQuery['max_id'] = $_GET['p'];
	}

	$oResults = $oTwitter->get("search/tweets", $aQuery);
	
	$sSQL = "INSERT INTO searches (query, page, session_id, results, timestamp)
				VALUES ('" . $oDB->real_escape_string($_GET['q']) . "', " . intval($_GET['c']) . ", '" . session_id() . "', " .
				sizeof($oResults->statuses) . ", UNIX_TIMESTAMP())";

	$oRes = $oDB->query($sSQL);

	//echo '<pre>' . print_r($oResults, true) . '</pre>';
	echo json_encode($oResults);

	require_once('includes/footer.php');

?>