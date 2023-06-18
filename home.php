<?php
$logonly = true;
require_once('include/log.php');
$stats_page='home';
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/consts.php');
$tr = load_tr($lang, 'home');
$sound_path='/audio/page_sounds/member.mp3';
$title = tr($tr,'title');

$log = '';
if((isset($_GET['token']) and $_GET['token'] == $login['token']) or (isset($_POST['token']) and $_POST['token'] == $login['token'])) {
	if(isset($_GET['sendmail'])) {
		require_once('include/sendconfirm.php');
		send_confirm($login['id'], $login['email'], $settings['mhash'], $login['username']);
	}
	if(isset($_GET['settings']) and isset($_POST['username']) and isset($_POST['mail'])) {
		$ok = true;
		$username = $_POST['username'];
		if(strlen($username) < 3 or strlen($username) > 32) {$ok=false;$log .= '<li>'.tr($tr,'err_name_length').'</li>';}
		if(strlen($_POST['mail']) > 255 or empty($_POST['mail'])) {$ok=false;$log .= '<li>'.tr($tr,'err_mail_length').'</li>';}
		$req = $bdd->prepare('SELECT `username`,`email` FROM `accounts` WHERE (`username`=? OR `email`=?) AND `id`!=? LIMIT 1');
		$req->execute(array($username, $_POST['mail'], $login['id']));
		if($data = $req->fetch()) {
			if($data['username'] == $username) {$ok = false;
				$log .= '<li>'.tr($tr,'err_name_used').'</li>';}
			if($data['email'] == $_POST['mail']) {$ok = false;
				$log .= '<li>'.tr($tr,'err_mail_used').'</li>';}
		}
		$bd_m = 0;
		$bd_d = 0;
		if(isset($_POST['bd_m']) and isset($_POST['bd_d']) and preg_match('/^\d\d?$/',$_POST['bd_m']) and preg_match('/^\d\d?$/',$_POST['bd_d'])) {
			$bd_m = intval($_POST['bd_m']);
			$bd_d = intval($_POST['bd_d']);
		}
		$comments_sub = intval(isset($_POST['comments_sub']) and $_POST['comments_sub'] == 'on');
		$notif_mail = isset($_POST['notif_mail']) and $_POST['notif_mail'] == 'on';

		if($ok) {
			$settings['bd_m'] = $bd_m;
			$settings['bd_d'] = $bd_d;
			$settings['notif_mail'] = $notif_mail;
			if($_POST['mail'] != $login['email']) {
				$settings['mhash'] = hash('sha512',strval(time()+random_int(100000,9999999)).$login['password'].strval(random_int(100000,9999999)));
				$req = $bdd->prepare('UPDATE `accounts` SET `username`=?, `email`=?, `confirmed`=0, `settings`=?, `subscribed_comments`=? WHERE `id`=? LIMIT 1');
				$req->execute(array($username, $_POST['mail'], json_encode($settings), $comments_sub, $login['id']));
				header('Location: /home.php?settings_ok&mail_sent');
				require_once('include/sendconfirm.php');
				send_confirm($login['id'], $_POST['mail'], $settings['mhash'], $username);
			}
			else {
				$req = $bdd->prepare('UPDATE `accounts` SET `username`=? , `email`=? , `settings`=?, `subscribed_comments`=? WHERE `id`=? LIMIT 1');
				$req->execute(array($username, $_POST['mail'], json_encode($settings), $comments_sub, $login['id']));
				header('Location: /home.php?settings_ok');
			}
			exit();
		}
	}
	if(isset($_GET['chpsw']) and isset($_POST['oldpsw']) and isset($_POST['newpsw']) and isset($_POST['newrpsw'])) {
		$ok = true;
		if($_POST['newpsw'] != $_POST['newrpsw']) {$ok=false;$log .= '<li>'.tr($tr,'err_psw_same').'</li>';}
		if(strlen($_POST['newpsw']) < 8 or strlen($_POST['newpsw']) > 64) {$ok=false;$log .= '<li>'.tr($tr,'err_psw_length').'</li>';}
		if(!password_verify($_POST['oldpsw'], $login['password'])) {$ok=false;$log .= '<li>'.tr($tr,'err_psw_old').'</li>';}

		if($ok) {
			$req = $bdd->prepare('UPDATE `accounts` SET `password`=? WHERE `id`=? LIMIT 1');
			$req->execute(array(password_hash($_POST['newpsw'], PASSWORD_DEFAULT), $login['id']));
			header('Location: /home.php?psw_ok');
			exit();
		}
	}
	if(isset($_GET['rm']) and isset($_POST['psw']) and isset($_POST['msgrm'])) {
		$ok = true;
		if(strlen($_POST['msgrm']) > 8192) {$ok=false;$log .= '<li>'.tr($tr,'err_rmmsg_length').'</li>';}
		if(!password_verify($_POST['psw'], $login['password'])) {$ok=false;$log .= '<li>'.tr($tr,'err_psw_bad').'</li>';}

		if($ok) {
			$req = $bdd->prepare('DELETE FROM `accounts` WHERE `id`=? LIMIT 1');
			$req->execute(array($login['id']));
			header('Location: /login.php?goodbye');
			exit();
		}
	}
	if(isset($_GET['chforum']) and isset($_POST['username']) and isset($login['forum_id']) and $login['forum_id'] !== NULL) {
		require_once('include/flarum.php');
		update_forum_account($login, $_POST['username']);
		$log .= '<li>'.tr($tr,'log_forum_updated_ok').'</li>';
	}
	if(isset($_GET['newforum']) and (!isset($login['forum_id']) or $login['forum_id'] === NULL)) {
		require_once('include/flarum.php');
		$newforum_result = create_forum_account($login['id'], $login['username'], $login['email']);
		$login['forum_id'] = $newforum_result['id'];
		$login['forum_psw'] = $newforum_result['psw'];
		$login['forum_username'] = $newforum_result['username'];
		$log .= '<li>'.tr($tr,'log_forum_created_ok', array('token'=>$login['token'])).'</li>';
	}
	if(isset($_GET['notifs_all_read'])) {
		$req = $bdd->prepare('UPDATE `notifs` SET `unread`=0 WHERE `account`=? AND `unread`=1');
		$req->execute(array($login['id']), array('token'=>$login['token']));
		$rep['notifs_all_read'] = true;
	}
	if(isset($_GET['notif_read'])) {
		$req = $bdd->prepare('UPDATE `notifs` SET `unread`=0 WHERE `id`=? AND `account`=?');
		$req->execute(array($_GET['notif_read'], $login['id']));
	}
	if(isset($_GET['notif_unread'])) {
		$req = $bdd->prepare('UPDATE `notifs` SET `unread`=1 WHERE `id`=? AND `account`=?');
		$req->execute(array($_GET['notif_unread'], $login['id']));
	}
	if(isset($_GET['rm_ses'])) {
		$req = $bdd->prepare('UPDATE `sessions` SET `expire`=? WHERE `account`=? AND `id`=? AND `expire`>? LIMIT 1');
		$req->execute(array(time()-1, $login['id'], $_GET['rm_ses'], time()));
	}
}
require_once('include/user_rank.php');
if(isset($_GET['settings_ok']))
	$log .= '<li>'.tr($tr,'log_settings_ok').'</li>';
if(isset($_GET['psw_ok']))
	$log .= '<li>'.tr($tr,'log_psw_ok').'</li>';
if(isset($_GET['mail_sent']))
	$log .= '<li>'.tr($tr,'log_mail_sent').'</li>';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
	<h1 id="contenu"><?php print $title; ?></h1>
<?php if(!empty($log)) echo '<ul class="log">'.$log.'</ul>';
if($login['confirmed'] == 0)
	echo '<p>'.tr($tr,'confirm_mail').'<br><a href="/home.php?sendmail&mail_sent&token='.$login['token'].'">'.tr($tr,'send_mail').'</a></p>';
?>
<ul>
	<li><?php echo tr($tr,'profile_rank', array('rank'=>urank($login['rank']))); ?></li>
	<li><?php echo tr($tr,'profile_id', array('id'=> ($login['team_id']? 'E'.$login['team_id'].'':'') . 'M'.$login['id'])); ?></li>
	<li><?php echo tr($tr,'profile_signup_date',array('date'=>getFormattedDate($login['signup_date'], tr($tr0,'fndatetime')))); ?></li>
	<?php if(isset($settings['bd_m']) and isset($settings['bd_d'])) { ?>
	<li><?php $date=getdate(); echo tr($tr,'profile_birthday',array('date'=>((($settings['bd_m']==$date['mon'] and $settings['bd_d']==$date['mday']) or ($settings['bd_m']==2 and $date['mon']==3 and $settings['bd_d']==29 and $date['mday']==1 and $date['year']%4==0)) ? tr($tr,'profile_happy_birthday').' &#127874;' : zeros($settings['bd_d'],2).'/'.zeros($settings['bd_m'],2)))); ?></li>
	<?php } ?>
</ul>
	
	<h3 id="notifs"><?php echo tr($tr,'notifs'); ?></h3>
	<a href="?notifs_all_read&token=<?php echo $login['token']; ?>" onclick="read_all_notifs(event)"><?php echo tr($tr,'notifs_read_all'); ?></a>
<?php
$notifs_read = '';
$notifs_unread = '';
$req = $bdd->prepare('SELECT * FROM `notifs` WHERE `account`=? ORDER BY `date` DESC');
$req->execute(array($login['id']));
while($notif = $req->fetch()) {
	$data = json_decode($notif['data'], true);
	$notif_html = '<li id="lnotif'.$notif['id'].'" class="lnotif lnotif_'.($notif['unread']?'':'un').'read"><span class="lnotif_date">'.getFormattedDate($notif['date'], tr($tr0,'fndatetime')).'</span> <span class="lnotif_text">';
	if(isset($data['type'])) {
		if($data['type'] == 'new_comment' and isset($data['article'])) {
			$req2 = $bdd->prepare('SELECT `name` FROM `softwares` WHERE `id`=? LIMIT 1');
			$req2->execute(array($data['article']));
			if($tmp = $req2->fetch())
				$notif_html .= tr($tr,'notifs_new_comment',array('link'=>'<a href="/article.php?id='.$data['article'].'">'.$tmp['name'].'</a>.'));
		}
	}
	$notif_html .= '</span> <a class="lnotif_readlink" href="?notif_read='.$notif['id'].'&token='.$login['token'].'" onclick="read_notif(event, '.$notif['id'].', true)" style="display:'.($notif['unread']?'initial':'none').'">('.tr($tr,'notifs_read').')</a><a class="lnotif_unreadlink" href="?notif_unread='.$notif['id'].'&token='.$login['token'].'" onclick="read_notif(event, '.$notif['id'].', false)" style="display:'.($notif['unread']?'none':'initial').'">('.tr($tr,'notifs_unread').')</a></li>';
	if($notif['unread'])
		$notifs_unread .= $notif_html;
	else
		$notifs_read .= $notif_html;
}
echo '<ul id="notifs_unread">' . $notifs_unread . '</ul>';
echo '<details><summary>'.tr($tr,'notifs_show_read').'</summary><ul id="notifs_read">' . $notifs_read . '</ul></details>';
?>
	
	<h3 id="sessions"><?php echo tr($tr,'sessions'); ?></h3>
	<ul>
<?php
$time = time();
$req = $bdd->prepare('SELECT * FROM `sessions` WHERE `account`=? ORDER BY `expire` DESC');
$req->execute(array($login['id']));
while($data = $req->fetch()) {
	echo '<li>';
	echo tr($tr,'session_item',array('created'=>getFormattedDate($data['created'], tr($tr0,'fndatetime')), 'expires'=>getFormattedDate($data['expire'], tr($tr0,'fndatetime'))));
	if($data['expire'] > $time)
		echo ' (<a href="?rm_ses='.$data['id'].'&token='.$login['token'].'">'.tr($tr,'session_item_remove').'</a>)';
	if($data['id'] == $login['session_id'])
		echo ' (<strong>'.tr($tr,'session_item_current').'</strong>)';
	echo '</li>';
}
?>
	</ul>
	
	<h3 id="settings"><?php echo tr($tr,'settings'); ?></h3>
	<form action="?settings" method="post">
		<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
		<fieldset><legend><?php echo tr($tr,'settings_account'); ?></legend>
			<table>
				<tr><td class="formlabel"><label for="f1_username"><?php echo tr($tr,'settings_name'); ?></label></td>
					<td><input type="text" id="f1_username" name="username" value="<?php echo htmlentities($login['username']); ?>" maxlength="32" required></td></tr>
				<tr><td class="formlabel"><label for="f1_mail"><?php echo tr($tr,'settings_mail'); ?></label></td>
					<td><input type="email" id="f1_mail" name="mail" value="<?php echo htmlentities($login['email']); ?>" maxlength="255" required></td></tr>
				<?php /*<tr><td class="formlabel"><label for="f1_notifcom"><?php echo tr($tr,'settings_notifcom'); ?></label></td>
					<td><input type="checkbox" id="f1_notifcom" name="notifcom" autocomplete="off"<?php if(isset($settings['notifcom']) and $settings['notifcom']==1)echo ' checked'; ?>></td></tr>*/ ?>
				<tr><td class="formlabel"><?php echo tr($tr,'settings_birthday'); ?></td>
					<td><label for="f1_bd_m"><?php echo tr($tr,'settings_birthday_month'); ?></label> <input type="number" id="f1_bd_m" name="bd_m" value="<?php echo isset($settings['bd_m'])? $settings['bd_m']:'0'; ?>" min="0" max="12" size="4">
						<label for="f1_bd_d"><?php echo tr($tr,'settings_birthday_day'); ?></label> <input type="number" id="f1_bd_d" name="bd_d" value="<?php echo isset($settings['bd_d'])? $settings['bd_d']:'0'; ?>" min="0" max="31" size="4"></td></tr>
				<tr><td class="formlabel"><label for="f1_comments_sub"><?php echo tr($tr,'settings_comments_sub'); ?></label></td>
					<td><input type="checkbox" id="f1_comments_sub" name="comments_sub"<?php echo $login['subscribed_comments']? ' checked':''; ?>></td></tr>
				<!-- <tr><td class="formlabel"><label for="f1_notif_mail"><?php echo tr($tr,'settings_notif_mail'); ?></label></td>
					<td><input type="checkbox" id="f1_notif_mail" name="notif_mail"<?php echo $settings['notif_mail']? ' checked':''; ?>></td></tr>-->
				<tr><td></td>
					<td><input type="submit" value="<?php echo tr($tr,'settings_submit'); ?>"></td></tr>
			</table>
		</fieldset>
	</form>
	<form action="?chpsw" method="post">
		<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
		<fieldset><legend><?php echo tr($tr,'chpsw'); ?></legend>
			<table>
				<tr><td class="formlabel"><label for="f2_oldpsw"><?php echo tr($tr,'chpsw_old'); ?></label></td>
					<td><input type="password" id="f2_oldpsw" name="oldpsw" maxlength="64" required></td></tr>
				<tr><td class="formlabel"><label for="f2_newpsw"><?php echo tr($tr,'chpsw_new'); ?></label></td>
					<td><input type="password" id="f2_newpsw" name="newpsw" maxlength="64" required></td></tr>
				<tr><td class="formlabel"><label for="f2_newrpsw"><?php echo tr($tr,'chpsw_new_re'); ?></label></td>
					<td><input type="password" id="f2_newrpsw" name="newrpsw" maxlength="64" required></td></tr>
				<tr><td></td>
					<td><input type="submit" value="<?php echo tr($tr,'chpsw_submit'); ?>"></td></tr>
			</table>
		</fieldset>
	</form>
<?php
if(isset($login['forum_id']) and $login['forum_id'] !== NULL) {
	$req = $bdd->prepare('SELECT `forum_username` FROM `accounts` WHERE `id`=? LIMIT 1');
	$req->execute(array($login['id']));
	if($data = $req->fetch()) {
		$forum_username = $data['forum_username'];
?>
	<form action="?chforum" method="post">
		<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
		<fieldset><legend><?php echo tr($tr,'chforum'); ?></legend>
			<p><?php echo tr($tr,'chforum_username_info'); ?></p>
			<table>
				<tr><td class="formlabel"><label for="f4_username"><?php echo tr($tr,'chforum_username'); ?></label></td>
					<td><input type="text" id="f4_username" name="username" maxlength="64" value="<?php echo $forum_username; ?>" autocomplete="off" required></td></tr>
				<tr><td></td>
					<td><input type="submit" value="<?php echo tr($tr,'chforum_submit'); ?>"></td></tr>
			</table>
		</fieldset>
	</form>
<?php
	}
	else
		echo '<p>Erreur avec les informations sur le compte Forum ProgAccess&nbsp;: veuillez contacter un administrateur.</p>';
} else {
?>
	<fieldset><legend><?php echo tr($tr,'newforum'); ?></legend>
		<a href="?newforum&token=<?php echo $login['token']; ?>"><?php echo tr($tr,'newforum_link'); ?></a>
	</fieldset>
<?php
}
?>
	<form action="?rm" method="post">
		<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
		<fieldset><legend><?php echo tr($tr,'remove_account'); ?></legend>
			<table>
				<tr><td colspan="2"><?php echo tr($tr,'remove_account_warn'); ?></td></tr>
				<tr><td class="formlabel"><label for="f3_msg"><?php echo tr($tr,'remove_account_msg'); ?></label></td>
					<td><textarea id="f3_msg" style="width:100%;" name="msgrm" maxlength="8192"><?php if(isset($_POST['msgrm']) and strlen($_POST['msgrm'])<=8192)echo htmlentities($_POST['msgrm']); ?></textarea></td></tr>
				<tr><td class="formlabel"><label for="f3_psw"><?php echo tr($tr,'remove_account_psw'); ?></label></td>
					<td><input type="password" id="f3_psw" name="psw" maxlength="64" required></td></tr>
				<tr><td></td>
					<td><input type="submit" value="<?php echo tr($tr,'remove_account_submit'); ?>"></td></tr>
			</table>
		</fieldset>
	</form>
</main>
<?php require_once('include/footer.php'); ?>

<script type="text/javascript" src="/scripts/jquery.js"></script>
<script type="text/javascript" src="/scripts/pa_api.js"></script>
<script type="text/javascript">
var api_session = new API_Session("/api/");
api_session.session = <?php echo json_encode($_COOKIE['session']); ?>;
api_session.connectid = <?php echo json_encode($login['connectid']); ?>;
api_session.token = <?php echo json_encode($login['token']); ?>;

function read_notif(e, notif, mod) {
	if(mod) {
		api_read_notif(api_session, notif, function(data) {
			if(data["read_notif"] == notif) {
				$("#lnotif"+notif).attr("class", "lnotif lnotif_read");
				$("#lnotif"+notif+" .lnotif_readlink").attr("style", "display: none;");
				$("#lnotif"+notif+" .lnotif_unreadlink").attr("style", "display: initial;");
				$("#lnotif"+notif).detach().appendTo("#notifs_read");
			}
		});
	}
	else {
		api_unread_notif(api_session, notif, function(data) {
			if(data["unread_notif"] == notif) {
				$("#lnotif"+notif).attr("class", "lnotif lnotif_unread");
				$("#lnotif"+notif+" .lnotif_unreadlink").attr("style", "display: none;");
				$("#lnotif"+notif+" .lnotif_readlink").attr("style", "display: initial;");
				$("#lnotif"+notif).detach().appendTo("#notifs_unread");
			}
		});
	}
	e.preventDefault();
}

function read_all_notifs(e) {
	api_read_all_notifs(api_session, function(data) {
		$(".lnotif_unread").each(function(i) {
			$(this).attr("class", "lnotif lnotif_read");
			$(this).children(".lnotif_readlink").attr("style", "display: none;");
			$(this).children(".lnotif_unreadlink").attr("style", "display: initial;");
			$(this).detach().appendTo("#notifs_read");
		});
	});
	e.preventDefault();
}
</script>
</body>
</html>