<?php $logonly = true;
$adminonly=true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';

$obcache = '';
if(isset($_GET['cache'])) {
	$cachedir = $_SERVER['DOCUMENT_ROOT'].'/cache/';
	
	ob_start();
	if($_GET['cache'] == 'all' or $_GET['cache'] == 'menu') {
		$file1 = fopen($cachedir.'menu_ulli_js.html', 'w');
		$file2 = fopen($cachedir.'menu_ulli_njs.html', 'w');
		$file3 = fopen($cachedir.'menu_select.html', 'w');
		$file4 = fopen($cachedir.'menu_search.html', 'w');
		$req = $bdd->query('SELECT * FROM `softwares_categories` ORDER BY name ASC');
		while($data = $req->fetch()) {
			fwrite($file1, '<li><a id="ulli_js_linkcat_'.$data['id'].'" href="/c?id='.$data['id'].'" role="menuitem">'.$data['name'].'</a></li>');
			fwrite($file2, '<li><a id="ulli_njs_linkcat_'.$data['id'].'" href="/c?id='.$data['id'].'" role="menuitem">'.$data['name'].'</a></li>');
			fwrite($file3, '<option id="sel_linkcat_'.$data['id'].'" value="/c?id='.$data['id'].'">'.$data['name'].'</option>');
			fwrite($file4, '<option value="'.$data['id'].'">'.$data['name'].'</option>');
		}
		fclose($file1);
		fclose($file2);
		fclose($file3);
		$req->closeCursor();
	}
	if($_GET['cache'] == 'all' or $_GET['cache'] == 'journal')
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
	if($_GET['cache'] == 'all' or $_GET['cache'] == 'slider')
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
	if($_GET['cache'] == 'all' or $_GET['cache'] == 'codestat')
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/codestat.php');
	if($_GET['cache'] == 'all' or $_GET['cache'] == 'langs')
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/langs_cache.php');
	if($_GET['cache'] == 'all' or $_GET['cache'] == 'accounts')
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/accounts_manager.php');
	
	$obcache = ob_get_contents();
	ob_end_clean();
	if(empty($obcache)) {
		header('Location: cache_update.php');
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Gestionnaire des caches &#8211; <?php print $nomdusite; ?></title>
		<?php print $cssadmin; ?>
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
		<h1>Gestionnaire des caches &#8211; <a href="/"><?php print $nomdusite; ?></a></h1>
		<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php'; ?>
<?php
if(!empty($obcache))
	echo '<fieldset><legend>Cachers\' stdout</legend>'.$obcache.'</fieldset><br>';
?>
		<a href="?cache=all">Mettre à jour tous les caches</a>
		<ul>
			<li><a href="?cache=menu">Mettre à jour le cache des menus (catégories)</a></li>
			<li><a href="?cache=journal">Mettre à jour le cache du journal des modifications</a></li>
			<li><a href="?cache=slider">Mettre à jour le cache du slider</a></li>
			<li><a href="?cache=codestat">Mettre à jour le cache des statistiques du code</a></li>
			<li><a href="?cache=langs">Mettre à jour le cache des langues</a></li>
			<li><a href="?cache=accounts">Lancer la tâche de gestion des comptes membre</a></li>
		</ul>
	</body>
</html>