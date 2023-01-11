<?php
$nolog = true;
require_once('inclus/log.php');
$stats_page='login';
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('inclus/consts.php');
$tr = load_tr($lang, 'login');
$cheminaudio='/audio/sons_des_pages/membre.mp3';
$titre = tr($tr,'title');

$log = '';
if(isset($_POST['username']) and isset($_POST['psw'])) {
	require_once('inclus/lib/random/random.php');
	
	$req = $bdd->prepare('SELECT * FROM `accounts` WHERE `username`=? OR `email`=? LIMIT 2');
	$req->execute(array($_POST['username'], $_POST['username']));
	
	while($data = $req->fetch()) {
		if(password_verify($_POST['psw'], $data['password'])) {
			$session = hash('sha512', time().random_int(100000,999999).sha1(random_int(100000,999999).$_POST['psw']));
			$connectid = hash('sha256', time().random_int(100000,999999).sha1(random_int(100000,999999).$data['id']));
			$token = urlsafe_b64encode(hash('sha256', strval(random_int(100000,999999).$connectid), true));
			$created = time();
			$expire = $created+31557600;
			setcookie('session', $session, $expire, '/', NULL, false, true);
			setcookie('connectid', $connectid, $expire, '/', NULL, false, true);
			$req2 = $bdd->prepare('INSERT INTO `sessions` (`account`, `session`, `connectid`, `expire`, `created`, `token`) VALUES (?,?,?,?,?,?)');
			$req2->execute(array($data['id'], password_hash($session,PASSWORD_DEFAULT), $connectid, $expire, $created, $token));
			if(isset($_GET['forum']))
				header('Location: /auth_forum.php?token='.$token);
			else
				header('Location: /redirlogin.php');
			exit();
		}
		else $log = tr($tr,'wrong');
	}
}
if(isset($_GET['signed']) and isset($_GET['mail'])) {
	$req = $bdd->prepare('SELECT `email` FROM `accounts` WHERE `id`=? AND `confirmed`=0 LIMIT 1');
	$req->execute(array($_GET['signed']));
	if($data = $req->fetch()) {
		if(sha1($data['email']) == $_GET['mail'])
			$log = tr($tr,'account_created',array('mail'=>htmlentities($data['email'])));
	}
}
if(isset($_GET['confirmed']))
	$log = tr($tr,'confirmed');
elseif(isset($_GET['confirm_err']))
	$log = tr($tr,'confirm_err');
elseif(isset($_GET['logonly']))
	$log = tr($tr,'logonly');
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php require_once('inclus/header.php'); ?>
<body>
<?php require_once('inclus/banner.php');
require_once('inclus/son.php'); ?>
<main id="container">
	<h1 id="contenu"><?php print $titre; ?></h1>
<?php if(!empty($log)) echo '<div id="divlog" role="complementary" aria-live="assertive"><p id="log"><b>'.$log.'</b></p></div>'; ?>
	<form action="?a=form<?php if(isset($_GET['forum'])) echo '&forum'; ?>#log" method="post">
		<input type="text" id="f1_username" name="username" placeholder="<?php echo tr($tr,'username'); ?>" maxlength="32" aria-label="<?php echo tr($tr,'username'); ?>" autofocus><br>
		<input type="password" id="f1_psw" name="psw" placeholder="<?php echo tr($tr,'password'); ?>" maxlength="64" aria-label="<?php echo tr($tr,'password'); ?>"><br>
		<input type="submit" id="f1_submit" value="<?php echo tr($tr,'bt_login'); ?>">
	</form>
	<a href="/mdp_demande.php"><?php echo tr($tr,'forgot_psw'); ?></a><br>
	<a href="/signup.php"><?php echo tr($tr,'signup'); ?></a>
	<p><?php echo tr($tr,'cookies'); ?></p>
</main>
<?php require_once('inclus/footer.php'); ?>
</body>
</html>