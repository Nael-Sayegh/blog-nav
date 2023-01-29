<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title='Liste des articles';
$stats_page = 'art-list';
$sound_path='/audio/page_sounds/article.mp3';
$cat = array();
$req = $bdd->query('SELECT * FROM `softwares_categories`');
while($data = $req->fetch()) {
$cat[$data['id']] = $data['name'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<form action="/art_list.php" method="get">
<label for="f1_sort">Trier par&nbsp;:</label>
<select name="sort" id="f1_sort">
<option value="id">Numéro d'article</option>
<option value="nom">Ordre alphabétique</option>
<option value="date">Date de mise à jour</option>
</select>
<input type="submit" value="Trier" style="cursor:pointer;">
</form>
<ul>
<?php
if(isset($_GET['sort'])) {
	switch($_GET['sort']) {
		case 'id': $order = 'id'; break;
		case 'nom': $order = 'name'; break;
		case 'date': $order = 'date DESC'; break;
	}
}
else $order = 'id';

$req = $bdd->prepare('
	SELECT `softwares_tr`.`lang`, `softwares_tr`.`name`, `softwares_tr`.`sw_id`, `softwares`.`category`
	FROM `softwares`
	LEFT JOIN `softwares_tr` ON `softwares`.`id`=`softwares_tr`.`sw_id`
	ORDER BY `softwares`.'.$order);
$req->execute();
while($data = $req->fetch()) {
	if(!isset($entries[$data['sw_id']]))
		$entries[$data['sw_id']] = array('cat'=>$data['category'], 'trs'=>array());
	$entries[$data['sw_id']]['trs'][$data['lang']] = array('title'=>$data['name']);
}

foreach($entries as $sw_id => $entry) {
	$entry_tr = '';
	if(array_key_exists($lang, $entry['trs']))
		$entry_tr = $lang;
	else {
		foreach($langs_prio as &$i_lang) {
			if(array_key_exists($i_lang, $entry['trs'])) {
				$entry_tr = $i_lang;
				break;
			}
		}
	}
	unset($i_lang);
	if(empty($entry_tr))// Error: sw has no translations
		continue;
	
	echo '<li><a href="/a?id='.$sw_id.'">A'.$sw_id.'&nbsp;: '.str_replace('{{site}}', $site_name, $entry['trs'][$entry_tr]['title']).'</a> (<a href="/c?id='.$entry['cat'].'">'.$cat[$entry['cat']].'</a>)</li>';
}
$req->closeCursor();
?>
</ul>
<p><b><?php echo count($entries); ?></b> articles trouvés</p>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>