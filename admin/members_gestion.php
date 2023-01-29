<?php
$logonly = true;
$adminonly = true;
$justpa = true;
$titlePAdm='Gestion des comptes membres';
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/include/consts.php');
if(isset($_GET['delete'])) {
	$req = $bdd->prepare('DELETE FROM `accounts` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['delete']));
}
if(isset($_GET['mod2']) and isset($_POST['username']) and isset($_POST['email']) and isset($_POST['rank'])) {
	if(isset($_POST['password']) and !empty($_POST['password'])) {
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$req = $bdd->prepare('UPDATE `accounts` SET `username`=?, `email`=?, `password`=?, `rank`=? WHERE `id`=? LIMIT 1');
		$req->execute(array(htmlentities($_POST['username']), $_POST['email'], $password, $_POST['rank'], $_GET['mod2']));
	} else {
		$req = $bdd->prepare('UPDATE `accounts` SET `username`=?, `email`=?, `rank`=? WHERE `id`=? LIMIT 1');
		$req->execute(array(htmlentities($_POST['username']), $_POST['email'], $_POST['rank'], $_GET['mod2']));
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Gestion des membres <?php print $site_name; ?></title>
		<?php print $admin_css_path; ?>
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<?php require_once('include/banner.php'); ?>
		<table border="1">
			<thead><tr><th>Nom d'utilisateur</th><th>Adresse mail</th><th>Rang</th><th>Actions</th></tr></thead>
			<tbody>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/include/user_rank.php';
$req = $bdd->query("SELECT * FROM `accounts`");
while($data = $req->fetch()) {
	echo '<tr><td>'.$data['username'].'</td><td>'.$data['email'].'</td><td>'.urank($data['rank']).'</td><td><a href="?mod='.$data['id'].'#mod">Modifier</a> | <a href="?delete='.$data['id'].'" onclick="return confirm(\'Faut-il vraiment supprimer le membre '.$data['username'].'&nbsp;?\')">Supprimer</a></td></tr>';
}
?>
			</tbody>
		</table>
<?php
if(isset($_GET['mod'])) {
	$req = $bdd->prepare('SELECT * FROM `accounts` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['mod']));
	if($data = $req->fetch()) {
?>
		<h3 id="mod">Modifier</h3>
		<form action="?mod2=<?php echo $data['id']; ?>" method="post">
			<label for="f2_username">Nom d'utilisateur&nbsp;:</label>
			<input type="text" name="username" id="f2_username" maxlength="32" value="<?php echo $data['username']; ?>" required><br>
			<label for="f2_email">Adresse mail&nbsp;:</label>
			<input type="email" name="email" id="f2_email" maxlength="255" value="<?php echo $data['email']; ?>" required><br>
			<label for="f2_psw">Mot de passe&nbsp;:</label>
			<input type="password" name="password" id="f2_psw" maxlength="64"><br>
			<label for="f2_rank">Rang&nbsp;:</label>
			<select id="f2_rank" name="rank">
				<option value="0" <?php if($data['rank']=='0')echo'selected'; ?>>Nouveau</option>
				<option value="1" <?php if($data['rank']=='1')echo'selected'; ?>>Membre</option>
				<option value="a" <?php if($data['rank']=='a')echo'selected'; ?>>Membre de l'équipe</option>
				<option value="m" <?php if($data['rank']=='m')echo'selected'; ?>>Modérateur</option>
				<option value="i" <?php if($data['rank']=='i')echo'selected'; ?>>Anonyme</option>
				<option value="b" <?php if($data['rank']=='b')echo'selected'; ?>>Banni</option>
			</select><br>
			<input type="submit" value="Modifier">
		</form>
<?php
	}
}
?>
	</body>
</html>