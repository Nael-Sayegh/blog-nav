<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Vous êtes banni</title>
<audio src="/audio/forbidden.mp3" autoplay></audio>
</head>
<body>
<h1>Erreur 403&nbsp;: accès refusé</h1>
<p>Votre compte membre <?php if (isset($logged) && $logged)
{
    echo $login['username'];
} ?> est banni du site.<br>
Pour faire une réclamation, <a href="/contact_form.php">contactez-nous</a>, sinon <a href="/">retournez à l'accueil</a>.</p>
</body>
</html>