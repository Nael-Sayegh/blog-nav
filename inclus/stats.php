<?php
require_once('dbconnect.php');

$domain = '';
if(!isset($stats_page)) $stats_page = '';
if(strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'dev.progaccess33.net/')) $domain = 'pa33_dev';
else if(strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'progaccess33.net/')) $domain = 'pa33';
else if(strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'dev.pa33netki3kw4kjk.onion/')) $domain = 'pa33_onion_dev';
else if(strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], 'pa33netki3kw4kjk.onion/')) $domain = 'pa33_onion';

require_once('isbot.php');
if(!(isset($logged) and $logged and $settings['rank'] == 'a') and !$isbot) {
	$req = $bdd->prepare('SELECT id FROM `count_visits` WHERE `date`=CURDATE() AND `page`=? AND `domain`=? LIMIT 1');
	$req->execute(array($stats_page, $domain));
	if($data = $req->fetch()) {
		$req = $bdd->prepare('UPDATE `count_visits` SET `visits`=`visits`+1 WHERE `date`=CURDATE() AND `page`=? AND `domain`=? LIMIT 1');
		$req->execute(array($stats_page, $domain));
	}
	else {
		$req = $bdd->prepare('INSERT INTO `count_visits`(`date`,`visits`,`page`,`domain`) VALUES(CURDATE(),1,?,?)');
		$req->execute(array($stats_page, $domain));
	}

	$req = $bdd->prepare('SELECT id FROM `count_visitors` WHERE `addr`=? AND `domain`=? LIMIT 1');
	$req->execute(array(sha1($_SERVER['REMOTE_ADDR']), $domain));
	if($data = $req->fetch()) {
		$req = $bdd->prepare('UPDATE `count_visitors` SET `lastvisit`=? WHERE `addr`=? AND `domain`=? LIMIT 1');
		$req->execute(array(time(), sha1($_SERVER['REMOTE_ADDR']), $domain));
	}
	else {
		$req = $bdd->prepare('INSERT INTO `count_visitors`(`addr`,`lastvisit`,`domain`) VALUES(?,?,?)');
		$req->execute(array(sha1($_SERVER['REMOTE_ADDR']), time(), $domain));
	}
}

if(!isset($stats_no)) {
	$date = date('Y-m-d');
	$xpage = 0;
	$xpagetoday = 0;
	$xvisits = 0;
	$xvisitstoday = 0;
	$req = $bdd->prepare('SELECT `page`,`date`,`visits` FROM `count_visits` WHERE `domain`=? AND `date`>?');
	$req->execute(array($domain, time()-31557600));
	while($data = $req->fetch()) {
		if($data['page'] == $stats_page) {
			$xpage += $data['visits'];
			if($data['date'] == $date)
				$xpagetoday += $data['visits'];
		}
		$xvisits += $data['visits'];
		if($data['date'] == $date)
			$xvisitstoday += $data['visits'];
	}
	
	$xvisitors = 0;
	$xconn = 0;
	$xtoday = 0;
	$req = $bdd->prepare('SELECT `lastvisit` FROM `count_visitors` WHERE `domain`=?');
	$req->execute(array($domain));
	while($data = $req->fetch()) {
		if($data['lastvisit'] > strtotime('midnight'))
			$xtoday ++;
		if($data['lastvisit'] > time()-600)
			$xconn ++;
		$xvisitors ++;
	}
	
	echo '<ul id="compteur">
	<li>Page chargée '.$xpage.' fois depuis un an dont '.$xpagetoday.' ce jour</li>
	<li>'.$xvisits.' pages chargées depuis un an dont '.$xvisitstoday.' aujourd\'hui</li>
	<li>'.$xvisitors.' visiteurs depuis une semaine dont '.$xtoday.' aujourd\'hui</li>
	<li>'.$xconn.' connectés.</li></ul>';
}

$req->closeCursor();
?>