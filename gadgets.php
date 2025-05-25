<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang, 'gadgets');
$title = tr($tr, 'title', ['site' => $site_name]);
$sound_path = '/audio/page_sounds/gadget.mp3';
$stats_page = 'gadgets'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<p><?= tr($tr, 'intro_text', ['site' => $site_name]) ?></p>
<h2><?= tr($tr, 'gadget') ?></h2>
<ul>
<li><a href="gadgets/infos.php"><?= tr($tr, 'info') ?></a></li>
<li><a href="gadgets/password_gen.php"><?= tr($tr, 'password_gen') ?></a></li>
<li><a href="gadgets/flipping_coin.php"><?= tr($tr, 'flip_coin') ?></a></li>
</ul>
<h2><?= tr($tr, 'service') ?></h2>
<ul>
<li><a href="browser_homepage.php"><?= tr($tr, 'browser_hp') ?></a></li>
<li><a href="api"><?= tr($tr, 'api') ?></a></li>
</ul>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>