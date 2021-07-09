<?php $logonly = true;
set_include_path($_SERVER['DOCUMENT_ROOT']);
require 'inclus/log.php';
require_once 'inclus/consts.php';
$tr = load_tr($lang, 'redirlogin');
$titre = tr($tr,'title');
$cheminaudio = '/audio/sons_des_pages/membre.mp3';
$stats_page = 'redirlogin'; ?>
<!doctype html>
<html lang="<?php echo $lang; ?>">
<?php require_once 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="<?php echo tr($tr0,'banner_homelink'); ?>"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</div>
<?php include('inclus/son.php');
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
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
	<li><a href="/admin"><?php echo tr($tr,'adminlink').' ('.$nomdusite.')'; ?></a></li>
<?php }
	if($worksnum == '0' or $worksnum == '2') { ?>
	<li><a href="https://www.nvda-fr.org/admin?cid=<?php print $_COOKIE['connectid']; ?>&ses=<?php print $_COOKIE['session']; ?>"><?php echo tr($tr,'adminlink').' (NVDA-FR)'; ?></a></li>
<?php } }
if(isset($login['forum_id']) and $login['forum_id'] !== NULL) { ?>
	<li><a href="/auth_forum.php?token=<?php echo $login['token']; ?>"><?php echo tr($tr,'forumlink'); ?></a></li>
<?php } ?>
	<li><a href="/"><?php echo tr($tr,'homelink'); ?></a></li>
	<li><a href="/home.php"><?php echo tr($tr,'memberlink'); ?></a></li>
	<li><a href="/alist.php"><?php echo tr($tr,'memberlistlink'); ?></a></li>
	<li><a href="/logout.php?token=<?php echo $login['token']; ?>"><?php echo tr($tr,'logoutlink'); ?></a></li>
</ul>
</div>
<?php include 'inclus/footer.php'; ?>
</body>
</html>
