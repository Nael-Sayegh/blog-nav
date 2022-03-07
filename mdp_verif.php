<?php
$stats_page='mdp_verif';
set_include_path($_SERVER['DOCUMENT_ROOT']);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once 'inclus/lib/PHPMailer/src/PHPMailer.php';
require_once 'inclus/lib/PHPMailer/src/Exception.php';
require_once 'inclus/lib/PHPMailer/src/SMTP.php';
include_once 'inclus/log.php';
require_once 'inclus/consts.php';
if(isset($_POST['identite']) and isset($_POST['email']) and isset($_POST['sujet']) and isset($_POST['msg'])) {

if($_POST['sujet'] != 'non') {
if($_POST['sujet'] < 10) {
$postid = 'M00'.$_POST['sujet'];
} elseif($_POST['sujet'] >= 10 and $_POST['sujet'] < 100) {
$postid = 'M0'.$_POST['sujet'];
} else { $postid = 'M'.$_POST['sujet']; }
} else { $postid = 'Inconnu'; }

if($_POST['msg'] != 'non') {
$postmsg = date('d/m/Y à H:i',$_POST['msg']);
} else $postmsg = 'Inconnue'; }

$msg = '<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<title>Mot de passe oublié</title>
</head>
<body>
<h1>Un membre a perdu son mot de passe '.$nomdusite.', voici les informations qu\'il a pu fournir</h1>
<ul>
<li>Nom d\'utilisateur : 'htmlentities(.$_POST['identite']).'</li>
<li>Adresse mail : '.htmlentities($_POST['email']).'</li>
<li>Numéro de membre : '.htmlentities($postid).'</li>
<li>Date d\'inscription : '.htmlentities($postmsg).'</li>
</ul>
</body>
</html>';

$strDestin='miklhcos@progaccess33.net';
$sujet=$nomdusite.' : mot de passe oublié';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = SMTP_HOST;
$mail->Port = SMTP_PORT;
$mail->SMTPAuth = true;
$mail->Username = SMTP_USERNAME;
$mail->Password = SMTP_PSW;
$mail->setFrom($_POST['email'], $_POST['identite']);
		$mail->addReplyTo($_POST['email'], $_POST['identite']);
$mail->AddAddress($strDestin);
$mail->Subject = $sujet;
$mail->CharSet = 'UTF-8';
$mail->IsHTML(TRUE);
$mail->Body = $msg;
if($mail->send()) {
header('Location: /');
}
else { echo $mail->ErrorInfo; }
?>
