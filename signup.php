<?php
$nolog = true;
require_once('inclus/log.php');
$stats_page='signup';
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('inclus/consts.php');
$cheminaudio='/audio/sons_des_pages/membre.mp3';
$titre = 'Se créer un compte '.$nomdusite;

$log = '';
if(isset($_GET['a']) and $_GET['a'] == 'form' and isset($_POST['username']) and isset($_POST['mail']) and isset($_POST['psw']) and isset($_POST['rpsw'])) {
	if(strlen($_POST['username']) > 32 or strlen($_POST['username']) < 3) $log .= '<li>Votre nom d\'utilisateur doit comporter entre 3 et 32 caractères.</li>';
	if(strlen($_POST['mail']) > 255 or empty($_POST['mail'])) $log .= '<li>Votre adresse e-mail ne doit pas dépasser 255 caractères.</li>';
	if($_POST['psw'] != $_POST['rpsw']) $log .= '<li>Veuillez rentrer deux fois le mot de passe identique.</li>';
	if(strlen($_POST['psw']) > 128 or strlen($_POST['psw']) < 8) $log .= '<li>Votre mot de passe doit comporter entre 8 et 64 caractères.</li>';
	if(!(isset($_POST['box1']) and $_POST['box1']=='on') or (isset($_POST['box2']) and $_POST['box2']=='on')) $log .= '<li>Veuillez cocher l\'avant-dernière case, mais pas la dernière.</li>';
	if(empty($log)) {
		$username = $_POST['username'];
		$req = $bdd->prepare('SELECT `username`,`email` FROM `accounts` WHERE `username`=? OR `email`=? LIMIT 1');
		$req->execute(array($username, $_POST['mail']));
		if($data = $req->fetch()) {
			if($data['username'] == $username)
				$log .= '<li>Ce nom d\'utilisateur est déjà utilisé&#8239;!</li>';
			if($data['email'] == $_POST['mail'])
				$log .= '<li>Cette adresse e-mail est déjà utilisée&#8239;!</li>';
		}
		else {
			$ok = 100;
			while($ok > 0) {
				$id64 = base64_encode(hash('sha256', time().random_int(1000000,9999999).$username.random_int(10000000,99999999), true));
				$id64 = str_replace('/', '-', $id64);
				$id64 = str_replace('+', '_', $id64);
				$id64 = str_replace('=', '.', $id64);
				$req = $bdd->prepare('SELECT `id` FROM `accounts` WHERE `id64`=?');
				$req->execute(array($id64));
				if($req->fetch())
					$ok -= 1;
				else
					$ok = 0;
				if($ok == 1) {
					print 'Erreur, veuillez réessayer';
					exit();
				}
			}
			$password = password_hash($_POST['psw'], PASSWORD_DEFAULT);
			$mhash = hash('sha512',strval(time()+random_int(1000000,99999999)).$password.strval(random_int(100000,99999999)));
			$settings = ['mhash'=>$mhash,'menu'=>'0','fontsize'=>'16','audio'=>'0','date'=>'0','infosdef'=>'1'];
			if(isset($_COOKIE['menu']) and $_COOKIE['menu']=='1') $settings['menu'] = '1';
			if(isset($_COOKIE['fontsize']) and in_array($_COOKIE['fontsize'],['11','16','20','24'])) $settings['fontsize'] = $_COOKIE['fontsize'];
			if(isset($_COOKIE['audio']) and in_array($_COOKIE['audio'],['0','1','2','3','4','5','6','7','8','9','10'])) $settings['audio'] = $_COOKIE['audio'];
			if(isset($_COOKIE['date']) and $_COOKIE['date']=='1') $settings['date'] = '1';
			if(isset($_COOKIE['infosdef']) and $_COOKIE['infosdef']=='0') $settings['infosdef'] = '0';
			$email = $_POST['mail'];
			$req = $bdd->prepare('INSERT INTO `accounts` (`username`, `email`, `id64`, `password`, `signup_date`, `settings`) VALUES(?,?,?,?,?,?)');
			$req->execute(array($username, $email, $id64, $password, time(), json_encode($settings)));
			$id = $bdd->lastInsertId();
			
			if(isset($_POST['forum']) and $_POST['forum'] == 'on') {
				require_once('inclus/flarum.php');
				create_forum_account($id, $username, $email);
			}
			
			include('inclus/sendconfirm.php');
			send_confirm($id, $email, $mhash, $username);
			header('Location: /login.php?signed='.$id.'&mail='.sha1($email));
			
			if(isset($_POST['nl']) and $_POST['nl'] == 'on') {
				$req= $bdd->prepare('SELECT `id` FROM `newsletter_mails` WHERE `mail`=? LIMIT 1');
				$req->execute(array($email));
				if($req->fetch())
					exit();
				$req = $bdd->prepare('INSERT INTO `newsletter_mails` (`hash`, `mail`, `expire`, `freq`, `notif_site`, `notif_upd`, `confirm`) VALUES (?, ?, ?, "3", 1, 1, 0)');
				$req->execute(array(sha1(strval(rand()+time()).$email).sha1($email.$_SERVER['REMOTE_ADDR'].strval(rand())), $email, time()+86400));
			}
			exit();
		}
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('inclus/header.php'); ?>
<body>
<?php require_once('inclus/banner.php');
require_once('inclus/son.php'); ?>
<main id="container">
	<h1 id="contenu"><?php print $titre; ?></h1>
	<?php if(!empty($log)) echo '<ul id="log">'.$log.'</ul>'; ?>
	<form action="?a=form" method="post">
		<table>
			<tr><td class="formlabel"><label for="f_username">Nom d'utilisateur&nbsp;:</label></td>
				<td><input type="text" id="f_username" name="username" maxlength="32" required></td></tr>
			<tr><td class="formlabel"><label for="f_mail">Adresse e-mail&nbsp;:</label></td>
				<td><input type="email" id="f_mail" name="mail" maxlength="255" required></td></tr>
			<tr><td class="formlabel"><label for="f_psw">Mot de passe&nbsp;:</label></td>
				<td><input type="password" id="f_psw" name="psw" maxlength="64" required></td></tr>
			<tr><td class="formlabel"><label for="f_rpsw">Mot de passe (vérification)&nbsp;:</label></td>
				<td><input type="password" id="f_rpsw" name="rpsw" maxlength="64" required></td></tr>
			<tr><td class="formlabel"><label for="f_nl">S'inscrire à la lettre d'information&nbsp;:</label></td>
				<td><input type="checkbox" id="f_nl" name="nl"> <span>(mail hebdomadaire pour rester informer des mises à jours)</span></td></tr>
			<tr><td class="formlabel"><label for="f_forum">S'inscrire au <a href="https://forum.progaccess.net">forum ProgAccess</a>&nbsp;:</label></td>
				<td><input type="checkbox" id="f_forum" name="forum" checked></td></tr>
			<tr><td class="formlabel"><label for="f_box1">Cochez cette case&nbsp;:</label></td>
				<td><input type="checkbox" id="f_box1" name="box1"></td></tr>
			<tr><td class="formlabel"><label for="f_box2">Ne cochez pas cette case&nbsp;:</label></td>
				<td><input type="checkbox" id="f_box2" name="box2"></td></tr>
		</table>
		<p>L'usage des cookies est nécessaire pour utiliser l'espace membres. Vous créer un compte <?php echo $nomdusite; ?> confirme que vous acceptez les cookies en vous identifiant.<br>Nous ne partagerons pas votre adresse e-mail avec des tiers. Vous pourrez modifier les paramètres de votre compte ou le supprimer à tout moment.</p>
		<input type="submit" value="S'inscrire">
	</form>
</main>
<?php require_once('inclus/footer.php'); ?>
</body>
</html>