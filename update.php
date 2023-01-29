<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title='Mise à jour du site';
$stats_page = 'update';
$sound_path='/audio/page_sounds/V.mp3'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?php
if(isset($_GET['id'])) {
	$req = $bdd->prepare('SELECT * FROM site_updates WHERE id=? LIMIT 1');
	$req->execute(array($_GET['id']));
	if($data = $req->fetch()) {
$versionxx = substr($data['name'],1);
echo '<h2>'.$versionxx.' (V'.$data['id'].')</h2><a href="/update.php">Liste des versions</a><br>';
$req2 = $bdd->prepare('SELECT * FROM `site_updates` WHERE `id`<? ORDER BY `date` DESC LIMIT 1');
		$req2->execute(array($data['id']));
if($data2 = $req2->fetch()) {
$versionxx2 = substr($data2['name'],1);
echo '<a href="/update.php?id='.$data2['id'].'">Version précédente&nbsp;: '.$versionxx2.' (V'.$data2['id'].')</a> ('.strftime(tr($tr0,'fndatetime'),$data2['date']).')<br>'; }
$req3 = $bdd->prepare('SELECT * FROM `site_updates` WHERE `id`>? ORDER BY `date` ASC LIMIT 1');
$req3->execute(array($data['id']));
if($data3 = $req3->fetch()) {
$versionxx3 = substr($data3['name'],1);
echo '<a href="/update.php?id='.$data3['id'].'">Version suivante&nbsp;: '.$versionxx3.' (V'.$data3['id'].')</a> ('.date('d/m/Y H:i', $data3['date']).')<br>'; }
echo '<p>Par '.$data['authors'].' ('.strftime(tr($tr0,'fndatetime'),$data['date']).')</p>'.str_replace('{{site}}', $site_name, $data['text']);
		$codestat = json_decode($data['codestat']);
		if(isset($codestat[0]) and isset($codestat[1]) and isset($codestat[2]) and $codestat[0] != -1 and $codestat[1] != -1 and $codestat[2] != -1) {
			echo '<hr><p>À cette version, le code du site est composé de <strong>'.$codestat[0].'</strong> fichiers, <strong>'.$codestat[1].'</strong> lignes, soit <strong>'.$codestat[2].'</strong> octets ('.human_filesize($codestat[2]).'o).<br>Seuls les fichiers PHP, HTML, CSS, JS, XML et texte brut sont pris en compte. Les fichiers dont nous ne sommes pas les auteurs ne sont pas comptés (bibliothèques, outils), ni les fichiers dynamiques (caches générés automatiquement), ni les fichiers de traduction (ne contenant que du texte).</p>';
		}
	} 
} else {
	if($dev == true) {include('ChangeLog_Dev.html');}
	$req = $bdd->prepare('SELECT * FROM `site_updates` ORDER BY `date` DESC');
	$req->execute();
	echo '<ul><li>Numéro de version [identifiant de version] (date)&nbsp;:</li>';
	while($data = $req->fetch()) {
		$versionxx = substr($data['name'],1);
		echo '<li><a href="/update.php?id='.$data['id'].'"><b>'.$versionxx.'</b> [V'.$data['id'].']</a> ('.strftime(tr($tr0,'fndatetime'),$data['date']).')</li>';
	}
	echo '</ul>';
$req->closeCursor();
}
?>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>