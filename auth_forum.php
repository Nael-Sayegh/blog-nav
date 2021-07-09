<?php
$logonly = true;
require 'inclus/log.php';
require_once 'inclus/consts.php';
require_once 'inclus/flarum.php';

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
