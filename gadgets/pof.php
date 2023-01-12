<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('inclus/log.php');
require_once('inclus/consts.php');
$titre="Pile ou face";
$cheminaudio="/audio/sons_des_pages/piece.mp3";
$stats_page = 'pof'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('inclus/header.php'); ?>
<body>
<?php require_once('inclus/banner.php');
require_once('inclus/son.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $titre; ?></h1>
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
<?php require_once('inclus/footer.php'); ?>
</body>
</html>