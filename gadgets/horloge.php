<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('inclus/log.php');
require_once('inclus/consts.php');
$titre=("Horloge "."$nomdusite");
$cheminaudio="/audio/sons_des_pages/gadget.mp3";
$stats_page = 'horloge'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('inclus/header.php'); ?>
<body>
<?php require_once('inclus/banner.php');
require_once('inclus/son.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $titre; ?></h1>
<noscript>
<p>Vous avez bien été redirigé vers notre horloge.<br>
Remarque :<br> 
Nous avons détecté que JavaScript est bloqué sur votre navigateur, nous respectons ce choix et nous allons donc vous communiquer une horloge en php.<br>
L'horloge affichera donc l'heure française, si vous ne résidez pas en France il est possible que l'heure affichée ici ne corresponde pas à l'heure exacte de votre pays.<br>
Notez également que cette horloge sera incapable de s'actualiser automatiquement, elle affichera donc l'heure qu'il était au moment où la page a été chargée.</p>
</noscript>
<div style="display:none" id="xyz">
<p>Vous avez bien été redirigé vers notre horloge.</p>
</div>
<script>document.getElementById('xyz').style.display='block';</script>
<noscript>
<?php
setlocale(LC_TIME,"fr_FR.UTF8");
echo "Quand cette page a été chargée nous étions le ".strftime("%A %e %B %Y");
echo ", il était ".strftime("%k:%M:%S");
?>
<br>
</noscript>
<div style="display:none" id="ag003030">
<script type="text/javascript" src="/scripts/horloge.js"></script>
<span id="date_heure"></span>
<script type="text/javascript">window.onload = date_heure('date_heure');</script>
</div>
<script>document.getElementById('ag003030').style.display='block';</script>
<a href="/gadgets.php">Retour à la liste des gadgets.</a>
</main>
<?php require_once('inclus/footer.php'); ?>
</body>
</html>