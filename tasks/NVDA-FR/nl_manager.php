<?php
/*if(!isset($simulate) and !isset($debug)) {
	$nbrsc=rand(0, 540);
	sleep($nbrsc);
}*/
/* Ce programme envoie automatiquement la newsletter et nettoye la table. */
$atime = microtime(true);
$noct = true;

$document_root = __DIR__.'/../..';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once($document_root.'/inclus/lib/PHPMailer/src/PHPMailer.php');

require_once($document_root.'/inclus/lib/PHPMailer/src/Exception.php');
require_once($document_root.'/inclus/lib/PHPMailer/src/SMTP.php');
require_once('consts.php');
require_once('smtp.php');

if(isset($simulate))
	echo "--simulate--\n";

$datejour = strftime('%d/%m/%Y');
$hrjr = strftime('%H:%M');

# Nettoyage de la table
$req = $bdd2->prepare('DELETE FROM `newsletter_mails` WHERE `expire`<?');
$req->execute(array(time()));

# Envoi des mails d'avertissement de fin d'abonnement
if(isset($debug)) {
	$req = $bdd2->prepare('SELECT * FROM `newsletter_mails` WHERE `confirm`=1 AND `expire`<=? AND `mail`=?');
	$req->execute(array(time()+172800, $debug));
	echo "--debug--\n";
}
else
{
	$req = $bdd2->prepare('SELECT * FROM `newsletter_mails` WHERE `confirm`=1 AND `expire`<=?');
	$req->execute(array(time()+172800));
	echo "--prod--\n";
}
while($data = $req->fetch()) {
	if(!isset($simulate)) {
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = $smtp_host;
		$mail->Port = $smtp_port;
		$mail->SMTPAuth = true;
		$mail->Username = $smtp_username;
		$mail->Password = $smtp_psw;
		$mail->setFrom('no_reply@nvda-fr.org', $nomdusite);
		$mail->addReplyTo('no_reply@nvda-fr.org', $nomdusite);
		$mail->addAddress($data['mail']);
		$mail->Subject = $nomdusite.' : votre abonnement à l\'actu '.$nomdusite.' expire bientôt';
		$mail->CharSet = 'UTF-8';
		$mail->IsHTML(false);
		$mail->Body = 'Bonjour '.$data['mail'].",\n\nVotre abonnement à l'actu '.$nomdusite.' expire le ".date('d/m/Y à H:i', $data['expire']).".\nCliquez sur le lien suivant pour le renouveler :\nhttps://www.progaccess.net/nlmod.php?id=".$data['hash']."\n\nCordialement,\n".$nomdusite;
		$mail->send();
	}
	echo $data['mail'];
}

# Sélection des mails
$r = '(freq_n=1';
if(localtime()[3] == 1)# premier jour du mois
	$r .= ' OR freq_n=5';
if(localtime()[6] == 1 and intval(date('W'))%2 == 0)# lundi et semaine paire
	$r .= ' OR freq_n=4';
if(localtime()[6] == 1)# lundi
	$r .= ' OR freq_n=3';
if(localtime()[7]%2 == 0)# jour pair sur l'année
	$r .= ' OR freq_n=2';
$r .= ')';

# Lister les catégories
$cat = array();
$req = $bdd->query('SELECT * FROM `softwares_categories`');
while($data = $req->fetch()) {$cat[$data['id']] = $data['name'];}

# Prendre des infos à envoyer
$sft = array();
$req = $bdd->prepare('SELECT * FROM `softwares` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-2678400));# récents d'au plus un mois
while($data = $req->fetch()) {
	if(!isset($sft[$data['id']]))
		$sft[$data['id']] = array('category'=>$data['category'], 'hits'=>$data['hits'], 'date'=>$data['date'], 'author'=>$data['author'], 'name'=>$data['name'], 'description'=>$data['description']);
}

$req = $bdd->prepare('SELECT * FROM `softwares_files` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-2678400));# récents d'au plus un mois
$files = array();
while($data = $req->fetch()) {
	$files[] = $data;
}

$subject = '📰 L\'actu '.$nomdusite.' du '.$datejour;
$message1 = '<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>'.$subject.'</title>
		<style type="text/css">
@font-face {font-family: Cantarell;src: url(https://progaccess.net/css/Cantarell-Regular.otf);}
html, body {margin: 0;padding: 0;font-family: Cantarell;}
.software {border-left: 2px dashed black;padding-left: 10px;}
.software_title {margin-bottom: -8px;}
.software_date {color: #606060;margin-left: 15px;}
.software_hits, .software_category {color: #008000;}
</style>
	</head>
	<body>
		<div id="header">
					<h1>'.$subject.'</h1>
			<img id="logo" alt="Logo de '.$nomdusite.'" src="https://www.nvda-fr.org/images/nvda_logo.png" />
		</div>
		<div id="content">
		<h2>Bonjour {{mail_user}},</h2>';
$message2 = '<hr /><p role="contentinfo" aria-label="Informations sur l\'abonnement">Votre abonnement expire le ';
$message3 = ', <a id="link" href="https://www.progaccess.net/nlmod.php?id=';
$message4 = '">cliquez ici pour le renouveler avant cette date</a>.</p>
			<p>Veuillez ne pas répondre, ce mail a été envoyé automatiquement, vous pouvez <a href="https://www.nvda-fr.org/inf.php">nous contacter ici</a></p>
			<p>Cordialement.<br />'.$nomdusite.'</p>
		</div>
	</body>
</html>';
$msgtxt1 = 'L\'actu '.$nomdusite.' du '.$datejour." (version texte)\nBonjour {{mail_user}},\n\n";
$msgtxt2 = 'Allez à l\'adresse ci-dessous pour gérer votre abonnement (à toute fin utile votre numéro d\'abonné est N{{idabonne}}). Vous serez automatiquement désinscrit de l\'actu le ';
$msgtxt3 = ".\nhttps://www.progaccess.net/nlmod.php?id=";
$msgtxt4 = "\n\nVeuillez ne pas répondre, ce mail a été envoyé automatiquement, cependant, vous pouvez nous contacter via notre formulaire de contact.\n\nCordialement.\n".$nomdusite;

# Envoi des mails
if(isset($debug)) {
	$req = $bdd2->prepare('SELECT * FROM `newsletter_mails` WHERE `confirm`=1 AND `mail`=?');
	$req->execute(array($debug));
	echo "--debug--\n";
}
else
{
	$req = $bdd2->prepare('SELECT * FROM `newsletter_mails` WHERE `confirm`=1 AND `notif_upd_n`=1 AND '.$r);
	$req->execute();
	echo "--prod--\n";
}
$nba = 0;
$nbt = 0;
$nbk = 0;
while($data = $req->fetch()) {
	$nba ++;
	$message = '';
	$msgtxt = '';
	$nbs = 0;# number of updated articles
	$nbf = 0;# number of updated files
	foreach($sft as $sw_id => $software) {
		if($software['date'] > $data['lastmail']) {
			$nbs ++;
			$message .= '<div class="software"><h3 class="software_title"><a href="https://www.nvda-fr.org/article.php?id='.$sw_id.'">'.$software['name'].'</a> (<a href="https://www.nvda-fr.org/cat.php?id='.$software['category'].'">'.$cat[$software['category']].'</a>)</h3><p>'.str_replace('{{site}}', $nomdusite, $software['description']).'<br /><span class="software_date">Mis à jour à '.date('H:i', $software['date']).' le '.date('d/m/Y', $software['date']).' par '.$software['author'].'</span><span class="software_hits">, '.$software['hits'].' visites</span></p><ul>';
			$msgtxt .= ' * '.$software['name'].' ('.$cat[$software['category']].") :\n".$software['description'].' ('.$software['hits'].' visites, mis à jour par '.$software['author'].' le '.date('d/m/Y à H:i', $software['date']).")\n";
			foreach($files as $file) {
				if($file['sw_id'] == $sw_id and $file['date'] > $data['lastmail']) {
					$nbf ++;
					$message .= '<li><a href="https://www.nvda-fr.org/r.php?id='.$file['id'].'">'.$file['title'].' (téléchargé '.$file['hits'].' fois)</a></li>';
					$msgtxt .= ' - '.$file['title'].', https://www.nvda-fr.org/r.php?id='.$file['id'].' ('.$file['hits']." téléchargements)\n";
				}
			}
			unset($file);
			$message .= '</ul></div>';
			$msgtxt .= "\n";
		}
	}
	unset($software);
	$message = $message1 . '<p>Depuis le '.date('d/m/Y', $data['lastmail']).', <strong>'.$nbs.'</strong> articles et <strong>'.$nbf.'</strong> fichiers ont été mis à jour.</p>' . $message;
	$msgtxt = $msgtxt1 . 'Depuis le '.date('d/m/Y', $data['lastmail']).", nous avons modifiés $nbs articles et $nbf fichiers.\n\n" . $msgtxt;
	echo $data['mail'];
	if($nbs > 0 or $nbf > 0) {
		echo ' send';
		$message .= $message2.date('d/m/Y, H:i', $data['expire']).$message3.$data['hash'].$message4;
		$msgtxt .= $msgtxt2.date('d/m/Y à H:i', $data['expire']).$msgtxt3.$data['hash'].$msgtxt4;
		
		$message = str_replace('{{mail}}', $data['mail'], str_replace('{{mail_user}}', ucfirst(explode('@', $data['mail'])[0]), str_replace('{{idabonne}}', $data['id'], $message)));
		$msgtxt = str_replace('{{mail}}', $data['mail'], str_replace('{{mail_user}}', ucfirst(explode('@', $data['mail'])[0]), str_replace('{{idabonne}}', $data['id'], $msgtxt)));
		$message = str_replace('{{site}}', $nomdusite, $message);
		$msgtxt = str_replace('{{site}}', $nomdusite, $msgtxt);
		
		if(isset($debug)) {
			print('<p>'.$msgtxt.'</p>');
		}
		
		if(!isset($simulate)) {
			$mail = new PHPMailer;
			$mail->isSMTP();
			$mail->Host = $smtp_host;
			$mail->Port = $smtp_port;
			$mail->SMTPAuth = true;
			$mail->Username = $smtp_username;
			$mail->Password = $smtp_psw;
			$mail->setFrom('no_reply@nvda-fr.org', $nomdusite);
			$mail->addReplyTo('no_reply@nvda-fr.org', $nomdusite);
			$mail->addAddress($data['mail']);
			$mail->Subject = $subject;
			$mail->CharSet = 'UTF-8';
			$mail->IsHTML(TRUE);
			$mail->Body = $message;
			$mail->AltBody = $msgtxt;
			$nbt ++;
			
			if($mail->send()) {
				echo ' OK';
				$req2 = $bdd2->prepare('UPDATE `newsletter_mails` SET `lastmail`=? WHERE id=? LIMIT 1');
				$req2->execute(array(time(), $data['id']));
				$nbk ++;
			}
			else
				echo ' Error:' . $mail->ErrorInfo;
		}
		echo "\n";
	}
}
$btime = microtime(true)-$atime;
echo $nba.' abonnés, '.$nbt.' envois, '.$nbk.' OK, '.$btime."s\n";
if($nbk > 0) {
		$message = "📤 Mail d'actu envoyé :\n-*".(intval($btime*1000)/1000)." secondes ;\n-*".$nbt." inscrits !\nConsultez vos mails 📥";
echo $message;
}
?>
