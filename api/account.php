<?php
require_once 'api_inc.php';

$rep = array('api_version'=>$api_version);

# Login
if(isset($_POST['login_name']) and isset($_POST['login_psw'])) {
	$req = $bdd->prepare('SELECT * FROM `accounts` WHERE `username`=? LIMIT 1');
	$req->execute(array($_POST['username']));
	
	if($data = $req->fetch()) {
		if(password_verify($_POST['psw'], $data['password'])) {
			$session = hash('sha512', time().random_int(100000,999999).sha1(random_int(100000,999999).$_POST['psw']));
			$connectid = hash('sha256', time().random_int(100000,999999).sha1(random_int(100000,999999).$data['id']));
			$token = base_convert(md5(strval(random_int(100000,999999)).$connectid), 16, 36);
			$created = time();
			$expire = $created+31557600;
			$req2 = $bdd->prepare('INSERT INTO `sessions` (`account`, `session`, `connectid`, `expire`, `created`, `token`) VALUES (?,?,?,?,?)');
			$req2->execute(array($data['id'], password_hash($session,PASSWORD_DEFAULT), $connectid, $expire, $created, $token));
			$rep['login'] = array('session'=>$session, 'connectid'=>$connectid, 'expire'=>$expire);
		}
	}
}

# Check login
$logged = false;
if(isset($_POST['session']) and isset($_POST['connectid'])) {
	$req = $bdd->prepare('
		SELECT `sessions`.`id` AS `session_id`, `sessions`.`session`, `sessions`.`connectid`, `sessions`.`expire`, `sessions`.`token`, `accounts`.`id`, `accounts`.`id64`, `accounts`.`email`, `accounts`.`username`, `accounts`.`signup_date`, `accounts`.`password`, `accounts`.`settings`, `accounts`.`confirmed`, `accounts`.`subscribed_comments`, `accounts`.`rank`, `team`.`id` AS `team_id` 
		FROM `sessions` 
		LEFT JOIN `accounts` ON `accounts`.`id` = `sessions`.`account` 
		LEFT JOIN `team` ON `team`.`account_id` = `sessions`.`account` 
		WHERE `sessions`.`connectid`=? AND `sessions`.`expire`>? LIMIT 1');
	$req->execute(array($_POST['connectid'], time()));
	if($login = $req->fetch()) {
		if(password_verify($_POST['session'], $login['session'])) {
			$logged = true;
			$req2 = $bdd->prepare('UPDATE `sessions` SET `expire`=? WHERE `id`=?');
			$req2->execute(array(time()+31557600, $login['session_id']));
			$settings = json_decode($login['settings'], true);
			
			$nom = '';
			if($settings['rank'] == 'a') {
				$req = $bdd->prepare('SELECT `short_name` FROM `team` WHERE `account_id`=? LIMIT 1');
				$req->execute(array($login['id']));
				if($data = $req->fetch())
					$nom = $data['short_name'];
			}
			
			$rep['login'] = array('connectid'=>$login['connectid'], 'expire'=>$login['expire'], 'token'=>$login['token']);
		}
	}
}

if($logged) {
	if(isset($_GET['myinfo'])) {
		$rep['myinfo'] = array();
		$rep['myinfo']['id'] = $login['id'];
		$rep['myinfo']['name'] = $login['username'];
		$rep['myinfo']['mail'] = $login['email'];
		$rep['myinfo']['settings'] = $settings;
		$rep['myinfo']['rank'] = $login['rank'];
		$rep['myinfo']['signup_date'] = $login['signup_date'];
		$rep['myinfo']['subscribed_comments'] = $login['subscribed_comments'];
		$rep['myinfo']['team_name'] = $nom;
	}
	if(isset($_POST['token']) and $_POST['token'] == $login['token']) {
		if(isset($_GET['subscribe_comments'])) {
			$req = $bdd->prepare('SELECT `id` FROM `softwares` WHERE `id`=? LIMIT 1');
			$req->execute(array($_GET['subscribe_comments']));
			if($req->fetch()) {
				$req = $bdd->prepare('SELECT `id` FROM `subscriptions_comments` WHERE `account`=? AND `article`=? LIMIT 1');
				$req->execute(array($login['id'], $_GET['subscribe_comments']));
				if(!$req->fetch()) {
					$req = $bdd->prepare('INSERT INTO `subscriptions_comments` (`account`,`article`) VALUES (?,?)');
					$req->execute(array($login['id'], $_GET['subscribe_comments']));
					$rep['subscribed'] = array('comments'=>array($_GET['subscribe_comments']));
				}
			}
		}
		if(isset($_GET['unsubscribe_comments'])) {
			$req = $bdd->prepare('DELETE FROM `subscriptions_comments` WHERE `account`=? AND `article`=? LIMIT 1');
			$req->execute(array($login['id'], $_GET['unsubscribe_comments']));
			$rep['unsubscribed'] = array('comments'=>array($_GET['unsubscribe_comments']));
		}
		if(isset($_GET['read_all_notifs'])) {
			$req = $bdd->prepare('UPDATE `notifs` SET `unread`=0 WHERE `account`=? AND `unread`=1');
			$req->execute(array($login['id']));
			$rep['read_all_notifs'] = true;
		}
		if(isset($_GET['read_notif'])) {
			$req = $bdd->prepare('UPDATE `notifs` SET `unread`=0 WHERE `id`=? AND `account`=?');
			$req->execute(array($_GET['read_notif'], $login['id']));
			$rep['read_notif'] = $_GET['read_notif'];
		}
		if(isset($_GET['unread_notif'])) {
			$req = $bdd->prepare('UPDATE `notifs` SET `unread`=1 WHERE `id`=? AND `account`=?');
			$req->execute(array($_GET['unread_notif'], $login['id']));
			$rep['unread_notif'] = $_GET['unread_notif'];
		}
	}
}

echo json_encode($rep);
?>
