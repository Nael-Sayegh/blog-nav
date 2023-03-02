<?php
$nolog = true;
set_include_path($_SERVER['DOCUMENT_ROOT']);
$stats_page = 'mdpforget';
require_once('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang,'fg_passwd');
$title=tr($tr,'title');
$sound_path='/audio/page_sounds/member.mp3';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require_once('include/lib/phpmailer/src/PHPMailer.php');
require_once('include/lib/phpmailer/src/Exception.php');
require_once('include/lib/phpmailer/src/SMTP.php');
if(isset($_GET['act']) && $_GET['act'] == 'form' && isset($_POST['username']) and isset($_POST['email']) and isset($_POST['nummember']) and isset($_POST['signup']))
{
	$req=$bdd->prepare('SELECT * FROM `accounts` WHERE `id`=? LIMIT 1');
	$req->execute(array($_POST['nummember']));
	if($data=$req->fetch())
	{
		if($_POST['signup'] == $data['signup_date'] && $_POST['username'] == $data['username'] && $_POST['email'] == $data['email'])
		{
			$caract = 'abcdefghijklmnopqrstuvwxyz0123456789@!:;,/?*$=+.-_ &)(][{}#"\'';
			$pwd='';
			for($i = 1; $i <= 12; $i++) {
				$pwd.=strtoupper($caract[mt_rand(0,(strlen($caract)-1))]);
			}
			$req2=$bdd->prepare('UPDATE `accounts` SET password=? WHERE id=? LIMIT 1');
			$req2->execute(array(password_hash($pwd, PASSWORD_DEFAULT), $_POST['nummember']));
			$msg = '<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Réinitialisation du mot de passe '.$site_name.'</title>
</head>
<body>
<h1>'.$site_name.' - Réinitialisation de mot de passe</h1>
<p>Bonjour '.htmlentities($data['username']).'<br>
vous avez demandé la réinitialisation de votre mot de passe sur '.$site_name.', celle-ci a été réalisée avec succès.<br>
Votre nouveau mot de passe est&nbsp;:<br>
'.$pwd.'
<br>par mesure de sécurité, nous vous invitons vivement à modifier ce mot de passe dans <a href="'.SITE_URL.'/home.php">votre profil</a>.<br>
Cordialement.<br>
'.$site_name.'</p>
</body>
</html>';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = SMTP_HOST;
$mail->Port = SMTP_PORT;
$mail->SMTPAuth = true;
$mail->Username = SMTP_USERNAME;
$mail->Password = SMTP_PSW;
$mail->setFrom(SMTP_MAIL, SMTP_NAME);
$mail->addReplyTo(SMTP_MAIL, SMTP_NAME);
$mail->AddAddress($data['email']);
$mail->Subject = 'Réinitialisation de mot de passe '.$site_name;
$mail->CharSet = 'UTF-8';
$mail->IsHTML(TRUE);
$mail->Body = $msg;
if($mail->send()) {
$log=tr($tr,'log_success');
}
		}
		else
		{
			$log=tr($tr,'log_error');
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?php if(!empty($log)) print $log; ?>
<p><?php echo tr($tr,'intro_text',array('site'=>$site_name)); ?></p>
<form action="?act=form" method="post" spellcheck="true">
<fieldset>
<legend><?php echo tr($tr,'perso_inf'); ?></legend>
<label for="f_username"><?php echo tr($tr,'username'); ?></label><input type="text" name="username" id="f_username" autocomplete="off" maxlength="100" required><br>
<label for="f_email"><?php echo tr($tr,'email'); ?></label><input type="email" name="email" id="f_email" autocomplete="off" maxlength="100" required><br>
<label for="f_nummember"><?php echo tr($tr,'member_id'); ?></label>
<select name="nummember" id="f_nummember">
<?php
$req = $bdd->query('SELECT * FROM `accounts` ORDER BY id ASC');
while($data = $req->fetch()) {
echo '<option value="'.$data['id'].'">M'.$data['id'].'</option>';
}
?>
<option value="non"><?php echo tr($tr,'idk'); ?></option>
</select><br>
<label for="f_signup"><?php echo tr($tr,'signup_date'); ?></label>
<select name="signup" id="f_signup">
<?php
$req2 = $bdd->query('SELECT * FROM `accounts` ORDER BY id ASC');
while($data = $req2->fetch()) {
echo '<option value="'.$data['signup_date'].'">'.strftime(tr($tr0,'fndatetime'),$data['signup_date']).'</option>';
}
?>
<option value="non"><?php echo tr($tr,'idk'); ?></option>
</select><br>
<input type="submit" value="<?php echo tr($tr,'confirm_btn'); ?>">
</fieldset>
</form>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>