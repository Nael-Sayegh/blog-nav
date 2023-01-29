<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<?php if($_GET['text'] == 'bantext')
echo '<title>Vous êtes banni</title>';
else echo '<title>403 accès refusé</title>';
?>
<audio src="<?php /*echo $_SERVER['DOCUMENT_ROOT'];*/ ?>/403/403.mp3" autoplay></audio>
</head>
<body>
<h1>Erreur 403&nbsp;: accès refusé</h1>
<?php if($_GET['text'] == 'bantext')
echo '<p>Votre compte membre '.$login['username'].' est banni du site.<br>
Pour faire une réclamation, <a href="/contact_form.php">contactez-nous</a>, sinon <a href="/">retournez à l\'accueil</a>.<br>
L\'Administration</p>';
else
echo '<p>Des autorisations que vous ne possédez pas sont requises pour accéder à la ressource demandée.<br>
Si vous pensez disposer des droits requis, <a href="/contact_form.php">contactez-nous</a>, sinon <a href="/">retournez à l\'accueil</a>.<br>
L\'Administration</p>'; ?>
</body>
</html>