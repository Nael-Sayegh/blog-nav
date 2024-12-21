<?php
$document_root = __DIR__.'/..';
require_once($document_root.'/include/consts.php');

// supprimer les IPs de plus de 28 jours
$req = $bdd->prepare('UPDATE `softwares_comments` SET `ip`="rm" WHERE `date` < ?');
$req->execute(array(time()-2419200));

// notifier l'admin des nouveaux messages
$req = $bdd->prepare('SELECT * FROM `softwares_comments` WHERE `date` > ?');
$req->execute(array(time()-86400));
$n = 0;
$msg = '';
while($data = $req->fetch()) {
	$req2 = $bdd->prepare('SELECT `id`,`name` FROM `softwares` WHERE `id`=? LIMIT 1');
	$req2->execute(array($data['sw_id']));
	$sw = $req2->fetch()
	$msg .= 'De "'.$data['pseudo'].'" à '.date('d/m/Y H:i').' sur '.$sw['name'].":\n".$data['text']."\n".SITE_URL."/a".$sw['id']."\n\n";
	$n ++;
}
if($n > 0) {
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;
	require_once($document_root.'/vendor/autoload.php');
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = SMTP_HOST;
	$mail->Port = SMTP_PORT;
	$mail->SMTPAuth = true;
	$mail->Username = SMTP_USERNAME;
	$mail->Password = SMTP_PSW;
	$mail->setFrom(SMTP_MAIL, SMTP_NAME);
	$mail->addReplyTo(SMTP_MAIL, SMTP_NAME);
	$mail->addAddress('corentin@progaccess.net');
	$mail->Subject = $n.' nouveaux commentaires sur ProgAccess';
	$mail->CharSet = 'UTF-8';
	$mail->IsHTML(false);
	$mail->Body = 'Compte rendu des commentaires d\'hier: '.$n." nouveaux.\n\n";
	$mail->send();
}
?>
