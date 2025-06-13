<?php $logonly = true;
$adminonly = true;
$justpa = true;
$titlePAdm = 'Slider';
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/include/consts.php');
requireAdminRight('manage_slider');

if ((isset($_POST['token']) && $_POST['token'] === $login['token']) || (isset($_GET['token']) && $_GET['token'] === $login['token']))
{
    if (isset($_GET['add']) && isset($_POST['label']) && isset($_POST['style']) && isset($_POST['title']) && isset($_POST['title_style']) && isset($_POST['contain']) && isset($_POST['contain_style']) && isset($_POST['lang']) && isset($_POST['todo']))
    {
        echo 'OK';
        $SQL = <<<SQL
            INSERT INTO slides(lang,label,style,title,title_style,contain,contain_style,date,todo_level) VALUES(:lng,:lbl,:style,:title,:titlestyle,:cont,:contstyle,:date,:lvl)
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':lng' => $_POST['lang'], ':lbl' => htmlspecialchars((string) $_POST['label']), ':style' => $_POST['style'], ':title' => $_POST['title'], ':titlestyle' => $_POST['title_style'], ':cont' => $_POST['contain'], ':contstyle' => $_POST['contain_style'], ':date' => time(), ':lvl' => $_POST['todo']]);
        require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
    }
    if (isset($_GET['delete']))
    {
        $SQL = <<<SQL
            DELETE FROM slides WHERE id=:id
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':id' => $_GET['delete']]);
        require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
    }
    if (isset($_GET['mod2']) && isset($_POST['label']) && isset($_POST['style']) && isset($_POST['title']) && isset($_POST['title_style']) && isset($_POST['contain']) && isset($_POST['contain_style']) && isset($_POST['lang']) && isset($_POST['todo']))
    {
        $SQL = <<<SQL
            UPDATE slides SET lang=:lng, label=:lbl, style=:style, title=:title, title_style=:titlestyle, contain=:cont, contain_style=:contstyle, date=:date, todo_level=:lvl WHERE id=:id
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':lng' => $_POST['lang'], ':lbl' => htmlspecialchars((string) $_POST['label']), ':style' => $_POST['style'], ':title' => $_POST['title'], ':titlestyle' => $_POST['title_style'], ':cont' => $_POST['contain'], ':contstyle' => $_POST['contain_style'], ':date' => time(), ':lvl' => $_POST['todo'], ':id' => $_GET['mod2']]);
        require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Gestion des slides de <?php print $site_name; ?></title>
<?php print $admin_css_path; ?>
<link rel="stylesheet" href="css/translate.css">
<script type="text/javascript" src="js/sliderstyles.js"></script>
<script type="text/javascript" src="/scripts/default.js"></script>
</head>
<body>
<?php require_once('include/banner.php'); ?>
<table border="1">
<thead><tr><th>Label</th><th>Langue</th><th>Titre</th><th>Modification</th><th>État</th><th>Publié</th><th>Actions</th></tr></thead>
<tbody>
<?php
$SQL = <<<SQL
    SELECT * FROM slides
    SQL;
foreach ($bdd->query($SQL) as $data)
{
    echo '<tr>
<td>'.$data['label'].'</td>
<td title="'.$data['lang'].'">'.$langs[$data['lang']].'</td>
<td>'.$data['title'].'</td>
<td>'.date('d/m/Y H:i', $data['date']).'</td>
<td class="tr_todo'.$data['todo_level'].'">'.$tr_todo[$data['todo_level']].'</td>
<td class="tr_published'.$data['published'].'">'.($data['published'] ? 'Public' : 'Privé').'</td>
<td><a href="?delete='.$data['id'].'&token='.$login['token'].'" onclick="return confirm(\'Faut-il vraiment supprimer la slide '.$data['title'].'&nbsp;?\')">Supprimer</a> | <a href="?mod='.$data['id'].'">Modifier</a></td>
</tr>';
}
?>
</tbody>
</table>

<?php
if (isset($_GET['mod']))
{
    $SQL = <<<SQL
        SELECT * FROM slides WHERE id=:id LIMIT 1
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':id' => $_GET['mod']]);
    if ($data = $req->fetch())
    { ?>
<h3>Modification de la slide</h3>
<label for="f1_stylejs">Design prédéfini&nbsp;: </label>
<select id="f1_stylejs"><option value="Blue Sky">Blue Sky</option><option value="Metal Kiwi">Metal Kiwi</option><option value="Light Pacific">Light Pacific</option><option value="Water Melon">Water Melon</option><option value="Breizh Gradient">Breizh Gradient</option></select>
<input type="button" value="Appliquer le style" onclick="setstyle('f1_')"><br>
<form action="?mod2=<?= $data['id'] ?>" method="post">
<input type="hidden" name="token" value="<?= $login['token'] ?>">
<label for="f1_lang">Langue&nbsp;:</label>
<select id="f1_lang" name="lang" autocomplete="off"><?= langs_html_opts($data['lang']) ?></select><br>
<label for="f1_todo">État&nbsp;:</label>
<select id="f1_todo" name="todo" autocomplete="off"><?php foreach ($tr_todo as $key => $val)
{
    echo '<option value="'.$key.'"'.($data['todo_level'] === $key ? ' selected' : '').'>'.$val.'</option>';
} ?></select><br>
<label for="f1_label">Label&nbsp;:</label><input type="text" name="label" id="f1_label" value="<?= $data['label'] ?>" maxlength="255" required><br>
<label for="f1_style">Style CSS de la slide&nbsp;:</label><br>
<textarea name="style" id="f1_style" maxlength="1024"><?= $data['style'] ?></textarea><br>
<label for="f1_title">Titre&nbsp;:</label><input type="text" name="title" id="f1_title" value="<?= $data['title'] ?>" maxlength="512" required><br>
<label for="f1_title_style">Style CSS du titre&nbsp;:</label><br>
<textarea name="title_style" id="f1_title_style" maxlength="1024"><?= $data['title_style'] ?></textarea><br>
<label for="f1_contain">Contenu&nbsp;:</label><br>
<textarea name="contain" id="f1_contain" maxlength="8192" rows="20" cols="500"><?= $data['contain'] ?></textarea><br>
<label for="f1_contain_style">Style CSS du contenu&nbsp;:</label><br>
<textarea name="contain_style" id="f1_contain_style" maxlength="1024"><?= $data['contain_style'] ?></textarea><br>
<input type="submit" value="Modifier">
</form>
<?php }
    }
?>

<h2>Ajout d'une slide</h2>
<label for="f_stylejs">Design prédéfini&nbsp;: </label>
<select id="f_stylejs"><option value="Blue Sky">Blue Sky</option><option value="Metal Kiwi">Metal Kiwi</option><option value="Light Pacific">Light Pacific</option><option value="Water Melon">Water Melon</option><option value="Breizh Gradient">Breizh Gradient</option></select>
<input type="button" value="Appliquer le style" onclick="setstyle('f_')"><br>
<form action="?add" method="post">
<input type="hidden" name="token" value="<?= $login['token'] ?>">
<label for="f_lang">Langue&nbsp;:</label>
<select id="f_lang" name="lang" autocomplete="off"><?= langs_html_opts($lang) ?></select><br>
<label for="f_todo">État&nbsp;:</label>
<select id="f_todo" name="todo" autocomplete="off"><?php foreach ($tr_todo as $key => $val)
{
    echo '<option value="'.$key.'">'.$val.'</option>';
} ?></select><br>
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
