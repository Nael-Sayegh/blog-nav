<?php
require_once 'consts.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once 'lib/PHPMailer/src/PHPMailer.php';
require_once 'lib/PHPMailer/src/Exception.php';
require_once 'lib/PHPMailer/src/SMTP.php';

function send_confirm($account, $email, $mhash, $username) {
	global $lang;
	$tr1 = load_tr($lang, 'sendconfirm');

	$link = 'https://www.progaccess.net/confirm.php?id='.$account.'&h='.$mhash;
	$message = '<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8" />
			<title>'.tr($tr1,'title').'</title>
		</head>
		<body>
			<div id="header">
	<img src="https://www.progaccess.net/image/logo128-170.png" alt="'.tr($tr1,'logo').'" />
				<h1>'.tr($tr1,'title2').'</h1>
			</div>
			<div id="content">
				<h2>'.tr($tr1,'hello',array('name'=>$username)).'</h2>
				<p>'.tr($tr1,'confirm').'</p>
				<a id="link" href="'.$link.'">'.tr($tr1,'click').'</a>
				<p>'.tr($tr1,'auto').'</p>
				<p>'.tr($tr1,'signature').'</p>
			</div>
		</body>
	</html>';

	$msgtext = tr($tr1,'plaintext',array('name'=>$username,'link'=>$link));

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = SMTP_HOST;
	$mail->Port = SMTP_PORT;
	$mail->SMTPAuth = true;
	$mail->Username = SMTP_USERNAME;
	$mail->Password = SMTP_PSW;
	$mail->setFrom('no_reply@progaccess.net', tr($tr1,'admin'));
	$mail->addReplyTo('no_reply@progaccess.net', tr($tr1,'team'));
	$mail->addAddress($email);
	$mail->Subject = tr($tr1,'subject');
	$mail->CharSet = 'UTF-8';
	$mail->IsHTML(TRUE);
	$mail->Body = $message;
	$mail->AltBody = $msgtext;
	$mail->send();
}
?>
