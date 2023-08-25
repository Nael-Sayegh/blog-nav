<?php
require_once 'api_inc.php';

$rdata = array('api_version'=>$api_version);

if(isset($_GET['g'])) {
	$domain = '';
	if(!isset($stats_page)) $stats_page = '';
	if(isDev()) $domain = 'dev';
	else if(!isDev()) $domain = 'prod';
	else if(isDev() && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], ONION_DOMAIN)) $domain = 'onion_dev';
	else if(!isDev() && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], ONION_DOMAIN)) $domain = 'onion';
	
	$date = date('Y-m-d');
	$xvisits = 0;
	$xvisitstoday = 0;
	$req = $bdd->prepare('SELECT `page`,`date`,`visits` FROM `count_visits` WHERE `domain`=? AND `date`>?');
	$req->execute(array($domain, time()-31557600));
	while($data = $req->fetch()) {
		$xvisits += $data['visits'];
		if($data['date'] == $date)
			$xvisitstoday += $data['visits'];
	}
	
	$xvisitors = 0;
	$xconn = 0;
	$xtoday = 0;
	$req = $bdd->prepare('SELECT `lastvisit` FROM `count_visitors` WHERE `domain`=?');
	$req->execute(array($domain));
	while($data = $req->fetch()) {
		if($data['lastvisit'] > strtotime('midnight'))
			$xtoday ++;
		if($data['lastvisit'] > time()-600)
			$xconn ++;
		$xvisitors ++;
	}
	
	$langs = array();
	$req = $bdd->prepare('SELECT * FROM `languages`');
	$req->execute();
	while($data = $req->fetch()) {
		$langs[] = array($data['id'], $data['lang'], $data['name'], $data['priority']);
	}
	
	$rdata['general'] = array(
		'name'=>$site_name,
		'slogan'=>$slogan,
		'lang'=>$lang,
		'version_id'=>$derniereversion,
		'version_name'=>$versionnom,
		'version_time'=>$versiondate,
		'maintenance'=>(isset($modemaintenance) and $modemaintenance),
		'domain'=>$domain,
		'visits_year'=>$xvisits,
		'visits_day'=>$xvisitstoday,
		'visitors_week'=>$xvisitors,
		'visitors_day'=>$xtoday,
		'languages'=>$langs
	);
}

if(isset($_GET['slides'])) {
	$slides = array();
	$req = $bdd->prepare('SELECT * FROM `slides` WHERE `published`=1');
	$req->execute();
	while($data = $req->fetch()) {
		$slides[] = array($data['id'], $data['lang'], $data['label'], $data['style'], str_replace('{{site}}', $site_name, $data['title']), $data['title_style'], str_replace('{{site}}', $site_name, $data['contain']), $data['contain_style'], $data['date']);
	}
	$rdata['slides'] = $slides;
}

if(isset($_GET['c'])) {
	$categories = array();
	if(empty($_GET['c'])) {
		$req = $bdd->prepare('SELECT * FROM `softwares_categories`');
		$req->execute();
	} else {
		$req = $bdd->prepare('SELECT * FROM `softwares` WHERE `category`=?');
		$req->execute(array($_GET['c']));
	}
	while($data = $req->fetch()) {
		$categories[] = array($data['id'], $data['name'], $data['text']);
	}
	$rdata['articles_categories'] = $categories;
}

if(isset($_GET['ca']) && !empty($_GET['ca'])) {
	$category_articles = array();
	$req = $bdd->prepare('SELECT * FROM `softwares` WHERE `category`=?');
	$req->execute(array($_GET['ca']));
	while($data = $req->fetch()) {
		$category_articles[] = array($data['id'], $data['name'], $data['date'], $data['hits'], $data['downloads'], $data['author'], $data['archive_after']);
	}
	$rdata['category_articles'] = $category_articles;
}

if(isset($_GET['a'])) {
	$articles = array();
	if(empty($_GET['a'])) {
		$req = $bdd->prepare('SELECT * FROM `softwares`');
		$req->execute();
	} else {
		$req = $bdd->prepare('SELECT * FROM `softwares` WHERE `id`=? LIMIT 1');
		$req->execute(array($_GET['a']));
	}
	while($data = $req->fetch()) {
		$articles[] = array($data['id'], $data['name'], $data['category'], $data['date'], $data['hits'], $data['downloads'], $data['author'], $data['archive_after']);
	}
	$rdata['articles'] = $articles;
}

if(isset($_GET['at'])) {
	$articles_tr = array();
	if(empty($_GET['at'])) {
		$req = $bdd->prepare('SELECT * FROM `softwares_tr` WHERE `published`=1');
		$req->execute();
	} else {
		$req = $bdd->prepare('SELECT * FROM `softwares_tr` WHERE `published`=1 AND `id`=? LIMIT 1');
		$req->execute(array($_GET['at']));
	}
	while($data = $req->fetch()) {
		$articles_tr[] = array($data['id'], $data['lang'], $data['sw_id'], $data['name'], $data['date'], $data['keywords'], $data['description'], $data['website'], $data['author']);
	}
	$rdata['articles_tr'] = $articles_tr;
}

if(isset($_GET['att'])) {
	$articles_tr_text = array();
	if(empty($_GET['att'])) {
		$req = $bdd->prepare('SELECT * FROM `softwares_tr` WHERE `published`=1');
		$req->execute();
	} else {
		$req = $bdd->prepare('SELECT * FROM `softwares_tr` WHERE `published`=1 AND `id`=? LIMIT 1');
		$req->execute(array($_GET['att']));
	}
	while($data = $req->fetch()) {
		$articles_tr_text[] = array($data['id'], $data['lang'], $data['sw_id'], $data['name'], $data['date'], $data['keywords'], $data['description'], $data['website'], $data['author'], $data['text']);
	}
	$rdata['articles_tr_text'] = $articles_tr_text;
}

if(isset($_GET['su'])) {
	$site_updates = array();
	$req = $bdd->prepare('SELECT * FROM `site_updates`');
	$req->execute();
	while($data = $req->fetch()) {
		$site_updates[] = array($data['id'], $data['name'], $data['text'], $data['date'], $data['authors'], json_decode($data['codestat']));
	}
	$rdata['site_updates'] = $site_updates;
}

if(isset($_GET['af'])) {
	$articles_files = array();
	if(empty($_GET['af'])) {
		$req = $bdd->prepare('SELECT * FROM `softwares_files`');
		$req->execute();
	} else {
		$req = $bdd->prepare('SELECT * FROM `softwares_files` WHERE `id`=? LIMIT 1');
		$req->execute(array($_GET['af']));
	}
	while($data = $req->fetch()) {
		$articles_files[] = array($data['id'], $data['sw_id'], $data['name'], $data['filetype'], $data['title'], $data['date'], $data['filesize'], $data['hits'], $data['label'], $data['md5'], $data['sha1']);
	}
	$rdata['articles_files'] = $articles_files;
}

if(isset($_GET['afl'])) {
	$articles_files_by_label = array();
	if(empty($_GET['afl'])) {
		$req = $bdd->prepare('SELECT * FROM `softwares_files`');
		$req->execute();
	} else {
		$req = $bdd->prepare('SELECT * FROM `softwares_files` WHERE `label`=? LIMIT 1');
		$req->execute(array($_GET['afl']));
	}
	while($data = $req->fetch()) {
		$articles_files_by_label[] = array($data['id'], $data['sw_id'], $data['name'], $data['filetype'], $data['title'], $data['date'], $data['filesize'], $data['hits'], $data['label'], $data['md5'], $data['sha1']);
	}
	$rdata['articles_files_by_label'] = $articles_files_by_label;
}

if(isset($_GET['am'])) {
	$articles_mirrors = array();
	if(empty($_GET['am'])) {
		$req = $bdd->prepare('SELECT * FROM `softwares_mirrors`');
		$req->execute();
	} else {
		$req = $bdd->prepare('SELECT * FROM `softwares_mirrors` WHERE `id`=? LIMIT 1');
		$req->execute(array($_GET['am']));
	}
	while($data = $req->fetch()) {
		$articles_mirrors[] = array($data['id'], $data['sw_id'], json_decode($data['links']), $data['title'], $data['date'], $data['hits'], $data['label']);
	}
	$rdata['articles_mirrors'] = $articles_mirrors;
}

if(isset($_GET['aml'])) {
	$articles_mirrors_by_label = array();
	if(empty($_GET['aml'])) {
		$req = $bdd->prepare('SELECT * FROM `softwares_mirrors`');
		$req->execute();
	} else {
		$req = $bdd->prepare('SELECT * FROM `softwares_mirrors` WHERE `label`=? LIMIT 1');
		$req->execute(array($_GET['aml']));
	}
	while($data = $req->fetch()) {
		$articles_mirrors_by_label[] = array($data['id'], $data['sw_id'], json_decode($data['links']), $data['title'], $data['date'], $data['hits'], $data['label']);
	}
	$rdata['articles_mirrors_by_label'] = $articles_mirrors_by_label;
}

print(json_encode($rdata));
?>
