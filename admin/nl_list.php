<?php $logonly = true;
$adminonly=true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Liste des abonnés à la lettre d'informations</title>
<?php print $cssadmin; ?>
<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<h1>Inscrits à la NL - <a href="/"><?php print $nomdusite.' '.$versionnom; ?></a></h1>
<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php'; ?>
		<table>
			<thead>
				<tr><th>Adresse e-mail</th><th>Hash</th><th>Fréquence</th><th>Dernier mail</th><th>Expiration</th></tr>
			</thead>
			<tbody>
<?php
$req = $bdd->prepare('SELECT * FROM `newsletter_mails`');
$req->execute();

while($data = $req->fetch()) {
	echo '<tr><td>'.$data['mail'].'</td><td>'.$data['hash'].'</td><td>'.$data['freq'].'</td><td>'.date('d/m/Y H:i:s',$data['lastmail']).'</td><td>'.date('d/m/Y H:i:s',$data['expire']).'</td></tr>';
}

$req->closeCursor();
?>
			</tbody>
		</table>
	</body>
</html>