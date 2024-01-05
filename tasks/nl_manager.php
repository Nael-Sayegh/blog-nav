<?php
/*if(!isset($simulate) and !isset($debug)) {
	$nbrsc=rand(0, 540);
	sleep($nbrsc);
}*/
/* Ce programme envoie automatiquement la newsletter et nettoye la table. */
$atime = microtime(true);
$noct = true;

$document_root = __DIR__.'/..';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once($document_root.'/include/lib/phpmailer/src/PHPMailer.php');
require_once($document_root.'/include/lib/phpmailer/src/Exception.php');
require_once($document_root.'/include/lib/phpmailer/src/SMTP.php');
require_once($document_root.'/include/config.local.php');
require_once($document_root.'/include/consts.php');

if(isset($simulate))
	echo "--simulate--\n";

$datejour = getFormattedDate(time(), tr($tr0,'fndate'));
$hrjr = getFormattedDate(time(), tr($tr0,'ftime'));

# Nettoyage de la table
$req = $bdd->prepare('DELETE FROM `newsletter_mails` WHERE `expire`<?');
$req->execute(array(time()));

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
	if(!isset($simulate)) {
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
		$mail->Subject = $site_name.' : votre abonnement à l\'actu '.$site_name.' expire bientôt';
		$mail->CharSet = 'UTF-8';
		$mail->IsHTML(false);
		$mail->Body = 'Bonjour '.$data['mail'].",\n\nVotre abonnement à l'actu ProgAccess expire le ".date('d/m/Y à H:i', $data['expire']).".\nCliquez sur le lien suivant pour le renouveler :\n".SITE_URL."/nlmod.php?id=".$data['hash']."\n\nCordialement,\n".$site_name;
		$mail->send();
	}
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
/*$req = $bdd->prepare('SELECT * FROM `softwares` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-2678400));# récents d'au plus un mois
$sft = array();
while($data = $req->fetch()) {
	$req2 = $bdd->prepare('SELECT `name`,`description` FROM `softwares_tr` WHERE `sw_id`=? AND `lang`="fr" LIMIT 1');
	$req2->execute();
	if($data2 = $req2->fetch()) {
		$data['name'] = $data2['name'];
		$data['description'] = $data2['description'];
	}
	$sft[] = $data;
}*/

$sft = array();
$req = $bdd->prepare('
	SELECT `softwares_tr`.`lang`, `softwares_tr`.`name`, `softwares_tr`.`description`, `softwares_tr`.`sw_id`, `softwares`.`hits`, `softwares`.`date`, `softwares`.`author`, `softwares`.`category`
	FROM `softwares`
	LEFT JOIN `softwares_tr` ON `softwares`.`id`=`softwares_tr`.`sw_id`
	WHERE `softwares`.`date`>=?
	ORDER BY `softwares`.`date` DESC');
$req->execute(array(time()-2678400));# récents d'au plus un mois
while($data = $req->fetch()) {
	if(!isset($sft[$data['sw_id']]))
		$sft[$data['sw_id']] = array('category'=>$data['category'], 'hits'=>$data['hits'], 'date'=>$data['date'], 'author'=>$data['author'], 'trs'=>array());
	$sft[$data['sw_id']]['trs'][$data['lang']] = array('name'=>$data['name'], 'description'=>$data['description']);
}

$req = $bdd->prepare('SELECT * FROM `softwares_files` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-2678400));# récents d'au plus un mois
$files = array();
while($data = $req->fetch()) {
	$files[] = $data;
}

$maj_name = '';
$maj_text = '';
$maj_author = '';
$maj_date = 0;
$req = $bdd->prepare('SELECT * FROM `site_updates` ORDER BY `date` DESC LIMIT 1');
$req->execute();
if($data = $req->fetch()) {
	$maj_id = 'V'.$data['id'];
	$maj_name = substr($data['name'],1);
	$maj_text = $data['text'];
	$maj_author = $data['authors'];
	$maj_date = $data['date'];
}
$subject = '📰 L\'actu '.$site_name.' du '.$datejour;
$message1 = '<!DOCTYPE html>
<html lang="{{lang}}">
	<head>
		<meta charset="utf-8">
		<title>'.$subject.'</title>
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
					<h1>'.$subject.'</h1>
			<img id="logo" alt="Logo de '.$site_name.'" src="'.SITE_URL.'/image/logo128-170.png">
		</div>
		<div id="content">
		<h2>Bonjour {{mail_user}},</h2>';
$message2 = '<h2>Pétition pour des transports plus accessibles à Bordeaux</h2><p>Corentin, fondateur de '.$site_name.' et habitant de Bordeaux, a lancé une pétition pour améliorer l\'accessibilité du réseau de transports bordelais, particulièrement en ce qui concerne les tramways. Pour soutenir cette action, signez et partagez <a href="https://www.change.org/p/tbm-pour-l-annonce-syst%C3%A9matique-de-la-destination-d-un-tram">la pétition adressée à TBM et Bordeaux Métropole</a>.<br>Cette pétition a également fait l\'objet d\'une médiatisation dans le journal Sud Ouest, vous pouvez lire l\'<a href="https://www.sudouest.fr/sante/handicap/tramway-de-bordeaux-les-personnes-deficientes-visuelles-se-font-entendre-17962342.php">article du Sud Ouest ici</a>.<br>Merci pour votre soutien.</p><hr><div  role="contentinfo" aria-label="Informations sur l\'abonnement"><p>Vous recevez l\'actu '.$site_name.' car vous vous y êtes inscrit jusqu\'au ';
$message3 = ', <a id="link" href="'.SITE_URL.'/nlmod.php?id=';
$message4 = '">cliquez ici pour modifier vos préférences ou vous désinscrire</a>.</p>
			<p>Veuillez ne pas répondre, ce mail a été envoyé automatiquement, vous pouvez <a href="'.SITE_URL.'/contact.php">nous contacter ici</a></p>
			<p>Cordialement.<br>'.$site_name.'</p></div>
		</div>
	</body>
</html>';
$msgtxt1 = 'L\'actu '.$site_name.' du '.$datejour." (version texte)\nBonjour {{mail_user}},\nRetrouvez l'historique des mises à jour sur ".SITE_URL."/history.php\n\n";
$msgtxt2 = 'Allez à l\'adresse ci-dessous pour gérer votre abonnement (à toute fin utile votre numéro d\'abonné est N{{idabonne}}). Vous serez automatiquement désinscrit de l\'actu le ';
$msgtxt3 = ".\n".SITE_URL."/nlmod.php?id=";
$msgtxt4 = "\n\nVeuillez ne pas répondre, ce mail a été envoyé automatiquement, cependant, vous pouvez nous contacter via notre formulaire de contact.\n\nCordialement.\n".$site_name;

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
			$entry_tr = '';
			if(array_key_exists($data['lang'], $software['trs']))
				$entry_tr = $data['lang'];
			else {
				foreach($langs_prio as &$i_lang) {
					if(array_key_exists($i_lang, $software['trs'])) {
						$entry_tr = $i_lang;
						break;
					}
				}
			}
			unset($i_lang);
			if(empty($entry_tr))// Error: sw has no translations
				continue;
			
			$nbs ++;
			$message .= '<div class="software"><h3 class="software_title"><a href="'.SITE_URL.'/a'.$sw_id.'">'.$software['trs'][$entry_tr]['name'].'</a> (<a href="'.SITE_URL.'/c'.$software['category'].'">'.$cat[$software['category']].'</a>)</h3><p>'.str_replace('{{site}}', $site_name, $software['trs'][$entry_tr]['description']).'<br><span class="software_date">Mis à jour à '.date('H:i', $software['date']).' le '.date('d/m/Y', $software['date']).' par '.$software['author'].'</span><span class="software_hits">, '.$software['hits'].' visites</span></p><ul>';
			$msgtxt .= ' * '.$software['trs'][$entry_tr]['name'].' ('.$cat[$software['category']].") :\n".$software['trs'][$entry_tr]['description'].' ('.$software['hits'].' visites, mis à jour par '.$software['author'].' le '.date('d/m/Y à H:i', $software['date']).")\n";
			foreach($files as $file) {
				if($file['sw_id'] == $sw_id and $file['date'] > $data['lastmail']) {
					$nbf ++;
					$message .= '<li><a href="'.SITE_URL.'/dl/'.$file['id'].'">'.$file['title'].' (téléchargé '.$file['hits'].' fois)</a></li>';
					$msgtxt .= ' - '.$file['title'].', '.SITE_URL.'/dl/'.$file['id'].' ('.$file['hits']." téléchargements)\n";
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
		if($data['notif_site'] == 1 and $data['lastmail'] < $maj_date) {
			$message .= '<h2>'.$site_name.' version '.$maj_name.' : '.$maj_id.' ('.$maj_author.')</h2><p>'.$maj_text.'</p>';
			$msgtxt .= 'Mise à jour du site : '.$site_name.' version '.$maj_name.' ('.$maj_id.')'."\n".strip_tags(html_entity_decode($maj_text))."\n\n"; 
		}
		$message .= $message2.date('d/m/Y, H:i', $data['expire']).$message3.$data['hash'].$message4;
		$msgtxt .= $msgtxt2.date('d/m/Y à H:i', $data['expire']).$msgtxt3.$data['hash'].$msgtxt4;
		
		$message = str_replace('{{lang}}', $data['lang'], $message);
		$message = str_replace('{{mail}}', $data['mail'], str_replace('{{mail_user}}', ucfirst(explode('@', $data['mail'])[0]), str_replace('{{idabonne}}', $data['id'], $message)));
		$msgtxt = str_replace('{{mail}}', $data['mail'], str_replace('{{mail_user}}', ucfirst(explode('@', $data['mail'])[0]), str_replace('{{idabonne}}', $data['id'], $msgtxt)));
		$message = str_replace('{{site}}', $site_name, $message);
		$msgtxt = str_replace('{{site}}', $site_name, $msgtxt);
		
		if(isset($debug)) {
			print('<p>'.$msgtxt.'</p>');
		}
		
		if(!isset($simulate)) {
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
			$mail->Subject = $subject;
			$mail->CharSet = 'UTF-8';
			$mail->IsHTML(TRUE);
			$mail->Body = $message;
			$mail->AltBody = $msgtxt;
			$nbt ++;
			
			if($mail->send()) {
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
}
$btime = microtime(true)-$atime;
echo $nba.' abonnés, '.$nbt.' envois, '.$nbk.' OK, '.$btime."s\n";
if($nbk > 0) {
		$message = "📤 Mail d'actu envoyé :\n-*".(intval($btime*1000)/1000)." secondes ;\n-*".$nbt." inscrits !\nConsultez vos mails 📥";
echo $message;
}
?>
