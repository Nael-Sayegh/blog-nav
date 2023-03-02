<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/consts.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once('include/lib/phpmailer/src/PHPMailer.php');
require_once('include/lib/phpmailer/src/Exception.php');
require_once('include/lib/phpmailer/src/SMTP.php');

if(isset($_GET['id']) and isset($_GET['h'])) {
	$req = $bdd->prepare('SELECT `id`, `username`, `email`, `signup_date`, `settings` FROM `accounts` WHERE `id`=? AND `signup_date`<? AND `confirmed`=0');
	$req->execute(array($_GET['id'], time()+86400));
	while($data = $req->fetch()) {
		if(json_decode($data['settings'], true)['mhash'] == $_GET['h']) {
			$req = $bdd->prepare('UPDATE `accounts` SET `confirmed`=1 WHERE `id`=? LIMIT 1');
			$req->execute(array($data['id']));
			
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = SMTP_HOST;
			$mail->Port = SMTP_PORT;
			$mail->SMTPAuth = true;
			$mail->Username = SMTP_USERNAME;
			$mail->Password = SMTP_PSW;
			$mail->setFrom(SMTP_MAIL, SMTP_NAME);
			$mail->addReplyTo(SMTP_MAIL, SMTP_NAME);
			$mail->addAddress($data['email']);
			$mail->Subject = $site_name.' : vos informations de membre';
			$mail->CharSet = 'UTF-8';
			$mail->IsHTML(TRUE);
			$mail->Body = '<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Vos informations de membre '.$site_name.'</title>
</head>
<body>
<h1>'.$site_name.'</h1>
<img src="'.SITE_URL.'/image/logo128-170.png" alt="Logo">
<h2>Bonjour '.htmlentities($data['username']).' et bienvenue dans la communauté '.$site_name.'</h2>
<p>Veuillez conserver précieusement ce message, il contient vos informations de membre qui vous seront utiles en cas de perte de mot de passe afin d\'<a href="'.SITE_URL.'/fg_password.php">en demander un nouveau</a>.<br>
Si vous changez par la suite votre nom d\'utilisateur ou votre adresse mail, vos nouvelles informations ne vous seront pas réenvoyées (conservez donc vos changements en lieu sûr).<br>
Vos informations sont les suivantes :</p>
<ul>
<li>Nom d\'utilisateur : '.htmlentities($data['username']).'</li>
<li>Adresse mail : '.$data['email'].'</li>
<li>Numéro de membre : M'.$data['id'].'</li>
<li>Date d\'inscription : '.date('d/m/Y à H:i',$data['signup_date']).'</li>
</ul>
<p>Ne répondez pas à ce mail, il vous a été envoyé automatiquement.<br>
Cordialement.<br>
L\'administration '.$site_name.'</p>
</body>
</html>';
			$mail->AltBody = 'Ce mail est uniquement disponible en HTML, activer l\'affichage HTML dans votre messagerie';
			$mail->send();
			header('Location: /login.php?confirmed');
			$req2 = $bdd->prepare('UPDATE `newsletter_mails` SET `confirm`=1 , `lastmail`=? WHERE `mail`=? LIMIT 1');
			$req2->execute(array(time(), $data['email']));
			exit();
		}
	}
}
header('Location: /login.php?confirm_err');
exit();
?>