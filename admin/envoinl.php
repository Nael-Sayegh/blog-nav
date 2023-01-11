<?php $logonly=true;
$adminonly=true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';

if(isset($_GET['act']) and $_GET['act'] == 'form') {
	if(isset($_POST['mail']) and !empty($_POST['mail']))
		$debug = $_POST['mail'];
	if(isset($_POST['simulate']))
		$simulate = true;
	header('Content-type: text/plain');
	header('Content-disposition: inline');
	include $_SERVER['DOCUMENT_ROOT'].'/tasks/nl_manager.php';
	exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Administration - <?php print $nomdusite; ?></title>
<?php print $cssadmin; ?>
<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<h1>Envoi de la NL - <a href="/"><?php print $nomdusite; ?></a></h1>
<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php'; ?>
<form action="?act=form" method="post">
	<label for="maildebug">Debuguer pour&nbsp;:</label>
	<input type="email" name="mail" id="maildebug"><br>
	<label for="mailsimulate">Simulation (n'envoie aucun mail, ne modifie pas la bdd)&nbsp;:</label>
	<input type="checkbox" name="simulate" id="mailsimulate"><br>
	<input type="submit" value="Envoyer">
</form>
</body>
</html>