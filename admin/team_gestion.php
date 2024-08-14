<?php
$logonly = true;
$adminonly=true;
$justpa = true;
$titlePAdm='Gestion de l\'équipe';
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/include/consts.php');

if(isset($_GET['add']) and isset($_POST['name']) and isset($_POST['status']) and isset($_POST['age']) and isset($_POST['short_name']) and isset($_POST['bio']) and isset($_POST['works']) and isset($_POST['mastodon'])) {
	$account_id = NULL;
	if(isset($_POST['account_id']) and !empty($_POST['account_id']))
		$account_id = $_POST['account_id'];
	$req = $bdd->prepare('INSERT INTO `team`(`name`, `status`, `date`, `age`, `account_id`, `short_name`, `bio`, `works`, `mastodon`, `rights`) VALUES(?,?,?,?,?,?,?,?,?,?)');
	$req->execute(array($_POST['name'], $_POST['status'], time(), strtotime(preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $_POST['age'])), $account_id, $_POST['short_name'], $_POST['bio'], $_POST['works'], $_POST['mastodon'], ''));
/*	$req = $bdd->prepare('SELECT `id`,`works`,`age`,`short_name` FROM `team` ORDER BY `id` DESC LIMIT 1');
	$req->execute();
	if($data = $req->fetch()) {
		switch($data['works']) {
		case '0': $worksswi = 'NVDA.FR'; break;
		case '1': $worksswi = $site_name; break;
		case '2': $worksswi = 'NVDA.FR & '.$site_name; break;
	}
		require_once($_SERVER['DOCUMENT_ROOT'].'/include/lib/facebook/fb_publisher.php');
		send_facebook('Nouvel arrivant dans l\'équipe : '.$data['short_name'].' (E'.$data['id'].') : travaille pour '.$worksswi.'.'."\n".'Consulter https://www.progaccess.net/contact.php pour en savoir plus.'."\n".'L\'administration');
	}*/
}
if(isset($_GET['delete'])) {
	$req = $bdd->prepare('DELETE FROM `team` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['delete']));
}
if(isset($_GET['mod2']) and isset($_POST['name']) and isset($_POST['status']) and isset($_POST['age']) and isset($_POST['account_id']) and isset($_POST['short_name']) and isset($_POST['bio']) and isset($_POST['works']) and isset($_POST['mastodon'])) {
	$req = $bdd->prepare('UPDATE `team` SET `name`=?, `status`=?, `age`=?, `account_id`=?, `short_name`=?, `bio`=?, `works`=?, `mastodon`=?, `rights`=? WHERE `id`=? LIMIT 1');
	$req->execute(array(htmlentities($_POST['name']), $_POST['status'], strtotime(preg_replace('/^(\d{2})\/(\d{2})\/(\d{4})$/', '$3-$2-$1', $_POST['age'])), $_POST['account_id'], $_POST['short_name'], $_POST['bio'], $_POST['works'], $_POST['mastodon'], '', $_GET['mod2']));
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Gestion de l'équipe de <?php print $site_name; ?></title>
		<?php print $admin_css_path; ?>
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<?php require_once('include/banner.php'); ?>
		<table border="1">
			<thead><tr><th>Numéro d'équipier</th><th>Nom</th><th>Nom court</th><th>Statut(s)</th><th>Date</th><th>Âge</th><th>Mastodon</th><th>Actions</th></tr></thead>
			<tbody>
<?php
$req = $bdd->query('SELECT * FROM `team` ORDER BY `name` ASC');
while($data = $req->fetch()) {
	echo '<tr><td>M'.$data['account_id'].'/E'.$data['id'].'</td><td>'.$data['name'].'</td><td>'.$data['short_name'].'</td><td>'.$data['status'].'</td><td>'.date('d/m/Y H:i',$data['date']).'</td><td>'.intval((time()-$data['age'])/31557600).'</td><td>@'.$data['mastodon'].'</td><td><a href="?mod='.$data['id'].'#mod">Modifier</a> | <a href="?delete='.$data['id'].'">Supprimer</a></td></tr>';
}
?>
			</tbody>
		</table>
<?php
if(isset($_GET['mod'])) {
	$req = $bdd->prepare('SELECT * FROM `team` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['mod']));
	if($data = $req->fetch()) { ?>
		<h3 id="mod">Modifier</h3>
		<form action="?mod2=<?php echo $data['id']; ?>" method="post">
			<label for="f2_name">Nom&nbsp;:</label><input type="text" name="name" id="f2_name" maxlength="255" value="<?php echo $data['name']; ?>" required><br>
			<label for="f2_text">Statut(s)&nbsp;:</label><input type="text" name="status" id="f2_text" maxlength="255" value="<?php echo htmlentities($data['status']); ?>" required><br>
			<label for="f2_age">Date de naissance (dd/mm/aaaa)&nbsp;:</label><input type="text" name="age" id="f2_age" value="<?php echo date('d/m/Y', $data['age']); ?>" maxlength="10" required><br>
			<label for="f2_account">Compte membre&nbsp;:</label>
			<select id="f2_account" name="account_id" autocompletion="off">
				<option value="">Aucun</option>
<?php
		$req2 = $bdd->prepare('SELECT `id`, `username` FROM `accounts` WHERE `rank`="a" ORDER BY `id` ASC');
		$req2->execute();
		while($data2 = $req2->fetch()) {
			echo '<option value="'.$data2['id'].'"'.(($data2['id'] == $data['account_id']) ? ' selected':'').'>M'.$data2['id'].' '.htmlentities($data2['username']).'</option>';
		}
?>
			</select><br>
			<label for="f2_short">Nom court&nbsp;:</label>
			<input type="text" name="short_name" id="f2_short" value="<?php echo $data['short_name']; ?>" maxlength="255" required><br>
			<label for="f2_bio">Courte bio&nbsp;:</label>
			<textarea id="f2_bio" name="bio" style="width:100%;height:10em;"><?php echo htmlentities($data['bio']); ?></textarea><br>
<label for="f2_works">Travaille pour&nbsp;:</label>
<select id="f2_works" name="works">
<option value="0" <?php if($data['works'] == '0') { echo 'selected'; } ?>>NVDA.FR</option>
<option value="1" <?php if($data['works'] == '1') { echo 'selected'; } ?>><?php print $site_name; ?></option>
<option value="2" <?php if($data['works'] == '2') { echo 'selected'; } ?>>NVDA.FR et <?php print $site_name; ?></option>
</select><br>
			<label for="f2_mastodon">Pseudo Mastodon (sans le @ et avec l'instance si différent de mastodon.progaccess.net)&nbsp;:</label><input type="text" name="mastodon" id="f2_mastodon" maxlength="255" value="<?php echo $data['mastodon']; ?>"><br>
			<input type="submit" value="Modifier">
		</form>
<?php
	}
}
?>
		
		<h2>Ajouter</h2>
		<form action="?add" method="post">
			<label for="f_name">Nom&nbsp;:</label><input type="text" name="name" id="f_name" maxlength="255" required><br>
			<label for="f_text">Statut(s)&nbsp;:</label><input type="text" name="status" id="f_text" maxlength="255" required><br>
			<label for="f_age">Date de naissance (dd/mm/aaaa)&nbsp;:</label><input type="text" name="age" id="f_age" maxlength="10" required><br>
			<label for="f_account">Compte membre&nbsp;:</label>
			<select id="f_account" name="account_id">
				<option value="">Aucun</option>
<?php
$req = $bdd->prepare('SELECT `id`, `username` FROM `accounts` WHERE `rank`="a" ORDER BY `id` ASC');
$req->execute();
while($data = $req->fetch()) {
	echo '<option value="'.$data['id'].'">M'.$data['id'].' '.htmlentities($data['username']).'</option>';
}
?>
			</select><br>
			<label for="f_short">Nom court&nbsp;:</label>
			<input type="text" name="short_name" id="f_short" maxlength="255" required><br>
			<label for="f_bio">Courte bio&nbsp;:</label>
			<textarea id="f_bio" name="bio" style="width:100%;height:10em;"></textarea><br>
<label for="f_works">Travaille pour&nbsp;:</label>
<select id="f_works" name="works">
<option value="0">NVDA.FR</option>
<option value="1"><?php print $site_name; ?></option>
<option value="2">NVDA.FR et <?php print $site_name; ?></option>
</select><br>
			<label for="f_mastodon">Pseudo Mastodon (sans le @ et avec l'instance si différent de mastodon.progaccess.net)&nbsp;:</label><input type="text" name="mastodon" id="f_mastodon" maxlength="255"><br>
			<input type="submit" value="Ajouter">
		</form>
	</body>
</html>