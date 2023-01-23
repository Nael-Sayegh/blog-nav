<?php $logonly = true;
$adminonly=true;
$justpa = true;
$titlePAdm='Tickets';
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/phpmailer/src/PHPMailer.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/phpmailer/src/Exception.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/phpmailer/src/SMTP.php');
if(isset($_GET['archive'])) {
	$req = $bdd->prepare('UPDATE `tickets` SET `status`=3 WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['archive']));
}
if(isset($_GET['waiting'])) {
	$req = $bdd->prepare('UPDATE `tickets` SET `status`=2 WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['waiting']));
}
if(isset($_GET['delete']) and isset($_POST['del']) and $_POST['del'] == 'SUPPRIMER') {
	$req = $bdd->prepare('DELETE FROM `tickets` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['delete']));
}
if(isset($_GET['send']) and isset($_POST['msg'])) {
	$req = $bdd->prepare('SELECT * FROM `tickets` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['send']));
	if($data = $req->fetch()) {
		$msg = str_replace("\n\n", '</p><p>', $_POST['msg']);
		$msg = '<p>'.str_replace("\n", '<br>', $msg).'</p>';
		$msg2= strip_tags(html_entity_decode($msg));
		$msgs = json_decode($data['messages'], true);
		$time = time();
		$msgs[] = ['e'=>$_SERVER['REMOTE_USER'], 'm'=>1, 'd'=>$time, 't'=>$msg];
		$larname = $nom.' (Admin)';
		$req2 = $bdd->prepare('UPDATE `tickets` SET `messages`=?, `status`=2, `date`=?, `lastadmreply`=? WHERE `id`=? LIMIT 1');
		$req2->execute(array(json_encode($msgs), $time, $larname, $data['id']));
		$body = '<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Re: "'.htmlspecialchars($data['subject']).'" '.$nomdusite.'</title>
		<style type="text/css">#response{border-left:1px solid #0080FF;margin-left:8px;padding: 8px;}</style>
	</head>
	<body>
<h1>'.$nomdusite.'</h1>
		<p>Vous avez reçu une réponse pour votre message&nbsp;: <i>'.htmlspecialchars($data['subject']).'</i>.</p>
		<div id="response">'.$msg.'</div>
		<hr>
		<p>Merci de ne pas répondre à cet e-mail. Pour nous envoyer votre réponse, veuillez utiliser le lien ci-dessous.<br>
			<a href="https://www.progaccess.net/contacter.php?reply='.$data['id'].'&h='.$data['hash'].'">https://www.progaccess.net/contacter.php?reply='.$data['id'].'&h='.$data['hash'].'</a></p>
	</body>
</html>';
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = SMTP_HOST;
		$mail->Port = SMTP_PORT;
		$mail->SMTPAuth = true;
		$mail->Username = SMTP_USERNAME;
		$mail->Password = SMTP_PSW;
		$mail->setFrom('no_reply@progaccess.net', 'L\'administration '.$nomdusite);
		$mail->addReplyTo('no_reply@progaccess.net', 'no_reply');
		$mail->addAddress($data['expeditor_email']);
		$mail->Subject = 'Re: "'.htmlspecialchars($data['subject']).'" '.$nomdusite;
		$mail->CharSet = 'UTF-8';
		$mail->isHTML(TRUE);
		$mail->Body = $body;
		$mail->AltBody = $nomdusite."\r\nVous avez reçu une réponse pour votre message: \"".$data['subject']."\".\r\n\r\n$msg2\r\n________________\r\nMerci de ne pas répondre à cet e-mail. Pour envoyer votre réponse, veuillez utiliser le lien ci-dessous.\r\nhttps://www.progaccess.net/contacter.php?reply=".$data['id'].'&h='.$data['hash'];
		$mail->send();
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Tickets <?php print $nomdusite; ?></title>
<?php print $cssadmin; ?>
		<link rel="stylesheet" href="/admin/css/tickets.css">
<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<?php require_once('inclus/banner.php'); ?>
<ul>
<?php if(isset($_GET['ticket'])) { ?>
<li><a href="tickets.php">Liste des tickets</a></li>
<?php } ?>
</ul>
<?php
if(isset($_GET['ticket'])) {
	$req = $bdd->prepare('SELECT * FROM `tickets` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['ticket']));
	if($data = $req->fetch()) {
		echo '<p>Sujet&nbsp;: <b>'.htmlspecialchars($data['subject']).'</b><br>Expéditeur&nbsp: <b>'.htmlspecialchars($data['expeditor_name']).'</b><!-- (<b>'.htmlspecialchars($data['expeditor_email']).'</b>)--><br>Dernière activité&nbsp;: '.$data['lastadmreply'].' (le '.date('d/m/Y H:i', $data['date']).')<br>Statut&nbsp: <b style="color: #';
		switch($data['status']) {
			case 0: echo 'C00000;">Nouveau'; break;
			case 1: echo '606000;">Non lu'; break;
			case 2: echo '00C000;">En attente'; break;
			case 3: echo '0000C0;">Archivé'; break;
			default: echo 'black;">Erreur';
		}
		echo '</b></p><table id="ticket_msgs">';
		$messages = json_decode($data['messages'], true);
		foreach($messages as &$msg) {
			echo '<tr class="ticket_msg'.strval($msg['m']).'"><td rowspan="2" class="ticket_msgtd"></td>';
			echo '<td class="ticket_msginfo">';
			if($msg['m'] == 1)
				echo '<img alt="L\'administration '.$nomdusite.'" src="/image/logo16.png"> ';
			echo '<b>'.htmlspecialchars($msg['e']).'</b> '.date('d/m/Y H:i', $msg['d']).'</td></tr><tr><td>'.$msg['t'].'</td></tr>';
		}
		unset($msg);
		echo '</table>';
		if($data['status'] != 2)
			echo '<p><a href="?waiting='.$data['id'].'">Marquer comme lu</a></p>';
		if($data['status'] != 3)
			echo '<p><a href="?archive='.$data['id'].'">Archiver ce ticket</a></p>';
?>
		<form action="?send=<?php echo $data['id']; ?>" method="post">
			<fieldset><legend>Répondre</legend>
				<label for="f1_msg">Message&nbsp;:</label><br>
				<textarea id="f1_msg" name="msg" required rows="20" cols="500"><?php echo "\n\n".$nom.' (Administration '.$nomdusite.')'; ?></textarea><br>
				<input type="submit" value="Répondre">
			</fieldset>
		</form>
		<form action="?delete=<?php echo $data['id']; ?>" method="post">
			<fieldset><legend>Supprimer</legend>
				<label for="f2_del">Écrire SUPPRIMER en majuscules pour supprimer le ticket.</label><br>
				<input type="text" id="f2_del" name="del" required><br>
				<input type="submit" value="Supprimer">
			</fieldset>
		</form>
<?php
	}
	else
		echo '<p>Le ticket n\'existe pas.</p>';
} else {
?>
		<table id="tickets">
			<thead>
				<tr><th>Statut</th><th>Sujet</th><th>Correspondant</th><th>Dernière activité</th></tr>
			</thead>
			<tbody>
<?php
	$req = $bdd->prepare('SELECT * FROM `tickets` ORDER BY `status` ASC, `date` DESC');
	$req->execute();
	$tr2 = false;
	while($data = $req->fetch()) {
		echo '<tr class="ticket';
		if($tr2) echo ' ticket2';
		else echo ' ticket1';
		$tr2 = !$tr2;
		echo '"><td class="ticket_';
		switch($data['status']) {
			case 0: echo '0">Nouveau'; break;
			case 1: echo '1">Non lu'; break;
			case 2: echo '2">En cours'; break;
			case 3: echo '3">Archivé'; break;
			default: echo '">Erreur';
		}
		echo '</td><td><a href="?ticket='.$data['id'].'">'.htmlspecialchars($data['subject']).'</a></td><td>'.htmlspecialchars($data['expeditor_name']).'</td><td>'.$data['lastadmreply'] .'&nbsp;: le '.date('d/m/Y à H:i', $data['date']).'</td></tr>';
	}
?>
			</tbody>
		</table>
<?php } ?>

	</body>
</html>