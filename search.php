<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('inclus/log.php');
require_once('inclus/consts.php');
$tr = load_tr($lang, 'search');
$titre=tr($tr,'title');
$cheminaudio="/audio/sons_des_pages/recherche.mp3";
$stats_page='recherche';
$chemincss .= '<link rel="stylesheet" href="/css/search.css">';
$searchterms = '';
if(isset($_GET['q']) and $_GET['q'] != '' and strlen($_GET['q']) <= 255) {
	$searchterms = $_GET['q'];
	$args['q'] = $searchterms;
	if($searchterms == "﷐") {
		switch($lang) {
			case 'en': header('Location: https://en.wikipedia.org/wiki/Basilisk');break;
			case 'eo': header('Location: https://eo.wikipedia.org/wiki/Bazilisko_(mitologio)');break;
			case 'es': header('Location: https://es.wikipedia.org/wiki/Basilisco_(criatura_mitol%C3%B3gica)');break;
			case 'fr': header('Location: https://fr.wikipedia.org/wiki/Basilic_(mythologie)');break;
		}
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php require_once('inclus/header.php'); ?>
<body>
<?php require_once('inclus/banner.php');
require_once('inclus/son.php'); ?>
<main id="container">
<h1 id="contenu"><?php
if(!empty($searchterms))
	echo tr($tr,'title2',array('terms'=>htmlspecialchars($_GET['q'])));
?></h1>
<?php
if(!empty($searchterms)) {
	$req = $bdd->query('SELECT * FROM `softwares_categories`');
	$cats = array();
	while($data = $req->fetch()) {$cats[$data['id']] = $data['name'];}
	
	$atime = microtime();
	$terms = explode(' ', $searchterms);
	$results = array();
	$where = '';
	$cat = array();
	if(isset($_GET['c']) and !empty($_GET['c']) and count($_GET['c']) <= count($cats)) {
		foreach($_GET['c'] as &$val) {
			if(!empty($val))
				$cat[] = $val;
		}
		
		if(!empty($cat))
			$where = ' WHERE `category`=?';
		if(count($cat) > 1)
			$where .= str_repeat(' OR `category`=?', count($cat)-1);
	}
	
	$entries = [];
	$req = $bdd->prepare('
		SELECT `softwares_tr`.`id`, `softwares_tr`.`lang`, `softwares_tr`.`name`, `softwares_tr`.`keywords`, `softwares_tr`.`description`, `softwares_tr`.`sw_id`, `softwares`.`category`, `softwares`.`hits`, `softwares`.`downloads`
		FROM `softwares`
		LEFT JOIN `softwares_tr` ON `softwares`.`id`=`softwares_tr`.`sw_id`'
		.$where
	);
	$req->execute($cat);
	while($data = $req->fetch()) {
		if(!isset($entries[$data['sw_id']]))
			$entries[$data['sw_id']] = array('cat'=>$data['category'], 'hits'=>$data['hits'], 'dl'=>$data['downloads'], 'trs'=>array());
		$entries[$data['sw_id']]['trs'][$data['lang']] = array('id'=>$data['id'], 'title'=>$data['name'], 'tags'=>$data['keywords'], 'desc'=>$data['description']);
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
		
		$tags = explode(' ', $entry['trs'][$entry_tr]['tags']);
		$pts = intval($terms[0] == '*');
		if($pts) array_shift($tags);
		foreach($terms as &$term) {
			$imp = 3;
			foreach($tags as &$tag) {
				if($term == $tag)
					$pts += 12+$imp**2;
				else {
					$lev = levenshtein($term, $tag);
					if($lev <= 2)
						$pts += 5-$lev+$imp;
					$lev = levenshtein(metaphone($term), metaphone($tag));
					if($lev < 2)
						$pts += 5-$lev+$imp;
				}
			}
			if($imp > 0) $imp --;
			unset($tag);
		}
		unset($term);
		if($pts > 0)
			$results[] = array('id'=>$sw_id, 'title'=>$entry['trs'][$entry_tr]['title'], 'cat'=>$entry['cat'], 'desc'=>$entry['trs'][$entry_tr]['desc'], 'hits'=>$entry['hits'], 'dl'=>$entry['dl'], 'pts'=>$pts);
	}
	// remove the first occurence of v in a
	function array_remove($a, $v) {
		$r = array();
		$o = false;
		foreach($a as &$k) {
			if($k != $v or $o)
				$r[] = $k;
			else
				$o = true;
		}
		unset($k);
		return $r;
	}
	$btime = microtime() - $atime;
	if(count($results) == 0)
		echo '<span id="log">'.tr($tr,'noresult',array('terms'=>'<span class="log_quote">'.htmlentities($searchterms).'</span>')).'</span>';
	else
		echo '<p id="timelog">'.tr($tr,'found',array('count'=>count($results),'time'=>numberlocale(intval($btime*1000000)/1000))).'</p>';
	while(count($results) > 0) {
		$max = array('pts'=>0);
		foreach($results as &$rs) {
			if($rs['pts'] > $max['pts'])
				$max = $rs;
		}
		unset($rs);
		$results = array_remove($results, $max);
		echo '<div class="result"><a href="/article.php?id='.$max['id'].'"><h2 class="rs_title">'.$max['title'].'</h2></a><span class="rs_cat">('.$cats[$max['cat']].')</span>'. ($dev?'<span class="rs_pts">'.$max['pts'].'</span>':'') .'<p class="rs_text">'.$max['desc'].'</p><span class="rs_meta">';
		echo tr($tr,'result_hits',array('hits'=>$max['hits'],'dl'=>$max['dl'])).'</span></div>';
	}
}
?>
</main>
<?php require_once('inclus/footer.php'); ?>
</body>
</html>