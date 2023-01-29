<?php $logonly = true;
$adminonly=true;
$justpa = true;
$titlePAdm='Liste des abonnés à l\'actu';
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/include/consts.php');
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Liste des abonnés à la lettre d'informations</title>
<?php print $admin_css_path; ?>
<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<?php require_once('include/banner.php'); ?>
		<table>
			<thead>
				<tr><th>Adresse e-mail</th><th>Hash</th><th>Fréquence</th><th>Dernier mail</th><th>Expiration</th></tr>
			</thead>
			<tbody>
<?php
$req = $bdd->prepare('SELECT * FROM `newsletter_mails`');
$req->execute();

while($data = $req->fetch()) {
	echo '<tr><td>'.$data['mail'].'</td><td>'.$data['hash'].'</td><td>'.$data['freq'].'</td><td>'.date('d/m/Y H:i',$data['lastmail']).'</td><td>'.date('d/m/Y H:i',$data['expire']).'</td></tr>';
}

$req->closeCursor();
?>
			</tbody>
		</table>
	</body>
</html>