<?php $logonly = true;
$adminonly = true;
$justpa = true;

$titlePAdm='Publier sur les réseaux sociaux';
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php');
$log = '';

if(isset($_GET['form']) and isset($_POST['pf']) and isset($_POST['msg'])) {
	$plainmsg = preg_replace('/(?!\\${2})\\${2}/', '', $_POST['msg']);
	
	if(in_array('fb', $_POST['pf'])) {
		print('facebook: '.$plainmsg);
	}
	if(in_array('tw', $_POST['pf'])) {
		print('twitter: '.$_POST['msg']);
	}
	if(in_array('dd', $_POST['pf'])) {
		print('discord: '.$plainmsg);
	}
}

/*if(isset($_GET['form']) and isset($_POST['platform']) and isset($_POST['msg']) and strlen($_POST['msg']) <= '280') {
	if($_POST['platform'] == '1' or $_POST['platform'] == '2' or $_POST['platform'] == '5' or $_POST['platform'] == '6') {
		require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/facebook/envoyer.php');
		send_facebook($_POST['msg']);
		$log .= 'Publication postée ! ';
	}
	if($_POST['platform'] == '1' or $_POST['platform'] == '3' or $_POST['platform'] == '5' or $_POST['platform'] == '7') {
		require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/twitter/twitter.php');
		send_twitter($_POST['msg']);
		$log .= 'Tweet posté ! ';
	}
	if($_POST['platform'] == '1' or $_POST['platform'] == '4' or $_POST['platform'] == '6' or $_POST['platform'] == '7') {
		require_once('Discord/DiscordBot.php');
		$log .= 'Discord envoyé ! ';
	}
}*/

if(isset($_GET['nl'])) {
	$message = 'La lettre d\'infos du '.$datejour.' est envoyée à '.date('H:i').'!'."\n\n".$nom;
	if($_POST['nl'] == 'fb' or $_POST['nl'] == 'all') {
		require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/facebook/envoyer.php');
		send_facebook($message);
		$log .= 'Publication lettre d\'infos postée ';
	}
	if($_POST['nl'] == 'tw' or $_POST['nl'] == 'all') {
		require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/twitter/twitter.php');
		send_twitter($message);
		$log .= 'Tweet lettre d\infos posté ';
	}
}
if(isset($_GET['swfb'])) {
	if(isset($_GET['debug']))
		$debug = true;
	header('Content-type: text/plain');
	require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/facebook_publisher.php');
	exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Publication sur les réseaux - <?php print $nomdusite; ?></title>
<?php print $cssadmin; ?>
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<?php require_once('inclus/banner.php');
if(!empty($log)) print '<p><b>'.$log.'</b></p>'; ?>
		<form action="?form" method="post">
			<label for="f_platform">Publier&nbsp;:</label>
			<ul id="f_platform">
				<li><input id="f_platform_fb" type="checkbox" name="pf[]" value="fb" checked> <label for="f_platform_fb">Facebook</label></li>
				<li><input id="f_platform_tw" type="checkbox" name="pf[]" value="tw" checked> <label for="f_platform_tw">Twitter</label></li>
				<li><input id="f_platform_dd" type="checkbox" name="pf[]" value="dd" checked> <label for="f_platform_dd">Discord</label></li>
			</ul>
			
			<!--<select id="f_platform" name="platform">
				<option value="1" selected>Partout</option>
				<option value="2">Facebook</option>
				<option value="3">Twitter</option>
				<option value="4">Discord</option>
				<option value="5">Facebook et Twitter</option>
				<option value="6">Facebook et Discord</option>
				<option value="7">Twitter et Discord</option>
			</select><br>-->
			<label for="f_msg">Message&nbsp;:</label><br>
			<textarea id="f_msg" name="msg" autocomplete="off" rows="20" style="width: 100%;" required></textarea><br>
			<input type="submit" value="Publier">
		</form>
		<p>Utilisez <i>$$</i> comme séparateur pour Twitter, et <i>$$$$</i> pour écrire vraiment <i>$$</i>.</p>
		<!--<a href="?swfb">Publier le message Facebook des logiciels mis à jour.</a><br>
		<a href="?swfb&debug">Debug message Facebook des logiciels mis à jour.</a>-->
	</body>
</html>