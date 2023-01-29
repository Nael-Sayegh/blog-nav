<?php
$logonly = true;
require_once('include/log.php');
require_once('include/consts.php');
require_once('include/flarum.php');

if(isset($_GET['token']) and $_GET['token'] == $login['token']) {
	if(isset($login['forum_id']) and $login['forum_id']) {
		if(auth_forum($login['id'])) {
			header('Location: https://forum.progaccess.net/');
			exit();
		} else {
			echo 'Error: Cannot authenticate (please retry a bit later)';
		}
	} else {
		echo 'Error: no linked forum account';
	}
} else {
	echo 'Error: Bad token';
}
?>