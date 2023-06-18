<?php
/* Ce programme envoie automatiquement la newsletter et nettoye la table. */
$atime = microtime(true);
$noct = true;

$document_root = __DIR__.'/..';
require_once($document_root.'/include/consts.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once($document_root.'/include/lib/phpmailer/src/PHPMailer.php');
require_once($document_root.'/include/lib/phpmailer/src/Exception.php');
require_once($document_root.'/include/lib/phpmailer/src/SMTP.php');

$datejour = getFormattedDate(time(), tr($tr0,'fndatetime'));

# Netoyage de la table
$req = $bdd->prepare('DELETE FROM `notifs` WHERE `date`<?');
$req->execute(array(time()-2678400));

# Envoi des mails d'avertissement de fin d'abonnement
if(isset($debug)) {
	$req = $bdd->prepare('SELECT * FROM `newsletter_mails` WHERE `confirm`=1 AND `expire`<=? AND `mail`=?');
	$req->execute(array(time()+172800, $debug));
	echo "--debug--\n";
}
else
{
	$req = $bdd->prepare('SELECT * FROM `newsletter_mails` WHERE `confirm`=1 AND `expire`<=?');
	$req->execute(array(time()+172800));
	echo "--prod--\n";
}
while($data = $req->fetch()) {
	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = SMTP_HOST;
	$mail->Port = SMTP_PORT;
	$mail->SMTPAuth = true;
	$mail->Username = SMTP_USERNAME;
	$mail->Password = SMTP_PSW;
	$mail->setFrom(SMTP_MAIL, SMTP_NAME);
	$mail->addReplyTo(SMTP_MAIL, SMTP_NAME);
	$mail->addAddress($data['mail']);
	$mail->Subject = $site_name.' : votre abonnement à la lettre d\'informations expire bientôt';
	$mail->CharSet = 'UTF-8';
	$mail->IsHTML(false);
	$mail->Body = 'Bonjour '.$data['mail'].",\n\nVotre abonnement à la lettre d'informations de ProgAccess expire le ".date('d/m/Y à H:i').".\nCliquez sur le lien suivant pour renouveler votre abonnement :\n".SITE_URL."/nlmod.php?id=".$data['hash']."\n\nCordialement,\nAdministration ".$site_name;
	$mail->send();
	echo $data['mail'];
}

# Sélection des mails
$r = '(freq=1';
if(localtime()[3] == 1)# premier jour du mois
	$r .= ' OR freq=5';
if(localtime()[6] == 1 and intval(date('W'))%2 == 0)# lundi et semaine paire
	$r .= ' OR freq=4';
if(localtime()[6] == 1)# lundi
	$r .= ' OR freq=3';
if(localtime()[7]%2 == 0)# jour pair sur l'année
	$r .= ' OR freq=2';
$r .= ')';

# Lister les catégories
$cat = array();
$req = $bdd->query('SELECT * FROM `softwares_categories`');
while($data = $req->fetch()) {$cat[$data['id']] = $data['name'];}

# Prendre des infos à envoyer
$req = $bdd->prepare('SELECT * FROM `softwares` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-2678400));# récents d'au plus un mois
$sft = array();
while($data = $req->fetch()) {
	$sft[] = $data;
}

$req = $bdd->prepare('SELECT * FROM `softwares_files` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-2678400));# récents d'au plus un mois
$files = array();
while($data = $req->fetch()) {
	$files[] = $data;
}

$maj_name = '';
$maj_text = '';
$maj_date = 0;
$req = $bdd->prepare('SELECT * FROM `site_updates` ORDER BY `date` DESC LIMIT 1');
$req->execute();
if($data = $req->fetch()) {
	$maj_id = 'V'.$data['id'];
	$maj_name = substr($data['name'],1);
	$maj_text = $data['text'];
	$maj_date = $data['date'];
}

$message1 = '<!DOCTYPE html>
<html lang="{{lang}}">
	<head>
		<meta charset="utf-8">
		<title>Lettre d\'informations '.$site_name.'</title>
		<style type="text/css">
@font-face {font-family: Cantarell;src: url('.SITE_URL.'/css/Cantarell-Regular.otf);}
html, body {margin: 0;padding: 0;font-family: Cantarell;}
.software {border-left: 2px dashed black;padding-left: 10px;}
.software_title {margin-bottom: -8px;}
.software_date {color: #606060;margin-left: 15px;}
.software_hits, .software_category {color: #008000;}
</style>
	</head>
	<body>
		<div id="header">
					<h1>Lettre d\'informations '.$site_name.'</h1>
			<img id="logo" alt="Logo" src="'.SITE_URL.'/image/logo128.png">
		</div>
		<div id="content">
		<h2>Bonjour {{mail}},</h2>
			<h2>Depuis le dernier mail&nbsp;...</h2>';
$message2 = '<a id="link" href="'.SITE_URL.'/nlmod.php?id=';
$message3 = '">Cliquez ici pour modifier votre abonnement, le renouveler ou vous désinscrire.</a>
			<p>Votre abonnement expire le ';
$message4 = '.</p>
			<p>Merci de ne pas répondre, ceci est un mail automatique.</p>
			<p>Cordialement.<br>L\'Administration '.$site_name.'</p>
		</div>
	</body>
</html>';
$msgtxt1 = 'Lettre d\'informations '.$site_name." (version texte)\nBonjour {{mail}},\nRetrouvez l'historique des mises à jour sur ".SITE_URL."/history.php\n\nDepuis le dernier mail ...\n\n";
$msgtxt2 = 'Allez à l\'adresse ci-dessous pour modifier votre abonnement, le renouveler ou vous désinscrire. Vous serez automatiquement désinscrit le ';
$msgtxt3 = ".\n".SITE_URL."/nlmod.php?id=";
$msgtxt4 = "\n\nMerci de ne pas répondre, ceci est un mail automatique.\n\nCordialement.\nL'Administration ".$site_name;

$subject = $site_name.' : lettre d\'informations du '.$datejour;

# Envoi des mails
if(isset($debug)) {
	$req = $bdd->prepare('SELECT * FROM `newsletter_mails` WHERE `confirm`=1 AND `mail`=?');
	$req->execute(array($debug));
	echo "--debug--\n";
}
else
{
	$req = $bdd->prepare('SELECT * FROM `newsletter_mails` WHERE `confirm`=1 AND '.$r);
	$req->execute();
	echo "--prod--\n";
}
$sent = false;
$nba = 0;
$nbs = 0;
$nbk = 0;
while($data = $req->fetch()) {
	$nba ++;
	$message = $message1;
	$msgtxt = $msgtxt1;
	foreach($sft as $software) {
		if($software['date'] > $data['lastmail']) {
			$message .= '<div class="software"><h3 class="software_title"><a href="'.SITE_URL.'/a'.$software['id'].'">'.$software['name'].'</a> (<a href="'.SITE_URL.'/c'.$software['category'].'">'.$cat[$software['category']].'</a>)</h3><p>'.str_replace('{{site}}', $site_name, $software['description']).'<br><span class="software_hits">'.$software['hits'].' visites</span><span class="software_date"> (mis à jour par '.$software['author'].' le '.date('d/m/Y à H:i', $software['date']).')</span></p><ul>';
			$msgtxt .= ' * '.$software['name'].' ('.$cat[$software['category']].') :\n'.$software['description'].' ('.$software['hits'].' visites, mis à jour par '.$software['category'].' le '.date('d/m/Y à H:i', $software['date']).")\n";
			foreach($files as $file) {
				if($file['sw_id'] == $software['id'] and $file['date'] > $data['lastmail']) {
					$message .= '<li><a href="'.SITE_URL.'/r?id='.$file['id'].'">'.$file['title'].' ('.$file['hits'].' téléchargements)</a></li>';
					$msgtxt .= ' - '.$file['title'].', '.SITE_URL.'/r?id='.$file['id'].' ('.$file['hits']." téléchargements)\n";
				}
			}
			$message .= '</ul></div>';
			$msgtxt .= "\n";
		}
	}
	echo $data['mail'];
	if($message != $message1) {
		echo ' send';
		if($data['notif_site'] and $data['lastmail'] < $maj_date) {
			$message .= '<h2>Mise à jour du site&nbsp;: '.$site_name.' '.$maj_name.' ('.$maj_id.')</h2><p>'.$maj_text.'</p>';
			$msgtxt .= 'Mise à jour du site : '.$site_name.' '.$maj_name.' ('.$maj_id.')'."\n".strip_tags(html_entity_decode($maj_text))."\n\n"; 
		}
		$message .= $message2.$data['hash'].$message3.date('d/m/Y à H:i', $data['expire']).$message4;
		$msgtxt .= $msgtxt2.date('d/m/Y à H:i', $data['expire']).$msgtxt3.$data['hash'].$msgtxt4;
		
		$message = str_replace('{{lang}}', $data['lang'], $message
		$message = str_replace('{{mail}}', $data['mail'], $message);
		$msgtxt = str_replace('{{mail}}', $data['mail'], $msgtxt);
		$message = str_replace('{{site}}', $site_name, $message);
		$msgtxt = str_replace('{{site}}', $site_name, $msgtxt);
		
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = SMTP_HOST;
		$mail->Port = SMTP_PORT;
		$mail->SMTPAuth = true;
		$mail->Username = SMTP_USERNAME;
		$mail->Password = SMTP_PSW;
		$mail->setFrom(SMTP_MAIL', SMTP_NAME);
		$mail->addReplyTo(SMTP_MAIL, SMTP_NAME);
		$mail->addAddress($data['mail']);
		$mail->Subject = $subject;
		$mail->CharSet = 'UTF-8';
		$mail->IsHTML(TRUE);
		$mail->Body = $message;
		$mail->AltBody = $msgtxt;
		$nbs ++;
		if($mail->send()) {
			$sent = true;
			echo ' OK';
			$req2 = $bdd->prepare('UPDATE `newsletter_mails` SET `lastmail`=? WHERE id=? LIMIT 1');
			$req2->execute(array(time(), $data['id']));
			$nbk ++;
		}
		else
		    echo ' Error:' . $mail->ErrorInfo;
	}
	echo "\n";
}
$req->closeCursor();
$btime = microtime(true)-$atime;
echo $nba.' abonnés, '.$nbs.' envois, '.$nbk.' OK, '.$btime."s\n";
if($sent) {
	if(getFormattedDate((time(), 'c') == '5' or getFormattedDate(time(), 'c') == '6') {
		$message = 'Envoi de la Lettre d\'infos à '.$nbs.' abonnés en '.(intval($btime*1000)/1000).' secondes !'."\n".'Fin de la maintenance du jour, reprise éventuelle demain (13:00/22:10).'."\n".'L\'administration';
	} else {
		$message = 'Envoi de la Lettre d\'infos à '.$nbs.' abonnés en '.(intval($btime*1000)/1000).' secondes !'."\n".'Fin de la maintenance du jour, reprise éventuelle demain (06:30/12:00 et 13:00/22:10).'."\n".'L\'administration';
	}
echo $message;
}
?>
