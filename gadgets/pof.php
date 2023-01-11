<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'inclus/log.php';
require_once "inclus/consts.php";
$titre="Pile ou face";
$cheminaudio="/audio/sons_des_pages/piece.mp3";
$stats_page = 'pof'; ?>
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
</div>
<?php require_once "inclus/footer.php"; ?>
</body>
</html>