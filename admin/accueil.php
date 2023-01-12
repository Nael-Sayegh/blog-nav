<?php $logonly = true;
$adminonly = true;
$justpa = true;
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Administration - <?php print $nomdusite; ?></title>
<?php print $cssadmin; ?>
<script src="/scripts/default.js"></script>
</head>
<body>
<?php require_once('inclus/banner.php'); ?>
<h2>Statistiques</h2>
<p><?php
require_once($_SERVER['DOCUMENT_ROOT'].'/cache/codestatc.php');
if(isset($codestat_n_files)) echo $codestat_n_files.' fichiers, ';
if(isset($codestat_n_lines)) echo $codestat_n_lines.' lignes, ';
if(isset($codestat_n_chars)) echo $codestat_n_chars.' octets';
?></p>
<p>La géolocalisation utilise <a href="https://www.maxmind.com/">MaxMind</a></p>
</body>
</html>