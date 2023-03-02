<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang,'gadgets');
$title=tr($tr,'title',array('site'=>$site_name));
$sound_path='/audio/page_sounds/gadget.mp3';
$stats_page='gadgets'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<p><?php echo tr($tr,'intro_text',array('site'=>$site_name)); ?></p>
<h2><?php echo tr($tr,'gadget'); ?></h2>
<ul>
<li><a href="GEOIP/infos.php"><?php echo tr($tr,'info'); ?></a></li>
<li><a href="gadgets/password_gen.php"><?php echo tr($tr,'password_gen'); ?></a></li>
<li><a href="gadgets/clock.php"><?php echo tr($tr,'clock'); ?></a></li>
<li><a href="/gadgets/flipping_coin.php"><?php echo tr($tr,'flip_coin'); ?></a></li>
</ul>
<h2><?php echo tr($tr,'service'); ?></h2>
<ul>
<li><a href="/browser_homepage.php"><?php echo tr($tr,'browser_hp'); ?></a></li>
<li><a href="/api"><?php echo tr($tr,'api'); ?></a></li>
</ul>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>