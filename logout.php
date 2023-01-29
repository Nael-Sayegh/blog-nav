<?php
require_once('include/dbconnect.php');
$logonly = true;
require_once('include/log.php');

if(isset($_GET['token']) and $_GET['token'] == $login['token']) {
	setcookie('session', '', 0, '/', NULL, false, true);
	setcookie('connectid', '', 0, '/', NULL, false, true);
	$req = $bdd->prepare('UPDATE `sessions` SET `expire`=? WHERE `id`=? LIMIT 1');
	$req->execute(array(time()-1, $login['session_id']));
	header('Location: /');
}
else
	header('Location: /');
exit();
?>