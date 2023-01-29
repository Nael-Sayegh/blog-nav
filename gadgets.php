<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title='Les gadgets et services de '.$site_name;
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
<p>Sur cette page vous retrouverez des liens pour accéder aux gadgets et aux services disponibles via <?php print $site_name; ?>.</p>
<h2 id="interne">Les gadgets internes à <?php print $site_name; ?></h2>
<ul>
<li><a href="GEOIP/infos.php">Infos diverses vous concernant</a></li>
<li><a href="gadgets/password_gen.php">Générateur de mots de passe</a></li>
<li><a href="gadgets/clock.php">Horloge <?php print $site_name; ?></a></li>
<li><a href="/gadgets/pof.php">Pile ou face</a></li>
</ul>
<h2 id="services">Les services <?php print $site_name; ?></h2>
<ul>
<li><a href="/browser_homepage.php">Page d'accueil pour navigateur</a></li>
<li><a href="/api">API JSON</a></li>
</ul>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>