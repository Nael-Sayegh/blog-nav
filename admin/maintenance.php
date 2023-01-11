<?php $logonly=true;
$adminonly=true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';

if(isset($_GET['codestat']))
	require_once $_SERVER['DOCUMENT_ROOT'].'/tasks/codestat.php';
$codestat_n_files = -1;
$codestat_n_lines = -1;
$codestat_n_chars = -1;
include $_SERVER['DOCUMENT_ROOT'].'/cache/codestatc.php';

if(isset($_GET['mm0'])) {
	$maintenance = fopen($_SERVER['DOCUMENT_ROOT'].'/inclus/maintenance_mode.php', 'w');
	fputs($maintenance, '<?php $modemaintenance=false; ?>');
	fclose($maintenance);
	$modemaintenance = false;
}
elseif(isset($_GET['mm1'])) {
	$maintenance = fopen($_SERVER['DOCUMENT_ROOT'].'/inclus/maintenance_mode.php', 'w');
	fputs($maintenance, '<?php $modemaintenance=true; ?>');
	fclose($maintenance);
	$modemaintenance = true;
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Maintenance - Administration - <?php print $nomdusite; ?></title>
		<?php print $cssadmin; ?>
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
		<h1>Maintenance - <a href="/"><?php print $nomdusite; ?></a></h1>
		<h2>Mode maintenance</h2>
		<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php';
if(isset($modemaintenance) and $modemaintenance)
	echo '<p>Mode maintenance activé</p><a href="?mm0">Désactiver le mode maintenance</a>';
else
	echo '<p>Mode maintenance désactivé</p><a href="?mm1">Activer le mode maintenance</a>';
?>
		<h2>Statistiques du code</h2>
		<p><?php echo $codestat_n_files.' fichiers, '.$codestat_n_lines.' lignes, '.$codestat_n_chars; ?> octets.<br>
Une valeur -1 est une erreur (essayer de refaire le cache).</p>
		<a href="?codestat">Recalculer les valeurs</a>
	</body>
</html>