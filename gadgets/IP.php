<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'inclus/log.php';
require_once "inclus/consts.php";
$titre=("Détecteur d'IP "."$nomdusite");
$cheminaudio="/audio/sons_des_pages/gadget.mp3";
$stats_page = 'ip'; ?>
<!doctype html>
<html lang="fr">
<?php include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="Retour à l'accueil"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/searchtool.php';
include 'inclus/loginbox.php'; ?>
</div>
<?php include('inclus/son.php');
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
<p>Vous avez bien été redirigé vers notre détecteur d'ip.<BR>
Note :<br>
si vous êtes caché derrière un VPN ou un proxy ce détecteur affichera l'IP via laquelle vous êtes connecté et non votre vraie IP.</p>
<?php
function get_ip() {
if (isset($_SERVER['HTTP_CLIENT_IP'])) {
return $_SERVER['HTTP_CLIENT_IP'];
}
elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
return $_SERVER['HTTP_X_FORWARDED_FOR'];
}
else {
return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
}
}
echo "Vous disposez de l'adresse IP publique suivante : ".get_ip();
?>
<br>
<a href="/gadgets.php">Retour à la liste des gadgets.</a>
</div>
<?php require_once "inclus/footer.php"; ?>
</body>
</html>