<?php $logonly = true;
$adminonly=true;
$justpa = true;
$titlePAdm='Catégories';
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php');
if(isset($_GET['add']) and isset($_POST['name'])) {
	$req = $bdd->prepare('INSERT INTO softwares_categories(name,text) VALUES(?,?)');
	$req->execute(array(htmlspecialchars($_POST['name']), $_POST['text']));
}
if(isset($_GET['delete'])) {
	$req = $bdd->prepare('DELETE FROM softwares_categories WHERE id=?');
	$req->execute(array($_GET['delete']));
}
if(isset($_GET['mod2']) and isset($_POST['name'])) {
	$req = $bdd->prepare('UPDATE softwares_categories SET name=?, text=? WHERE id=?');
	$req->execute(array(htmlspecialchars($_POST['name']), $_POST['text'], $_GET['mod2']));
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Gestion des catégories de <?php print $nomdusite; ?></title>
<?php print $cssadmin; ?>
<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<?php require_once('inclus/banner.php'); ?>
		<table border="1">
			<thead><tr><th>Numéro de catégorie</th><th>Nom</th><th>Actions</th></tr></thead>
			<tbody>
<?php
$req = $bdd->query('SELECT * FROM softwares_categories ORDER BY name ASC');
while($data = $req->fetch()) {
	echo '<tr><td>C'.$data['id'].'</td><td>'.$data['name'].'</td><td><a href="?delete='.$data['id'].'" onclick="return confirm(\'Faut-il vraiment supprimer la catégorie '.$data['name'].'&nbsp;?\')">Supprimer</a> | <a href="?mod='.$data['id'].'">Modifier</a></td></tr>';
}
?>
			</tbody>
		</table>
		
<?php
if(isset($_GET['mod'])) {
	$req = $bdd->prepare('SELECT * FROM softwares_categories WHERE id=? ORDER BY name ASC LIMIT 1');
	$req->execute(array($_GET['mod']));
	if($data = $req->fetch()) { ?>
		<h3>Modification de la catégorie</h3>
		<form action="?mod2=<?php echo $data['id']; ?>" method="post">
			<label for="f2_name">Nom&nbsp;:</label><input type="text" name="name" id="f2_name" maxlength="255" value="<?php echo $data['name']; ?>" required><br>
			<label for="f2_text">Texte d'introduction HTML&nbsp;:</label><br>
			<textarea name="text" id="f2_text" maxlength="2047" rows="20" cols="500"><?php echo $data['text']; ?></textarea><br>
			<input type="submit" value="Modifier">
		</form>
<?php	}
}
?>
		
		<h2>Ajout d'une catégorie</h2>
		<form action="?add" method="post">
			<label for="f_name">Nom&nbsp;:</label><input type="text" name="name" id="f_name" maxlength="255" required><br>
			<label for="f_text">Texte d'introduction HTML&nbsp;:</label><br>
			<textarea name="text" id="f_text" maxlength="2047" rows="20" cols="500"></textarea><br>
			<input type="submit" value="Ajouter">
		</form>
	</body>
</html>