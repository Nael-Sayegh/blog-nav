<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once 'inclus/lib/PHPMailer/src/PHPMailer.php';
require_once 'inclus/lib/PHPMailer/src/Exception.php';
require_once 'inclus/lib/PHPMailer/src/SMTP.php';
// require_once 'inclus/lib/PHPMailerLatest/vendor/autoload.php';
include_once 'inclus/log.php';
require_once 'inclus/consts.php';
$tr = load_tr($lang, 'contacter');
$titre='Contacter l\'équipe '.$nomdusite;
$cheminaudio='/audio/sons_des_pages/harp_notif.mp3';
$stats_page='contacter';

$log = '';
$reply = false;
if(isset($_GET['reply']) and isset($_GET['h'])) {
	$req = $bdd->prepare('SELECT * FROM `tickets` WHERE `id`=? AND `hash`=?');
	$req->execute(array($_GET['reply'], $_GET['h']));
	if($rdata = $req->fetch())
		$reply = true;
	else
		$log .= '<li>Le lien que vous avez suivi est invalide. Veuillez réessayer.<br />Si le problème persiste, vous pouvez envoyer un nouveau message en faisant référence à l\'ancien dans le texte.</li>';
}

if(isset($_GET['act']) and ($_GET['act'] == 'contact' or $_GET['act'] == 'reply')) {
	if(isset($_POST['agree']))
		exit();
	
	$reply2 = false;
	if($_GET['act'] == 'reply' and isset($_GET['id']) and isset($_GET['h'])) {
		$req = $bdd->prepare('SELECT * FROM `tickets` WHERE `id`=? AND `hash`=? LIMIT 1');
		$req->execute(array($_GET['id'], $_GET['h']));
		if($rdata2 = $req->fetch())
			$reply2 = true;
		else
			$log .= '<li>Le lien que vous avez suivi est invalide. Veuillez réessayer.<br />Si le problème persiste, vous pouvez envoyer un nouveau message en faisant référence à l\'ancien dans le texte.</li>';
	}
	
	if(!$reply2) {
		if(isset($_POST['name']) and !empty($_POST['name'])) {
			if(strlen($_POST['name']) > 255)
				$log .= '<li>Votre nom ne doit pas dépasser les 255 caractères.</li>';
		}
		else $log .= '<li>Veuillez renseigner votre nom ou un pseudonyme de votre choix.</li>';

		if(isset($_POST['mail']) and !empty($_POST['mail'])) {
			if(strlen($_POST['mail']) > 255)
				$log .= '<li>Votre adresse e-mail ne doit pas dépasser les 255 caractères.</li>';
		}
		else $log .= '<li>Veuillez renseigner une adresse e-mail valide (elle sera utilisée pour vous répondre).</li>';

		$obj = '';
		if(isset($_POST['obj'])) {
			if(empty($_POST['obj'])) {
				if(isset($_POST['objother']) and !empty($_POST['objother'])) {
					if(strlen($_POST['objother']) > 255)
						$log .= '<li>Le sujet du message ne doit pas dépasser les 255 caractères.</li>';
					else
						$obj = $_POST['objother'];
				}
				else $log .= '<li>Veuillez renseigner le sujet de votre message.</li>';
			}
			else {
				if(strlen($_POST['obj']) > 255)
					$log .= '<li>Le sujet du message ne doit pas dépasser les 255 caractères.</li>';
				else
					$obj = $_POST['obj'];
			}
		}
		else $log .= '<li>Veuillez renseigner le sujet de votre message.</li>';
	}
	
	if(isset($_POST['msg']) and strlen($_POST['msg']) > 10) {
		if(strlen($_POST['msg']) > 8192)
			$log .= '<li>Le message ne doit pas dépasser les 8192 caractères.</li>';
	}
	else $log .= '<li>Votre message serait certainement plus utile en comportant un nombre de lettres supérieur à 10.</li>';
	
	if(empty($log)) {
		$msg = str_replace("\n\n", '</p><p>', htmlspecialchars($_POST['msg']));
		$msg = '<p>'.str_replace("\n", '<br />', $msg).'</p>';
		$time = time();
		if($reply2) {
			$req = $bdd->prepare('UPDATE `tickets` SET `messages`=?, `status`=1, `date`=?, `lastadmreply`=?WHERE `id`=? LIMIT 1');
			$messages = json_decode($rdata2['messages'], true);
			$messages[] = ['e'=>$rdata2['expeditor_name'],'m'=>0,'d'=>$time, 't'=>$msg];
			$req->execute(array(json_encode($messages), $time, $rdata2['expeditor_name'], $rdata2['id']));
			$tickid = $rdata2['id'];
		}
		else {
			$req = $bdd->prepare('INSERT INTO `tickets` (`subject`,`expeditor_email`,`expeditor_name`,`messages`,`status`,`hash`,`date`,`lastadmreply`) VALUES (?,?,?,?,0,?,?,?)');
			$message = json_encode([['e'=>$_POST['name'],'m'=>0,'d'=>$time, 't'=>$msg]]);
			$hash = hash('sha512', strval(time()).strval(rand()).$_POST['name'].strval(rand()));
			$req->execute(array($obj, $_POST['mail'], $_POST['name'], $message, $hash, $time, $_POST['name']));
			$req2 = $bdd->prepare('SELECT id FROM tickets WHERE subject=? AND expeditor_email=? AND expeditor_name=? AND messages=? AND hash=? AND date=? AND lastadmreply=?');
			$req2->execute(array($obj, $_POST['mail'], $_POST['name'], $message, $hash, $time, $_POST['name']));
			if($data = $req2->fetch()) {
				$tickid = $data['id'];
			}
		}
		header('Location: /?contactconfirm=1');
		$body = '<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Formulaire de contact '.$nomdusite.'</title>
	</head>
	<body>
		<h1>'.$nomdusite.' - Ticket '.$tickid.'</h1>';
		if($reply2)
		{
			$body.='<p>Une réponse a été envoyée via le formulaire de contact de '.$nomdusite.'.</p>
			<h2>'.$rdata2['subject'].' (par '.$rdata2['expeditor_name'].')</h2>';
		}
		else
		{
			$body.='<p>Un message a été envoyé via le formulaire de contact de '.$nomdusite.'.</p>
			<h2>'.$_POST['obj'].' (par '.$_POST['name'].')</h2>';
		}
		$body.='<p>'.nl2br($_POST['msg']).'</p>';
		if($reply2)
		{
			$body.='<p><a href="https://www.progaccess.net/admin/tickets.php?ticket='.$rdata2['id'].'">Consulter le ticket pour continuer la discussion</a></p>';
		}
		else
		{
			$body.='<p><a href="https://www.progaccess.net/admin/tickets.php?ticket='.$tickid.'">Consulter le ticket pour y répondre</a></p>';
		}
	$body.='</body>
</html>';
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->Host = SMTP_HOST;
		$mail->Port = SMTP_PORT;
		$mail->SMTPAuth = true;
		$mail->Username = SMTP_USERNAME;
		$mail->Password = SMTP_PSW;
		$mail->setFrom('no_reply@progaccess.net', 'L\'administration '.$nomdusite);
		$mail->addReplyTo('no_reply@progaccess.net', 'Formulaire '.$nomdusite);
		$req = $bdd->prepare('SELECT * FROM `team` WHERE `works`="1" OR `works`="2"');
		$req->execute();
while($data = $req->fetch()) {
	$req2 = $bdd->prepare('SELECT * FROM `accounts` WHERE `id`=?');
	$req2->execute(array($data['account_id']));
	while($data2 = $req2->fetch()) {
		$mail->addAddress($data2['email']);
	}
}
if($reply2) {
	$mail->Subject = 'Re : ['.$nomdusite.'] : '.$rdata2['subject'];
}
else
{
	$mail->Subject = '['.$nomdusite.'] : '.$_POST['obj'];
}
		$mail->CharSet = 'UTF-8';
		$mail->isHTML(TRUE);
		$mail->Body = $body;
		$mail->AltBody = "Formulaire de contact $nomdusite \r\nUn message a été envoyé via le formulaire de contact de $nomdusite .\r\nConsulter les tickets à l'adresse suivante:\r\nhttps://www.progaccess.net/admin/tickets.php";
		$mail->send();
		if(isset($_POST['copy']))
		{
				$bodycopy = '<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Formulaire de contact '.$nomdusite.'</title>
	</head>
	<body>
		<h1>'.$nomdusite.' - Ticket '.$tickid.'</h1>
<p>Nous avons bien reçu votre message et allons bientôt y répondre. Veuillez trouver ci-dessous une copie de votre message.</p>';
if($reply2)
{
			$bodycopy.='<h2>'.$rdata2['subject'].' (par '.$rdata2['expeditor_name'].')</h2>';
		}
		else
		{
			$bodycopy.='<h2>'.$_POST['obj'].' (par '.$_POST['name'].')</h2>';
		}
		$bodycopy.='<p>'.nl2br($_POST['msg']).'</p>
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
		$mail->addReplyTo('no_reply@progaccess.net', 'Formulaire '.$nomdusite);
if($reply2)
{
		$mail->addAddress($rdata2['expeditor_email']);
	$mail->Subject = 'Re : ['.$nomdusite.'] : '.$rdata2['subject'];
}
else
{
		$mail->addAddress($_POST['mail']);
		$mail->Subject = '['.$nomdusite.'] : '.$_POST['obj'];
}
		$mail->CharSet = 'UTF-8';
		$mail->isHTML(TRUE);
		$mail->Body = $bodycopy;
		$mail->AltBody = "Ce message est uniquement disponible en HTML. Veuillez activer l'affichage HTML.";
		$mail->send();
		}
	}
}
?>
<!doctype html>
<html lang="fr">
<?php include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="Retour à l'accueil"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</div>
<?php include('inclus/son.php');
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu">Contacter l'équipe <?php print $nomdusite; ?></h1>
<?php echo tr($tr,'tel',array('site'=>$nomdusite));
if(!empty($log)) echo '<ul id="log">'.$log.'</ul>'; ?>
<form action="?act=<?php if($reply) echo 'reply&id='.$rdata['id'].'&h='.$rdata['hash']; else echo 'contact'; ?>" method="post" spellcheck="true">
	<fieldset><legend>Informations personnelles</legend>
		<table>
			<tr><td><label for="f_name">Nom&nbsp;:</label></td><td><input type="text" name="name" id="f_name"<?php if($reply) echo ' value="'.htmlentities($rdata['expeditor_name']).'" disabled'; else {if(isset($_POST['name']))echo ' value="'.htmlentities($_POST['name']);echo '" maxlength="255" required';if(!isset($_GET['act'])) echo ' autofocus';} ?> /></td></tr>
			<tr><td><label for="f_mail">Adresse e-mail&nbsp;:</label></td><td><input type="email" name="mail" id="f_mail"<?php if($reply) echo ' value="'.htmlentities($rdata['expeditor_email']).'" disabled'; elseif(isset($_POST['mail']))echo ' value="'.htmlentities($_POST['mail']).'"'; ?> maxlength="255" required /></td></tr>
		</table>
	</fieldset>
	<fieldset><legend>Message</legend>
			<label for="f_obj">Sujet du message&nbsp;:</label>
		<?php if($reply) { ?>
		<input type="text" id="f_obj" name="obj" value="<?php echo htmlentities($rdata['subject']); ?>" disabled />
		<?php } else { ?>
		<input type="text" id="f_obj" name="obj" <?php if(isset($_POST['obj']))echo ' value="'.htmlentities($_POST['obj']).'"'; ?> required />
		<?php } ?><br />
		<label for="f_msg">Votre message&nbsp;:</label><br />
		<textarea id="f_msg" name="msg" maxlength="8192" style="width: calc(100% - 10px);min-height: 100px;margin-bottom: 10px;" required><?php if(isset($_POST['msg']))echo htmlentities($_POST['msg']); ?></textarea><br />
		<label for="f_agree" class="f_antispam">Veuillez ne pas cocher cette case&nbsp;:</label>
		<input type="checkbox" id="f_agree" name="agree" class="f_antispam" /><br />
		<label for="f_copy">Recevoir une copie de votre message&nbsp;:</label>
		<input type="checkbox" id="f_copy" name="copy" /><br />
		<input type="submit" value="Envoyer" />
	</fieldset>
</form>
</div>
<?php include 'inclus/footer.php'; ?>
</body>
</html>
