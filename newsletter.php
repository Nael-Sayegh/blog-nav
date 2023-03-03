<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once('include/lib/phpmailer/src/PHPMailer.php');
require_once('include/lib/phpmailer/src/Exception.php');
require_once('include/lib/phpmailer/src/SMTP.php');
require_once('include/log.php');
require_once('include/consts.php');
$stats_page = 'newsletter';
$log = '';
if(isset($_GET['a']) and $_GET['a'] == 's') {
	if(!isset($_POST['mail']) or strlen($_POST['mail']) > 255 or empty($_POST['mail']))
		$log .= 'L\'adresse e-mail ne doit pas être vide et ne doit pas excéder les 255 caractères&#8239;!<br>';
	if(!isset($_POST['freq']) or !($_POST['freq'] == '1' or $_POST['freq'] == '2' or $_POST['freq'] == '3' or $_POST['freq'] == '4' or $_POST['freq'] == '5'))
		$log .= 'Veuillez renseigner une fréquence d\'envoi valide.<br>';
	if(empty($log)) {
		$req = $bdd->prepare('SELECT `id` FROM `newsletter_mails` WHERE `mail`=? LIMIT 1');
		$req->execute(array($_POST['mail']));
		if($req->fetch())
			$log .= 'Cette adresse est déjà inscrite&#8239;!';
		else {
			$hash = sha1(strval(rand()+time()).$_POST['mail']).sha1($_POST['mail'].$_SERVER['REMOTE_ADDR'].strval(rand()));
			$f_site = false;
			if(isset($_POST['notif_site']) and $_POST['notif_site'] == 'on') $f_site = true;
			$f_upd = false;
			if(isset($_POST['notif_up']) and $_POST['notif_up'] == 'on') $f_upd = true;
			$f_upd_n = false;
			if(isset($_POST['notif_up_n']) and $_POST['notif_up_n'] == 'on') $f_upd_n = true;
			$message = '<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Confirmation de l\'inscription à l\'actu '.$site_name.'</title>
	</head>
	<body>
		<div id="header">
			<img id="logo" alt="Logo" src="data:image/png;base64,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\">
			<h1>L\'actu '.$site_name.'</h1>
		</div>
		<div id="content">
			<h2>Bonjour</h2>
			<p>Vous avez bien été abonné à l\'actu '.$site_name.'.</p>
			<p>Confirmez votre inscription en cliquant sur ce lien (expire après 24h)&nbsp;:</p>
			<a id="link" href="'.SITE_URL.'/nlmod.php?id='.$hash.'">Cliquez ici</a>
			<p>Vous pouvez, avec ce même lien, modifier les paramètres de votre abonnement ou vous désinscrire. Vous serez automatiquement désinscrit un an après la dernière fois que vous visitez ce lien.</p>
			<p>Ce mail a été envoyé automatiquement, merci de ne pas répondre.</p>
			<p>Cordialement,<br>L\'équipe '.$site_name.'</p>
		</div>
	</body>
</html>';

			$msgtext = "Confirmation d'inscription à l'actu $site_name (version texte) :
			Bonjour,
Vous avez bien été abonné à l'actu $site_name.
Confirmez votre inscription en cliquant sur ce lien (expire après 24h) :
".SITE_URL."/nlmod.php?id=$hash
Vous pouvez, avec ce même lien, modifier les paramètres de votre abonnement ou vous désinscrire. Vous serez automatiquement désinscrit un an après la dernière fois que vous visitez ce lien.
Ce mail a été envoyé automatiquement, merci de ne pas répondre.
Cordialement,
L'équipe $site_name";

			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = SMTP_HOST;
			$mail->Port = SMTP_PORT;
			$mail->SMTPAuth = true;
			$mail->Username = SMTP_USERNAME;
			$mail->Password = SMTP_PSW;
			$mail->setFrom(SMTP_MAIL, SMTP_NAME);
			$mail->addReplyTo(SMTP_MAIL, SMTP_NAME);
			$mail->addAddress($_POST['mail']);
			$mail->Subject = 'Confirmation de l\'inscription à l\'actu '.$site_name;
			$mail->CharSet = 'UTF-8';
			$mail->IsHTML(TRUE);
			$mail->Body = $message;
			$mail->AltBody = $msgtext;
			if($mail->send()) {
				$req = $bdd->prepare('INSERT INTO `newsletter_mails` (`hash`, `mail`, `expire`, `freq`, `freq_n`, `notif_site`, `notif_upd`, `notif_upd_n`, `confirm`, `lang`, `lastmail`, `lastmail_n`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?)');
				$req->execute(array($hash, $_POST['mail'], time()+86400, $_POST['freq'], $_POST['freq_n'],  $f_site, $f_upd, $f_upd_n, $lang, time(), time()));

				$log .= 'Vous êtes bien inscrit à l\'actu'.$site_name.'.<br>Veuillez cliquer sur le lien valable 24 heures envoyé à '.$_POST['mail'].' pour confirmer votre inscription.<br>Le mail peut mettre quelques minutes à arriver. Si vous ne le recevez toujours pas, vérifiez dans les indésirables.';
			} else
				$log .= 'Erreur pendant l\'envoi du mail.';
		}
	}
}
if(isset($_GET['stop']))
	$log .= 'Vous avez bien été désinscrit de l\'actu '.$site_name.'. Un mail vous a été envoyé pour confirmer. Vous ne recevrez plus aucun mail de notre part.';

$title='L\'actu '.$site_name;
$sound_path='/audio/page_sounds/nl.mp3'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?php if(!empty($log)) echo '<p autofocus><b>'.$log.'</b></p>'; ?>
<p>Inscrivez-vous à l'actu <?php print $site_name; ?> pour connaître toutes les nouveautés et maintenir vos logiciels à jour! Vous pouvez choisir d'être notifié à chaque mise à jour d'un logiciel.<br>
Veuillez noter que le mail de l'actu <?php print $site_name; ?> est envoyé automatiquement, sans aucune intervention de la part de l'équipe, à 19:50.</p>
<form action="?a=s" method="post">
	<label for="f_mail">Adresse e-mail&nbsp;:</label>
	<input type="email" name="mail" id="f_mail" maxlength="255" required><br>
	<fieldset><legend>Actu <?php print $site_name; ?></legend>
	<label for="f_freq">Recevoir un mail&nbsp;:</label>
	<select name="freq" id="f_freq"><option value="1">Quotidiennement</option><option value="2">Tous les 2 jours</option><option value="3" selected>Hebdomadairement</option><option value="4">Quinzomadairement</option><option value="5">Mensuellement</option></select><br>
	<label for="f_notif_site">Me notifier d'une mise à jour du site&nbsp;:</label>
	<input type="checkbox" name="notif_site" id="f_notif_site"><br>
	<label for="f_notif_up">Me notifier de la mise à jour d'un article&nbsp;:</label>
	<select name="notif_up" id="f_notif_up"><option value="on" selected>Oui</option><option value="off">Non</option></select><br>
	</fieldset>
	<fieldset><legend>Actu NVDA-FR</legend>
		<label for="f_freq_n">Recevoir un mail&nbsp;:</label>
	<select name="freq_n" id="f_freq_n"><option value="1">Quotidiennement</option><option value="2">Tous les 2 jours</option><option value="3" selected>Hebdomadairement</option><option value="4">Quinzomadairement</option><option value="5">Mensuellement</option></select><br>
	<label for="f_notif_up_n">Me notifier de la mise à jour d'un article&nbsp;:</label>
	<select name="notif_up_n" id="f_notif_up_n"><option value="on" selected>Oui</option><option value="off">Non</option></select><br>
	</fieldset>
	<p>Votre adresse e-mail ainsi que toutes vos informations personnelles ne seront pas partagées avec des tiers. Cet abonnement peut être annulé à tout moment. Il sera automatiquement annulé au bout d'un an si vous ne le renouvelez pas (la date d'expiration est affichée en bas de chaque mail).</p>
	<input type="submit" value="S'abonner">
</form>
</main>
<?php require_once('include/footer.php'); ?> 
</body>
</html>