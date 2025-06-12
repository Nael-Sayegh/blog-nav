<?php
session_start();
$back = $_SESSION['intended_403'] ?? ($_SERVER['HTTP_REFERER'] ?? null);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>403 accès refusé</title>
</head>
<body>
<h1>Erreur 403&nbsp;: accès refusé</h1>
<p>Cette action nécessite <?= (isset($missingAdminRightLabel)) ? 'l\'autorisation "'.$missingAdminRightLabel.'"' : 'des autorisations' ?> que vous ne possédez pas.<br>
Si vous pensez que c'est une erreur, <a href="/contact_form.php">contactez nous</a>. Sinon, merci de revenir <?php if ($back)
{
    echo '<a href="'.htmlspecialchars((string) $back).'">d\'où vous venez</a>, ou ';
} ?><a href="/">à l'accueil</a>.</p>
</body>
</html>
