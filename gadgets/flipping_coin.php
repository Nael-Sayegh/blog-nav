<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title="Pile ou face";
$sound_path="/audio/page_sounds/flipping_coin.mp3";
$stats_page = 'pof'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<p>Vous avez bien été redirigé vers notre gadget pile ou face.</p>
<?php
$i =rand(1,2);
if($i == 1)
{
echo 'C\'est pile <br>';
}
else
{
echo 'C\'est face <br>';
}
?>
<a href="/gadgets.php">Retour à la liste des gadgets.</a>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>