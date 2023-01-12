<h1><?php if(isset($titlePAdm)) echo $titlePAdm.' - '; ?><a href="/admin">Administration</a> - <a href="/"><?php print $nomdusite; ?></a></h1>
<h2>Connecté en tant que <?php print $nom; ?></h2>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php');
require_once('inclus/menu.php'); ?>