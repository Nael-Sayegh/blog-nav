<?php
$stats_page='article';
if(!isset($_GET['id'])) {header('Location: /');die();}
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('inclus/log.php');
require_once('inclus/consts.php');
require_once('inclus/isbot.php');
	if(isset($logged) && $logged == 'true' AND $login['rank'] == 'a') {
		$req2 = $bdd->prepare('SELECT `works` FROM `team` WHERE `account_id`=? LIMIT 1');
		$req2->execute(array($login['id']));
			if($data2 = $req2->fetch()) {
				$workn = $data2['works'];
			}
			$req2->closeCursor();
	}
$req = $bdd->prepare('SELECT * FROM softwares WHERE id=?');
$req->execute(array($_GET['id']));
$sw = $req->fetch();
if(!$sw){header('Location: /');die();}
if(!(isset($logged) and $logged and $settings['rank'] == 'a') and !$isbot) {
	$req = $bdd->prepare('UPDATE `softwares` SET `hits`=`hits`+1 WHERE `id`=? LIMIT 1');
	$req->execute(array($sw['id']));
}

$req = $bdd->prepare('SELECT * FROM `softwares_tr` WHERE `sw_id`=? AND `lang`=? AND `published`=1 LIMIT 1');
$req->execute(array($sw['id'], $lang));
if(!$sw_tr = $req->fetch()) {
	foreach($langs_prio as &$i) {
		$req = $bdd->prepare('SELECT * FROM `softwares_tr` WHERE `sw_id`=? AND `lang`=? AND `published`=1 LIMIT 1');
		$req->execute(array($sw['id'], $i));
		if($sw_tr = $req->fetch())
			break;
	}
	unset($i);
	if(!$sw_tr) {
		header('Location: /?sw_tr_error');
		exit();
	}
}

$tr = load_tr($lang, 'article');
$args['id'] = $sw['id'];
$titre = str_replace('{{site}}', $nomdusite, $sw_tr['name']);
$comlog = '';

if(isset($_GET['comment']) and isset($_POST['pseudo']) and isset($_POST['text'])) {
	if(strlen($_POST['pseudo']) <= 31 or strlen($_POST['text']) <= 1023) {
		$req = $bdd->prepare('INSERT INTO softwares_comments(sw_id,date,pseudo,text,ip) VALUES(?,?,?,?,?)');
		$req->execute(array($sw['id'], time(), $_POST['pseudo'], $_POST['text'], sha1($_SERVER['REMOTE_ADDR'])));
		$comlog = tr($tr,'comment_sent');
		header('Location: /article.php?id='.$sw['id']);
		
		# Add notification to subscribers
		$notif = json_encode(array('type'=>'new_comment', 'article'=>$sw['id']));
		$req2 = $bdd->prepare('INSERT INTO `notifs` (`date`,`account`,`data`) VALUES (?,?,?)');
		$time = time();
		$req = $bdd->prepare('SELECT `accounts`.`id`, `accounts`.`subscribed_comments`, `subscriptions_comments`.`id` AS `sub` FROM `accounts` LEFT JOIN `subscriptions_comments` ON `subscriptions_comments`.`account`=`accounts`.`id` AND `subscriptions_comments`.`article`=?');
		$req->execute(array($sw['id']));
		while($data = $req->fetch()) {
			if($data['subscribed_comments'] or $data['sub'])
				$req2->execute(array($time, $data['id'], $notif));
		}
		exit();
	}
	else $comlog = tr($tr,'comment_toolong');
}
if(isset($_GET['cdel'])) {
	if(isset($logged) && $logged == 'true' AND $login['rank'] == 'a' AND $workn == '1' or $workn == '2') {
	$req = $bdd->prepare('DELETE FROM `softwares_comments` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['cdel']));
	} else {
		$req = $bdd->prepare('DELETE FROM `softwares_comments` WHERE `id`=? AND `date`>? AND `ip`=? LIMIT 1');
	$req->execute(array($_GET['cdel'], time()-86400, sha1($_SERVER['REMOTE_ADDR'])));
	}
}
if(isset($_GET['cedit2']) and isset($_POST['text'])) {
	if(isset($logged) && $logged == 'true' AND $login['rank'] == 'a' AND $workn == '1' or $workn == '2') {
	$req = $bdd->prepare('SELECT id FROM softwares_comments WHERE id=? LIMIT 1');
	$req->execute(array($_GET['cedit2']));
	} else {
		$req = $bdd->prepare('SELECT id FROM softwares_comments WHERE id=? AND date>? AND ip=? LIMIT 1');
	$req->execute(array($_GET['cedit2'], time()-86400, sha1($_SERVER['REMOTE_ADDR'])));
	}
	if($data = $req->fetch()) {
		if(strlen($_POST['text']) <= 1023) {
			$req2 = $bdd->prepare('UPDATE softwares_comments SET text=? WHERE id=? LIMIT 1');
			$req2->execute(array($_POST['text'], $data['id']));
			header('Location: /article.php?id='.$sw['id']);
		}
		else $comlog = tr($tr,'commentmod_toolong');
	} else $comlog = tr($tr,'commentmod_error');
	$req->closeCursor();
}
if(isset($_GET['subscribe-comments']) and isset($_GET['token']) and isset($logged) and $logged and $_GET['token']==$login['token']) {
	$req = $bdd->prepare('SELECT `id` FROM `subscriptions_comments` WHERE `account`=? AND `article`=? LIMIT 1');
	$req->execute(array($login['id'], $sw['id']));
	if(!$req->fetch()) {
		$req = $bdd->prepare('INSERT INTO `subscriptions_comments` (`account`, `article`) VALUES (?, ?)');
		$req->execute(array($login['id'], $sw['id']));
	}
}
elseif(isset($_GET['unsubscribe-comments']) and isset($_GET['token']) and isset($logged) and $logged and $_GET['token']==$login['token']) {
	$req = $bdd->prepare('DELETE FROM `subscriptions_comments` WHERE `account`=? AND `article`=? LIMIT 1');
	$req->execute(array($login['id'], $sw['id']));
}

$cheminaudio='/audio/sons_des_pages/hihi6.mp3';
$cat = array();
$req = $bdd->query('SELECT * FROM `softwares_categories`');
while($data = $req->fetch()) {
	$cat[$data['id']] = $data['name'];
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
	<?php require_once('inclus/header.php'); ?>
	<body>
<?php require_once('inclus/banner.php');
require_once('inclus/son.php'); ?>
		<main id="container">
			<h1 id="contenu"><?php print $titre; ?></h1>
			<p><a href="/art_list.php"><?php echo tr($tr,'categories_link'); ?></a></p>
			<?php
if(isset($logged) and $logged) {
	if(isset($login['rank']) && $login['rank'] == 'a') {
$req = $bdd->prepare('SELECT `works` FROM `team` WHERE `account_id`=? LIMIT 1');
				$req->execute(array($login['id']));
				if($data = $req->fetch()) {
					$worksnum = $data['works'];
				}
				if($worksnum == '1' or $worksnum == '2') { ?>
			<ul>
				<li><a href="/admin/sw_mod.php?id=<?php echo $sw['id']; ?>"><?php echo str_replace('{{title}}', $titre, tr($tr,'adminlink_article').' '.$sw['name']); ?></a></li>
				<li><a href="/admin/sw_mod.php?listfiles=<?php echo $sw['id']; ?>"><?php echo str_replace('{{title}}', $titre, tr($tr,'adminlink_listfiles').' '.$sw['name']); ?></a></li>
				<li><a href="/admin/translate.php?type=article&id=<?php echo $sw['id']; ?>"><?php echo str_replace('{{title}}', $titre, tr($tr,'adminlink_trs').' '.$sw['name']); ?></a></li>
			</ul>
<?php
	} } ?>
	<details>
	<summary><?php echo tr($tr,'detailskw'); ?></summary>
	<ul>
	<?php foreach(explode(' ',$sw_tr['keywords']) as $keyword){print '<li>'.$keyword.'</li>';}
	?></ul>
	</details>
	<?php
	$req = $bdd->prepare('SELECT `id` FROM `subscriptions_comments` WHERE `account`=? AND `article`=? LIMIT 1');
	$req->execute(array($login['id'], $sw['id']));
	$sub = $req->fetch() ? true:false;
	echo '<a id="btunsub1" class="comments_btsubscription" href="?id='.$sw['id'].'&unsubscribe-comments&token='.$login['token'].'" title="'.tr($tr,'comments_unsubscribe_long').'" onclick="subscribe_comments(event, false)" style="display:'.($sub?'initial':'none').'">'.tr($tr,'comments_unsubscribe').'</a>';
	echo '<a id="btsub1" class="comments_btsubscription" href="?id='.$sw['id'].'&subscribe-comments&token='.$login['token'].'" title="'.tr($tr,'comments_subscribe_long').'" onclick="subscribe_comments(event, true)" style="display:'.($sub?'none':'initial').'">'.tr($tr,'comments_subscribe').'</a>';
}

echo '<div id="descart" role="article">'.str_replace('{{site}}', $nomdusite, $sw_tr['text']).'</div>';
$fichiersexistants = false;
$first = true;
$altc = true;
$req = $bdd->prepare('SELECT * FROM softwares_files WHERE sw_id=? ORDER BY date DESC');
$req->execute(array($sw['id']));
while($data = $req->fetch()) {
	if($first) {
		echo '<span style="position:absolute; top:-999px; left:-9999px;" role="heading" aria-level="2">'.tr($tr,'files_title',array('title'=>$titre)).'</span><table id="sw_files"><caption><strong>'.tr($tr,'files_title',array('title'=>$titre)).'</strong></caption><thead><tr><th>'.tr($tr,'files_size').'</th><th>'.tr($tr,'files_date').'</th><th>'.tr($tr,'files_hits').'</th><th>MD5, SHA1</th></tr></thead><tbody>';
		$fichiersexistants = true;
		$first = false;
	}
	echo '<tr class="sw_file';
	if($altc) echo ' altc';
	echo '"><td class="sw_file_ltd"><a class="sw_file_link" href="/r?';
	if(empty($data['label']))
		echo 'id='.$data['id'];
	else
		echo 'p='.$data['label'];
	echo '">'.str_replace('{{site}}', $nomdusite, $data['title']).'</a> <span class="sw_file_size">('.numberlocale(human_filesize($data['filesize'])).tr($tr0,'byte_letter').')</span></td><td class="sw_file_date">'.strftime(tr($tr0,'fndatetime'),$data['date']).'</td><td class="sw_file_hits">'.$data['hits'].'</td><td><details aria-label="'.tr($tr,'files_sums').'" title="'.tr($tr,'files_sums').'"><summary class="sw_file_sum">'.$data['name'].'</summary>md5: '.$data['md5'].'<br>sha1: '.$data['sha1'].'</details></tr>';
	$altc = !$altc;
}
if(!$first)
	echo '</tbody></table>';

$first = true;
$altc = true;
$req = $bdd->prepare('SELECT * FROM softwares_mirrors WHERE sw_id=? ORDER BY hits DESC');
$req->execute(array($sw['id']));
while($data = $req->fetch()) {
	if($first) {
		echo '<table id="sw_mirrors"><caption role="heading" aria-level="2"><strong>'.tr($tr,'mirrors_title',array('title'=>$titre)).'</strong></caption><thead><tr><th>'.tr($tr,'mirrors_filetitle').'</th><th>'.tr($tr,'mirrors_mirrors').'</th><th>'.tr($tr,'files_date').'</th><th>'.tr($tr,'files_hits').'</th></tr></thead><tbody>';
		$first = false;
	}
	echo '<tr class="sw_file';
	if($altc) echo ' altc';
	echo '"><td class="sw_file_title"><a class="sw_file_link" href="/r.php?m&';
	if(empty($data['label']))
		echo 'id='.$data['id'];
	else
		echo 'p='.$data['label'];
	echo '">'.str_replace('{{site}}', $nomdusite, $data['title']).'</a></td><td class="sw_file_ltd">';
	$i = 0;
	$links = json_decode($data['links'], true);
	foreach($links as $link) {
		if($i != 0)
			echo ' | ';
		echo '<a class="sw_file_link" href="/r.php?m='.$i.'&';
		if(empty($data['label']))
			echo 'id='.$data['id'];
		else
			echo 'p='.$data['label'];
		echo '">'.$link[0].'</a>';
		$i ++;
	}
	echo '</td><td class="sw_file_date">'.strftime(tr($tr0,'fndatetime'),$data['date']).'</td><td class="sw_file_hits">'.$data['hits'].'</td></tr>';
	$altc = !$altc;
}
if(!$first)
	echo '</tbody></table>';
?>
			<table><caption><?php echo tr($tr,'infos'); ?></caption>
				<tbody>
					<?php if($sw_tr['website'] != '') echo '<tr><td>'.tr($tr,'website').'</td><td><a target="_blank" rel="noopener" href="'.$sw_tr['website'].'" id="owlink">'.$sw_tr['website'].'</a></td></tr>'; if($fichiersexistants) echo '<tr><td>'.tr($tr,'hits').'</td>
<td>'.$sw['downloads'].'</td></tr>'; ?>
					<tr>
						<td><?php echo tr($tr,'visits'); ?></td>
						<td><?php echo $sw['hits']; ?></td>
					</tr>
					<tr>
						<td><?php echo tr($tr,'lastmodif'); ?></td>
						<td><?php echo tr($tr,'lastmodif_val',array('author'=>$sw['author'],'date'=>strftime(tr($tr0,'fndatetime'),$sw['date']))); ?></td>
					</tr>
					<tr>
						<td><?php echo tr($tr,'id'); ?></td>
						<td>A<?php echo $sw['id']; ?> (<?php echo '<a href="/cat.php?id='.$sw['category'].'">'.$cat[$sw['category']].'</a>'; ?>)</td>
					</tr>
				</tbody>
			</table>
			<h2><?php echo tr($tr,'comments_title'); ?></h2>
			<div id="comments">
				<?php
$req = $bdd->prepare('SELECT * FROM softwares_comments WHERE sw_id=? ORDER BY date DESC LIMIT 20');
$req->execute(array($sw['id']));
while($data = $req->fetch()) {
	echo '<div class="comment"><div class="comment_h"><h3><!--K'.$data['id'].': -->';
	echo htmlentities($data['pseudo']);
	echo ' ('.date('d/m/Y, H:i', $data['date']).')</h3>';
	echo '</div>';
	echo '<p class="comment_p">'.str_replace("\n",'<br>',htmlentities($data['text'])).'</p></div>';
		if(($data['ip'] == sha1($_SERVER['REMOTE_ADDR']) and $data['date'] > time()-86400) OR (isset($logged) && $logged == 'true' AND $login['rank'] == 'a' AND $workn == '0' or $workn == '2')) {
		echo '<a href="?id='.$sw['id'].'&cedit='.$data['id'].'#cedit"><img alt="'.tr($tr,'comments_mod').'" src="https://zettascript.org/images/mod16.png"></a><a href="?id='.$sw['id'].'&cdel='.$data['id'].'" onclick="return confirm(\''.tr($tr,'confirm_del_com').'\')"><img alt="'.tr($tr,'comments_rm').'" src="https://zettascript.org/images/trash16.png"></a>';
	}
}
$req->closeCursor();

if(isset($_GET['cedit'])) {
	if(isset($logged) && $logged == 'true' AND $login['rank'] == 'a' AND $workn == '0' or $workn == '2') {
	$req = $bdd->prepare('SELECT id, text FROM softwares_comments WHERE id=?');
	$req->execute(array($_GET['cedit']));
	} else {
	$req = $bdd->prepare('SELECT id, text FROM softwares_comments WHERE id=? AND date>? AND ip=?');
	$req->execute(array($_GET['cedit'], time()-86400, sha1($_SERVER['REMOTE_ADDR'])));
	}
	if($data = $req->fetch()) {
?>
				<form action="?id=<?php echo $sw['id'].'&cedit2='.$data['id'] ?>" method="post" id="cedit">
					<fieldset><legend><?php echo tr($tr,'comments_mod'); ?></legend>
						<label for="fc_text"><?php echo tr($tr,'comments_text'); ?></label><br>
						<textarea id="fc_text" class="ta" name="text" maxlength="1023"><?php echo htmlentities($data['text']); ?></textarea><br>
						<input type="submit" value="<?php echo tr($tr,'comments_ok'); ?>">
					</fieldset>
				</form>
<?php }$req->closeCursor();}
if(isset($logged) && $logged == 'true') { ?>
	<form action="?id=<?php echo $sw['id'] ?>&comment" method="post" id="comment_write">
					<?php if($comlog!='') echo '<strong>'.$comlog.'</strong>'; ?>
					<fieldset><legend><?php echo tr($tr,'comments_send'); ?></legend>
						<p><?php echo tr($tr,'comments_warn'); ?></p>
						<label for="fc_pseudo"><?php echo tr($tr,'comments_pseudo'); ?></label>
						<input type="text" id="fc_pseudo" name="pseudo" maxlength="31"<?php echo ' value="'.$login['username'].'"'; ?> readonly disabled><br>
						<label for="fc_text"><?php echo tr($tr,'comments_text'); ?></label><br>
						<textarea id="fc_text" class="ta" name="text" maxlength="1023"><?php if(isset($_POST['text']) and strlen($_POST['text']) <= 1023) echo htmlentities($_POST['text']); ?></textarea><br>
						<input type="submit" value="<?php echo tr($tr,'comments_ok'); ?>">
					</fieldset>
				</form>
<?php } else { echo tr($tr,'limitcommentext'); } ?>
			</div>
		</main>
		<?php require_once('inclus/footer.php');

if(isset($logged) and $logged) { ?>
		
		<script type="text/javascript" src="/scripts/jquery.js"></script>
		<script type="text/javascript" src="/scripts/pa_api.js"></script>
		<script type="text/javascript">
	function subscribe_comments(e, mod) {
		var api_session = new API_Session("/api/");
		api_session.session = <?php echo json_encode($_COOKIE['session']); ?>;
		api_session.connectid = <?php echo json_encode($login['connectid']); ?>;
		api_session.token = <?php echo json_encode($login['token']); ?>;
		if(mod) {
			api_subscribe_comments(api_session, <?php echo json_encode($sw['id']); ?>, function(data) {
				if(data["subscribed"]["comments"] != undefined) {
					if(data["subscribed"]["comments"].indexOf(<?php echo json_encode($sw['id']); ?>) != -1) {
						$("#btsub1").attr("style", "display:none;");
						$("#btunsub1").attr("style", "display:initial;");
					}
				}
			});
		}
		else {
			api_unsubscribe_comments(api_session, <?php echo json_encode($sw['id']); ?>, function(data) {
				if(data["unsubscribed"]["comments"] != undefined) {
					if(data["unsubscribed"]["comments"].indexOf(<?php echo json_encode($sw['id']); ?>) != -1) {
						$("#btunsub1").attr("style", "display:none;");
						$("#btsub1").attr("style", "display:initial;");
					}
				}
			});
		}
		e.preventDefault();
	}
	</script>
		<?php } ?>
	</body>
</html>