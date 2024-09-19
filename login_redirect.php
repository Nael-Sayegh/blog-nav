<?php $logonly = true;
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang, 'redirlogin');
$title = tr($tr,'title');
$sound_path = '/audio/page_sounds/member.mp3';
$stats_page = 'redirlogin'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?php
echo str_replace('{{membername}}', $login['username'], tr($tr,'maintext'));
?>
<ul>
<?php if(isset($login['rank']) && $login['rank'] == 'a') {
	$req = $bdd->prepare('SELECT `works` FROM `team` WHERE `account_id`=? LIMIT 1');
	$req->execute(array($login['id']));
	if($data = $req->fetch()) {
		$worksnum = $data['works'];
	}
	if($worksnum == '1' or $worksnum == '2') { ?>
	<li><a href="/admin"><?php echo tr($tr,'adminlink').' ('.$site_name.')'; ?></a></li>
<?php }
	if($worksnum == '0' or $worksnum == '2') { ?>
	<li><a href="https://www.nvda.fr/admin?cid=<?php print $_COOKIE['connectid']; ?>&ses=<?php print $_COOKIE['session']; ?>"><?php echo tr($tr,'adminlink').' (NVDA-FR)'; ?></a></li>
<?php } } ?>
	<li><a href="/"><?php echo tr($tr,'homelink'); ?></a></li>
	<li><a href="/home.php"><?php echo tr($tr,'memberlink'); ?></a></li>
	<li><a href="/members_list.php"><?php echo tr($tr,'memberlistlink'); ?></a></li>
	<li><a href="/logout.php?token=<?php echo $login['token']; ?>"><?php echo tr($tr,'logoutlink'); ?></a></li>
</ul>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>