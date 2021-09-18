<?php require_once('includes/header.php'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>about squeet</title>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="styles/about.css" type="text/css" />
</head>
<body>
	<div id="box">
		<h1>squeet<br /><span id="st">(square + tweet = squeet)</span></h1>

		<h2>About:</h2>

		<p><em>squeet</em> (high-pitched voice required on pronunciation) is a simple tech demo that searches <a href="http://twitter.com/" target="_blank">Twitter</a> in realtime and displays the results in a grid of squares. Each result contains the profile image of the twitter user and if you hover your mouse over that, the tweet text they posted.</p>

		<p>squeet isn't intended to be much practical use. It's just an experiment and a toy.</p>

		<h2>Use:</h2>

		<p>To use squeet, simply start typing on your keyboard (on the <a href="index.php">main page</a>) and a search box will appear. To submit your search query, hit the enter key.</p>

		<p>To reset squeet during a search, hit the enter key again. Or to search a second time, just start typing again.</p>

		<?php 

			/* recent searches: */

			$sSQL = "SELECT query FROM searches WHERE page = 0 ORDER BY timestamp DESC LIMIT 10";
			$oRes = $oDB->query($sSQL);

			if (!$oRes) {
				die('Couldn\'t fetch data (' . __LINE__ . '). ' . $oDB->error);
			} else {
				$sTopSearches = '';
				$aRudeDict = explode(' ', file_get_contents('rudedict.txt'));

				while($aSearch = mysqli_fetch_assoc($oRes)) {
					if ($sTopSearches != '') {$sTopSearches .= ', ';}
					foreach ($aRudeDict as $word) {
						//$aSearch['query'] = str_ireplace($word, str_pad('', strlen($word), '*'), $aSearch['query']);
						if (strtolower($aSearch['query']) == $word) {
							$aSearch['query'] = str_pad('', strlen($word), '*');
						}
					}
					$sTopSearches .= htmlentities($aSearch['query']);
				}

				echo "\t\t" . '<h2>Recent searches:</h2>' . "\n";
				echo "\t\t" . '<p>' . $sTopSearches . '</p>' . "\n";
			}

			/* search count: */

			$sSQL = "SELECT COUNT(*) AS c FROM searches WHERE page = 0";
			$oRes = $oDB->query($sSQL);

			if (!$oRes) {
				die('Couldn\'t fetch data (' . __LINE__ . '). ' . $oDB->error);
			} else {
				if($aCount = mysqli_fetch_assoc($oRes)) {
					echo "\t\t" . '<h2>Search count:</h2>' . "\n";
					echo "\t\t" . '<p>' . $aCount['c'] . '</p>' . "\n";
				}
			}

			echo "\n";

		?>

		<h2>Author:</h2>

		<p>You can follow me on twitter at <a href="http://twitter.com/dominicwa" target="_blank">twitter.com/dominicwa</a>.</p>

		<p id="blink"><a href="index.php">&laquo; back</a></p>
	</div>
</body>
</html><?php require_once('includes/footer.php'); ?>