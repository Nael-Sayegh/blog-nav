<?php $logonly = true;
$adminonly=true;
$justpa = true;
$titlePAdm='Versions du site';
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php');
if(isset($_GET['add']) and isset($_POST['name']) and isset($_POST['text'])) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/codestat.php');
	$codestat_n_files = -1;
	$codestat_n_lines = -1;
	$codestat_n_chars = -1;
	require_once($_SERVER['DOCUMENT_ROOT'].'/cache/codestatc.php');
	
	$req = $bdd->prepare('INSERT INTO `site_updates`(`name`, `text`,`date`,`authors`,`codestat`) VALUES(?,?,?,?,?)');
	$req->execute(array(htmlspecialchars($_POST['name']), $_POST['text'], time(), $nom, json_encode(array($codestat_n_files, $codestat_n_lines, $codestat_n_chars))));
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
	
	$req = $bdd->prepare('SELECT `id`,`name` FROM `site_updates` ORDER BY `id` DESC LIMIT 1');
	$req->execute();
	if($data = $req->fetch()) {
		require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/facebook/envoyer.php');
		send_facebook($nomdusite.' version '.substr($data['name'],1).' publié, changements sur https://www.progaccess.net/u?id='.$data['id'].' '.$nom);
		require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/twitter/twitter.php');
		send_twitter($nomdusite.' version '.substr($data['name'],1).' publié, changements sur https://www.progaccess.net/u?id='.$data['id'].' '.$nom);
		require_once('Discord/DiscordBot2.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
	}
}
if(isset($_GET['delete'])) {
	$req = $bdd->prepare('DELETE FROM site_updates WHERE id=?');
	$req->execute(array($_GET['delete']));
	require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
}
if(isset($_GET['mod2']) and isset($_POST['name']) and isset($_POST['text'])) {
	$req = $bdd->prepare('UPDATE site_updates SET name=?, text=?, authors=? WHERE id=?');
	$req->execute(array(htmlspecialchars($_POST['name']), $_POST['text'], $nom, $_GET['mod2']));
	require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Gestion des versions de <?php print $nomdusite; ?></title>
<?php print $cssadmin; ?>
<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<?php require_once('inclus/banner.php'); ?>
		<table border="1">
			<thead><tr><th>ID</th><th>Numéro de version</th><th>Date</th><th>Actions</th></tr></thead>
			<tbody>
<?php
$req = $bdd->query('SELECT * FROM site_updates ORDER BY date ASC');
while($data = $req->fetch()) {
$versionxx = substr($data['name'],1);
echo '<tr><td>V'.$data['id'].'</td><td>'.$versionxx.'</td><td>'.date('d/m/Y H:i',$data['date']).'</td><td><a href="?delete='.$data['id'].'">Supprimer</a> | <a href="?mod='.$data['id'].'#mod">Modifier</a></td></tr>';
}
?>
			</tbody>
		</table>
		
<?php
if(isset($_GET['mod'])) {
	$req = $bdd->prepare('SELECT * FROM site_updates WHERE id=? LIMIT 1');
	$req->execute(array($_GET['mod']));
	if($data = $req->fetch()) { ?>
		<h3 id="mod">Modification de la mise à jour</h3>
		<form action="?mod2=<?php echo $data['id']; ?>" method="post">
			<label for="f2_name">Nom&nbsp;:</label><input type="text" name="name" id="f2_name" maxlength="255" value="<?php echo $data['name']; ?>" required><br>
			<label for="f2_text">Texte descriptif HTML&nbsp;:</label><br>
			<textarea name="text" id="f2_text" maxlength="8192" rows="20" cols="500"><?php echo $data['text']; ?></textarea><br>
			<input type="submit" value="Modifier">
		</form>
<?php	}
}
?>
		
		<h2>Ajout d'une mise à jour</h2>
		<form action="?add" method="post">
			<label for="f_name">Nom&nbsp;:</label><input type="text" name="name" id="f_name" maxlength="255" required><br>
			<label for="f_text">Texte descriptif HTML&nbsp;:</label><br>
			<textarea name="text" id="f_text" maxlength="8192" rows="20" cols="500"></textarea><br>
			<input type="submit" value="Ajouter">
		</form>
	</body>
</html>