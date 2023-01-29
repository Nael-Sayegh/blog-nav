<?php
$logged = false;

function check_login($session, $connectid) {
	global $bdd, $login, $nolog, $settings, $nom;
	require_once($_SERVER['DOCUMENT_ROOT'].'/include/dbconnect.php');
	/*$req = $bdd->prepare('
		SELECT `accounts`.*, `team`.`id` AS `team_id` 
		FROM `accounts` 
		LEFT JOIN `team` ON `team`.`account_id` = `accounts`.`id` 
		WHERE `accounts`.`connectid`=? AND `accounts`.`expire`>? LIMIT 1');*/
	$req = $bdd->prepare('
		SELECT `sessions`.`id` AS `session_id`, `sessions`.`session`, `sessions`.`connectid`, `sessions`.`expire`, `sessions`.`token`, `accounts`.`id`, `accounts`.`id64`, `accounts`.`email`, `accounts`.`username`, `accounts`.`signup_date`, `accounts`.`password`, `accounts`.`settings`, `accounts`.`confirmed`, `accounts`.`subscribed_comments`, `accounts`.`rank`, `team`.`id` AS `team_id`, `accounts`.`forum_id` AS `forum_id` 
		FROM `sessions` 
		LEFT JOIN `accounts` ON `accounts`.`id` = `sessions`.`account` 
		LEFT JOIN `team` ON `team`.`account_id` = `sessions`.`account` 
		WHERE `sessions`.`connectid`=? AND `sessions`.`expire`>? LIMIT 1');
	$req->execute(array($connectid, time()));
	if($login = $req->fetch()) {
		if(!isset($login['id']) or !$login['id']) {
			unset($login);
			return false;
		}
		
		if(password_verify($session, $login['session'])) {
			if(isset($nolog) and $nolog) {
				$req->closeCursor();
				header('Location: /');
				exit();
			}
			$req = $bdd->prepare('UPDATE `sessions` SET `expire`=? WHERE `id`=?');
			$req->execute(array(time()+31557600, $login['session_id']));
			# check settings cookies
			$settings = json_decode($login['settings'], true);
			$sets = array('menu', 'fontsize', 'audio', 'date', 'infosdef');
			foreach($sets as &$setting) {
				if(isset($settings[$setting]) and (!isset($_COOKIE[$setting]) or (isset($_COOKIE[$setting]) and $_COOKIE[$setting]!=$settings[$setting])))
					setcookie($setting, $settings[$setting], time()+31557600, null, null, false, true);
			}
			unset($setting);
			unset($sets);
			
			$nom = '';
			if($login['rank'] == 'a') {
				$req = $bdd->prepare('SELECT `short_name` FROM `team` WHERE `account_id`=? LIMIT 1');
				$req->execute(array($login['id']));
				if($data = $req->fetch())
					$nom = $data['short_name'];
			}
			$req->closeCursor();
			return true;
		}
		else
			unset($login);
	}
	$req->closeCursor();
	return false;
}

if(isset($_COOKIE['session']) and isset($_COOKIE['connectid'])) {
	$logged = check_login($_COOKIE['session'], $_COOKIE['connectid']);
}

if(!$logged and isset($_GET['ses']) and isset($_GET['cid'])) {
	if($logged = check_login($_GET['ses'], $_GET['cid'])) {
		$expire = time()+31557600;
		setcookie('session', $_GET['ses'], $expire, '/', NULL, false, true);
		setcookie('connectid', $_GET['cid'], $expire, '/', NULL, false, true);
	}
}

if(!$logged and isset($logonly) and $logonly) {
	http_response_code(403);
	header('Location: /login.php?logonly');
	exit();
}
if($logged and $login['rank'] == 'b') {
	http_response_code(403);
	require_once($_SERVER['DOCUMENT_ROOT'].'/403/403B.php');
	exit();
}
if(isset($adminonly) && $adminonly && $login['rank'] != 'a') {
	http_response_code(403);
	require_once($_SERVER['DOCUMENT_ROOT'].'/403/403.html');
	exit();
}
if($logged and $login['rank'] == 'a') {
	$req = $bdd->prepare('SELECT `works` FROM `team` WHERE `account_id`=? LIMIT 1');
	$req->execute(array($login['id']));
	if($data = $req->fetch()) {
		$worksnum2=$data['works'];
	}
}
if(isset($justpa) && $justpa && $worksnum2 == '0') {
	header('Location: https://www.nvda-fr.org/admin/');
	exit();
}
?>
