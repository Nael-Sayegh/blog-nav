<?php
$isbot = false;
if(!empty($_SERVER['HTTP_USER_AGENT'])) {
	$uabots = array('DotBot','bingbot','Googlebot','Ahrefsbot','Twitterbot','applebot','PaperLiBot','SemrushBot','SurdotlyBot','SocialRankIOBot','ubermetrics','facebookexternalhit','LivelapBot','TrendsmapResolver','bot@linkfluence.com','YandexBot','MJ12bot');
	foreach($uabots as &$uabot) {
		if(strpos($_SERVER['HTTP_USER_AGENT'], $uabot) !== false) {
			$isbot = true;
			break;
		}
	}
	$ipDebut = ip2long('94.130.0.0');
	$ipFin = ip2long('94.130.255.255');
	$ipAbloquer = ip2long($_SERVER['REMOTE_ADDR']);
	if (($ipAbloquer >= $ipDebut) && ($ipAbloquer <= $ipFin)) {
		$isbot = true;
	}
	$ipDebut1 = ip2long('17.0.0.0');
	$ipFin1 = ip2long('17.255.255.255');
	$ipAbloquer1 = ip2long($_SERVER['REMOTE_ADDR']);
	if (($ipAbloquer1 >= $ipDebut1) && ($ipAbloquer1 <= $ipFin1)) {
		$isbot = true;
	}
	$ipDebut2 = ip2long('34.64.0.0');
	$ipFin2 = ip2long('34.127.255.255');
	$ipAbloquer2 = ip2long($_SERVER['REMOTE_ADDR']);
	if (($ipAbloquer2 >= $ipDebut2) && ($ipAbloquer2 <= $ipFin2)) {
		$isbot = true;
	}
}
?>