<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title=("Horloge "."$site_name");
$sound_path="/audio/page_sounds/gadget.mp3";
$stats_page = 'horloge'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
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
echo "Quand cette page a été chargée nous étions le ".getFormattedDate(time(), tr($tr0,'fndate'))." et il était ".getFormattedDate(time(), tr($tr0,'ftime'));
?>
<br>
</noscript>
<div style="display:none" id="ag003030">
<script type="text/javascript" src="/scripts/clock.js"></script>
<span id="date_heure"></span>
<script type="text/javascript">window.onload = date_heure('date_heure');</script>
</div>
<script>document.getElementById('ag003030').style.display='block';</script>
<a href="/gadgets.php">Retour à la liste des gadgets.</a>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>