<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once 'inclus/lib/PHPMailer/src/PHPMailer.php';
require_once 'inclus/lib/PHPMailer/src/Exception.php';
require_once 'inclus/lib/PHPMailer/src/SMTP.php';
require_once 'inclus/consts.php';
require 'inclus/log.php';
$log = '';

if(!isset($_GET['id'])) {
	header('Location: /newsletter.php');
	exit();
}

$req = $bdd->prepare('SELECT * FROM `newsletter_mails` WHERE `hash`=? AND `expire`>=?');
$req->execute(array($_GET['id'], time()));
if($nldata = $req->fetch()) {
	if(isset($_GET['stop'])) {
		$req2 = $bdd->prepare('DELETE FROM `newsletter_mails` WHERE `id`=? LIMIT 1');
		$req2->execute(array($nldata['id']));
		header('Location: newsletter.php?stop');
		
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = SMTP_HOST;
		$mail->Port = SMTP_PORT;
		$mail->SMTPAuth = true;
		$mail->Username = SMTP_USERNAME;
		$mail->Password = SMTP_PSW;
		$mail->setFrom('no_reply@progaccess.net', 'L\'administration '.$nomdusite);
		$mail->addReplyTo('no_reply@progaccess.net', 'L\'administration '.$nomdusite);
		$mail->addAddress($nldata['mail']);
		$mail->Subject = 'Désinscription de l\'actu '.$nomdusite;
		$mail->CharSet = 'UTF-8';
		$mail->IsHTML(TRUE);
		$mail->Body = '<!DOCTYPE html>
<html lang="'.$nldata['lang'].'">
	<head>
		<meta charset="utf-8" />
		<title>Confirmation du désabonnement de l\'actu '.$nomdusite.'</title>
	</head>
	<body>
		<div id="header">
<img src="https://www.progaccess.net/image/logo128.png" alt="Logo" />
			<h1>L\'actu '.$nomdusite.'</h1>
		</div>
		<div id="content">
			<h2>Bonjour '.$nldata['mail'].',</h2>
			<p>Vous avez bien été désabonné de l\'actu '.$nomdusite.'.</p>
<p>Ceci sera notre dernier mail, nous sommes tristes de vous voir partir et nous espérons vous revoir bientôt sur <a href="https://www.progaccess.net">'.$nomdusite.'</a>.</p>
			<p>Ce mail a été envoyé automatiquement, merci de ne pas répondre.</p>
			<p>Cordialement,<br />l\'administration '.$nomdusite.'</p>
		</div>
	</body>
</html>';
		$mail->AltBody = 'L\'actu '.$nomdusite.'
Bonjour '.$nldata['mail'].',
Vous avez bien été désabonné de l\'actu '.$nomdusite.'.
Ceci sera notre dernier mail, nous sommes tristes de vous voir partir et nous espérons vous revoir bientôt sur https://www.progaccess.net/
Ce mail a été envoyé automatiquement, merci de ne pas répondre.
Cordialement,
l\'administration '.$nomdusite;
		$mail->send();
		
		header('Location: /newsletter.php?stop');
		exit();
	}
	if(!$nldata['confirm']) {
		$req2 = $bdd->prepare('UPDATE `newsletter_mails` SET `confirm`=1 , `lastmail`=?, `lastmail_n`=? WHERE `id`=?');
		$req2->execute(array(time(), time(), $nldata['id']));
		$log .= 'Votre inscription à la l\'actu '.$nomdusite.' a bien été confirmée.<br />';
	}
	if(isset($_GET['mod'])) {
		$freq = $nldata['freq'];
		if(isset($_POST['freq']) and ($_POST['freq'] == '1' or $_POST['freq'] == '2' or $_POST['freq'] == '3' or $_POST['freq'] == '4' or $_POST['freq'] == '5'))
			$freq = $_POST['freq'];
		
				$freq_n = $nldata['freq_n'];
		if(isset($_POST['freq_n']) and ($_POST['freq_n'] == '1' or $_POST['freq_n'] == '2' or $_POST['freq_n'] == '3' or $_POST['freq_n'] == '4' or $_POST['freq_n'] == '5'))
			$freq_n = $_POST['freq_n'];
		
		$f_site = false;
		if(isset($_POST['notif_site']) and $_POST['notif_site'] == 'on') $f_site = true;
		$f_upd = false;
		if(isset($_POST['notif_up']) and $_POST['notif_up'] == 'on') $f_upd = true;
		$f_upd_n = false;
		if(isset($_POST['notif_up_n']) and $_POST['notif_up_n'] == 'on') $f_upd_n = true;
		$f_lang = $nldata['lang'];
		if(isset($_POST['lang']) and in_array($_POST['lang'], $langs_prio)) $f_lang = $_POST['lang'];
		$req = $bdd->prepare('UPDATE `newsletter_mails` SET `freq`=? , `freq_n`=? , `notif_site`=? , `notif_upd`=?, `notif_upd_n`=?, `lang`=? WHERE `id`=?');
		$req->execute(array($freq, $freq_n, $f_site, $f_upd, $f_upd_n, $f_lang, $nldata['id']));
	}
	$req2 = $bdd->prepare('UPDATE newsletter_mails SET expire=? WHERE id=?');
	$req2->execute(array(time()+31536000, $nldata['id']));
	$log .= 'Votre abonnement pour <i>'.htmlspecialchars($nldata['mail']).'</i> expirera le '.date('d/m/Y H:i', time()+31536000).'.';
	$args['id'] = $nldata['hash'];
}
else {
	header('Location: newsletter.php');
	exit();
}

$titre='L\'actu '.$nomdusite;
$cheminaudio='/audio/sons_des_pages/nl.mp3';
$stats_page = 'nlmod'; ?>
<!doctype html>
<html lang="<?php echo $lang; ?>">
<?php include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="Retour à l'accueil"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/searchtool.php';
include 'inclus/loginbox.php'; ?>
</div>
<?php include('inclus/son.php');
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
<?php if(!empty($log)) echo '<p><b>'.$log.'</b></p>'; ?>
<p>Sur cette page vous pouvez modifier les paramètres de votre abonnement aux lettres d'informations de <?php print $nomdusite; ?>.</p>
<form action="?mod&id=<?php echo $nldata['hash']; ?>" method="post">
<fieldset><legend>Actu <?php print $nomdusite; ?></legend>
	<label for="f_lang">Langue préférée&nbsp;:</label>
	<select id="f_lang" name="lang" autocomplete="off"><?php echo langs_html_opts($nldata['lang']); ?></select><br />
	<label for="f_freq">Recevoir un mail&nbsp;:</label>
	<select name="freq" id="f_freq" autocomplete="off"><option value="1"<?php if($nldata['freq']==1) echo ' selected'; ?>>Quotidiennement</option><option value="2"<?php if($nldata['freq']==2) echo ' selected'; ?>>Tous les 2 jours</option><option value="3"<?php if($nldata['freq']==3) echo ' selected'; ?>>Hebdomadairement</option><option value="4"<?php if($nldata['freq']==4) echo ' selected'; ?>>Quinzomadairement</option><option value="5"<?php if($nldata['freq']==5) echo ' selected'; ?>>Mensuellement</option></select><br />
	<label for="f_notif_site" autocomplete="off">Me notifier d'une mise à jour du site&nbsp;:</label>
	<input type="checkbox" name="notif_site" id="f_notif_site"<?php if($nldata['notif_site']) echo ' checked="checked"'; ?> /><br />
	<label for="f_notif_up">Me notifier de la mise à jour d'un article&nbsp;:</label>
	<select name="notif_up" id="f_notif_up" autocomplete="off"><option value="on"<?php if($nldata['notif_upd']) echo ' selected'; ?>>Oui</option><option value="off"<?php if(!$nldata['notif_upd']) echo ' selected'; ?>>Non</option></select><br />
</fieldset>
<fieldset><legend>Actu NVDA-FR</legend>
	<label for="f_freq_n">Recevoir un mail&nbsp;:</label>
	<select name="freq_n" id="f_freq_n" autocomplete="off"><option value="1"<?php if($nldata['freq_n']==1) echo ' selected'; ?>>Quotidiennement</option><option value="2"<?php if($nldata['freq_n']==2) echo ' selected'; ?>>Tous les 2 jours</option><option value="3"<?php if($nldata['freq_n']==3) echo ' selected'; ?>>Hebdomadairement</option><option value="4"<?php if($nldata['freq_n']==4) echo ' selected'; ?>>Quinzomadairement</option><option value="5"<?php if($nldata['freq_n']==5) echo ' selected'; ?>>Mensuellement</option></select><br />
	<label for="f_notif_up_n">Me notifier de la mise à jour d'un article&nbsp;:</label>
	<select name="notif_up_n" id="f_notif_up_n" autocomplete="off"><option value="on"<?php if($nldata['notif_upd_n']) echo ' selected'; ?>>Oui</option><option value="off"<?php if(!$nldata['notif_upd_n']) echo ' selected'; ?>>Non</option></select><br />
</fieldset>
	<input type="submit" value="Modifier l'abonnement" />
</form>
	<p>Ne plus recevoir de lettres d'information&nbsp;: <a href="?stop&id=<?php echo $nldata['hash']; ?>">Se désabonner</a></p>
</div>
<?php include 'inclus/footer.php'; ?> 
</body>
</html>
