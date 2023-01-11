<?php $logonly = true;
$adminonly=true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';

if((isset($_POST['token']) and $_POST['token'] == $login['token']) or (isset($_GET['token']) and $_GET['token'] == $login['token'])) {
	if(isset($_GET['add']) and isset($_POST['label']) and isset($_POST['style']) and isset($_POST['title']) and isset($_POST['title_style']) and isset($_POST['contain']) and isset($_POST['contain_style']) and isset($_POST['lang']) and isset($_POST['todo'])) {
		echo 'OK';
		$req = $bdd->prepare('INSERT INTO slides(lang,label,style,title,title_style,contain,contain_style,date,todo_level) VALUES(?,?,?,?,?,?,?,?,?)');
		$req->execute(array($_POST['lang'], htmlspecialchars($_POST['label']), $_POST['style'], $_POST['title'], $_POST['title_style'], $_POST['contain'], $_POST['contain_style'], time(), $_POST['todo']));
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
	}
	if(isset($_GET['delete'])) {
		$req = $bdd->prepare('DELETE FROM `slides` WHERE `id`=?');
		$req->execute(array($_GET['delete']));
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
	}
	if(isset($_GET['mod2']) and isset($_POST['label']) and isset($_POST['style']) and isset($_POST['title']) and isset($_POST['title_style']) and isset($_POST['contain']) and isset($_POST['contain_style']) and isset($_POST['lang']) and isset($_POST['todo'])) {
		$req = $bdd->prepare('UPDATE `slides` SET `lang`=?, `label`=?, `style`=?, `title`=?, `title_style`=?, `contain`=?, `contain_style`=?, `date`=?, `todo_level`=? WHERE `id`=? LIMIT 1');
		$req->execute(array($_POST['lang'], htmlspecialchars($_POST['label']), $_POST['style'], $_POST['title'], $_POST['title_style'], $_POST['contain'], $_POST['contain_style'], time(), $_POST['todo'], $_GET['mod2']));
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Gestion des slides de <?php print $nomdusite; ?></title>
		<?php print $cssadmin; ?>
		<link rel="stylesheet" href="css/translate.css">
		<script type="text/javascript" src="js/sliderstyles.js"></script>
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<h1>Slider - <a href="/"><?php print $nomdusite; ?></a></h1>
<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php'; ?>
		<table border="1">
			<thead><tr><th>Label</th><th>Langue</th><th>Titre</th><th>Actions</th><th>Modification</th><th>État</th><th>Publié</th></tr></thead>
			<tbody>
<?php
$req = $bdd->query('SELECT * FROM `slides`');
while($data = $req->fetch()) {
	echo '<tr>
			<td>'.$data['label'].'</td>
			<td title="'.$data['lang'].'">'.$langs[$data['lang']].'</td>
			<td>'.$data['title'].'</td>
			<td><a href="?delete='.$data['id'].'&token='.$login['token'].'">Supprimer</a> | <a href="?mod='.$data['id'].'">Modifier</a></td>
			<td>'.date('d/m/Y H:i', $data['date']).'</td>
			<td class="tr_todo'.$data['todo_level'].'">'.$tr_todo[$data['todo_level']].'</td>
			<td class="tr_published'.$data['published'].'">'.($data['published']?'Public':'Privé').'</td>
		</tr>';
}
?>
			</tbody>
		</table>
		
<?php
if(isset($_GET['mod'])) {
	$req = $bdd->prepare('SELECT * FROM `slides` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['mod']));
	if($data = $req->fetch()) { ?>
		<h3>Modification de la slide</h3>
		<label for="f1_stylejs">Design prédéfini&nbsp;: </label>
		<select id="f1_stylejs"><option value="Blue Sky">Blue Sky</option><option value="Metal Kiwi">Metal Kiwi</option><option value="Light Pacific">Light Pacific</option><option value="Water Melon">Water Melon</option><option value="Breizh Gradient">Breizh Gradient</option></select>
		<input type="button" value="Appliquer le style" onclick="setstyle('f1_')"><br>
		<form action="?mod2=<?php echo $data['id']; ?>" method="post">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>" autocomplete="off">
			<label for="f1_lang">Langue&nbsp;:</label>
			<select id="f1_lang" name="lang" autocomplete="off"><?php echo langs_html_opts($data['lang']); ?></select><br>
			<label for="f1_todo">État&nbsp;:</label>
			<select id="f1_todo" name="todo" autocomplete="off"><?php foreach($tr_todo as $key => $val) {echo '<option value="'.$key.'"'.($data['todo_level']==$key? ' selected':'').'>'.$val.'</option>';} ?></select><br>
			<label for="f1_label">Label&nbsp;:</label><input type="text" name="label" id="f1_label" value="<?php echo $data['label']; ?>" maxlength="255" required><br>
			<label for="f1_style">Style CSS de la slide&nbsp;:</label><br>
			<textarea name="style" id="f1_style" maxlength="1024"><?php echo $data['style']; ?></textarea><br>
			<label for="f1_title">Titre&nbsp;:</label><input type="text" name="title" id="f1_title" value="<?php echo $data['title']; ?>" maxlength="512" required><br>
			<label for="f1_title_style">Style CSS du titre&nbsp;:</label><br>
			<textarea name="title_style" id="f1_title_style" maxlength="1024"><?php echo $data['title_style']; ?></textarea><br>
			<label for="f1_contain">Contenu&nbsp;:</label><br>
			<textarea name="contain" id="f1_contain" maxlength="8192" rows="20" cols="500"><?php echo $data['contain']; ?></textarea><br>
			<label for="f1_contain_style">Style CSS du contenu&nbsp;:</label><br>
			<textarea name="contain_style" id="f1_contain_style" maxlength="1024"><?php echo $data['contain_style']; ?></textarea><br>
			<input type="submit" value="Modifier">
		</form>
<?php	}
}
?>
		
		<h2>Ajout d'une slide</h2>
		<label for="f_stylejs">Design prédéfini&nbsp;: </label>
		<select id="f_stylejs"><option value="Blue Sky">Blue Sky</option><option value="Metal Kiwi">Metal Kiwi</option><option value="Light Pacific">Light Pacific</option><option value="Water Melon">Water Melon</option><option value="Breizh Gradient">Breizh Gradient</option></select>
		<input type="button" value="Appliquer le style" onclick="setstyle('f_')"><br>
		<form action="?add" method="post">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>" autocomplete="off">
			<label for="f_lang">Langue&nbsp;:</label>
			<select id="f_lang" name="lang" autocomplete="off"><?php echo langs_html_opts($lang); ?></select><br>
			<label for="f_todo">État&nbsp;:</label>
			<select id="f_todo" name="todo" autocomplete="off"><?php foreach($tr_todo as $key => $val) {echo '<option value="'.$key.'">'.$val.'</option>';} ?></select><br>
			<label for="f_label">Label&nbsp;:</label><input type="text" name="label" id="f_label" maxlength="255" required><br>
			<label for="f_style">Style CSS de la slide&nbsp;:</label><br>
			<textarea name="style" id="f_style" maxlength="1024"></textarea><br>
			<label for="f_title">Titre&nbsp;:</label><input type="text" name="title" id="f_title" maxlength="512" required><br>
			<label for="f_title_style">Style CSS du titre&nbsp;:</label><br>
			<textarea name="title_style" id="f_title_style" maxlength="1024"></textarea><br>
			<label for="f_contain">Contenu&nbsp;:</label><br>
			<textarea name="contain" id="f_contain" class="ta" maxlength="8192"></textarea><br>
			<label for="f_contain_style">Style CSS du contenu&nbsp;:</label><br>
			<textarea name="contain_style" id="f_contain_style" maxlength="1024"></textarea><br>
			<input type="submit" value="Ajouter">
		</form>
	</body>
</html>