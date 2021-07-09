<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'inclus/log.php';
require_once 'inclus/consts.php';
$titre="Alerte grave, votre navigateur utilise le moteur de rendu Trident";
$cheminaudio='/audio/sons_des_pages/Alarm.mp3';
$stats_page='trident'; ?>
<!doctype html>
<html lang="fr">
<?php include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="Retour à l'accueil"><?php print $nomdusite; ?></a></h1>
<?php include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</div>
<?php include('inclus/sontrident.php');
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
<p>Chers visiteurs,<br />
suite à de nombreux visiteurs mécontents nous avons rétablit l'accès au site depuis Internet Explorer ou autres dérivés à partir de <?php print $nomdusite; ?> 15.0.<br />
Malgré tout, les navigateurs du style d'Internet Explorer ne respectent pas bien les dernières normes web utilisées par le site, afin de vous offrir une expérience de visite optimale nous vous recommandons l'un des navigateurs suivants&nbsp;:</p>
<ul>
<li><a href="/r.php?p=ff">Firefox 32 bits</a></li>
<li><a href="/r.php?p=ff64">Firefox 64 bits</a></li>
<li><a href="/r.php?p=opera">Opera</a></li>
<li><a href="/r.php?p=vivaldi">Vivaldi 32 bits</a></li>
<li><a href="/r.php?p=vivaldi64">Vivaldi 64 bits</a></li>
<li><a href="/r.php?p=gchrome">Google Chrome 32 bits</a></li>
<li><a href="/r.php?p=gchrome64">Google Chrome 64 bits</a></li>
<li><a href="/r.php?p=kmeleon">K-Meleon</a></li>
<li><a href="/r.php?p=cometbird">Comet Bird</a></li>
</ul>
<p>Sous Windows 10, vous pouvez aussi utiliser Microsoft Edge.<br />
Sous Windows XP, certains navigateurs ci-dessus ne sont pas compatible.<br />
Enfin, d'autres navigateurs peuvent fonctionner, mais ils ne sont pas tous disponible ici, vous pouvez toujours utiliser Internet Explorer malgré tout.</p>
</div>
<?php require_once 'inclus/footer.php'; ?>
</body>
</html>