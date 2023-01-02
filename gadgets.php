<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'inclus/log.php';
require_once 'inclus/consts.php';
$titre='Les gadgets et services de '.$nomdusite;
$cheminaudio='/audio/sons_des_pages/gadget.mp3';
$stats_page='gadgets'; ?>
<!doctype html>
<html lang="fr">
<?php include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="Retour à l'accueil"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</div>
<?php include('inclus/son.php');
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
<p>Sur cette page vous retrouverez des liens pour accéder aux gadgets et aux services disponibles via <?php print $nomdusite; ?>.</p>
<h2 id="interne">Les gadgets internes à <?php print $nomdusite; ?></h2>
<ul>
<li id="infos"><a href="GEOIP/infos.php">Infos diverses vous concernant</a></li>
<li id="mdp"><a href="gadgets/ParamPasswd.php">Générateur de mots de passe</a></li>
<li id="heure"><a href="gadgets/horloge.php">Horloge <?php print $nomdusite; ?></a></li>
<li id="POF"><a href="/gadgets/pof.php">Pile ou face</a></li>
</ul>
<h2 id="services">Les services <?php print $nomdusite; ?></h2>
<ul>
<li id="accnav"><a href="/accueil_navigateurs.php">Page d'accueil pour navigateur</a></li>
<li id="accnav"><a href="/api">API JSON</a></li>
</ul>
</div>
<?php include 'inclus/footer.php'; ?>
</body>
</html>