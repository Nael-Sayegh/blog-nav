<?php
if(!isset($_GET['id'])) {header('Location: /');die();}
set_include_path($_SERVER['DOCUMENT_ROOT']);
require 'inclus/log.php';
require_once 'inclus/consts.php';
$req = $bdd->prepare('SELECT * FROM softwares_categories WHERE id=?');
$req->execute(array($_GET['id']));
$data = $req->fetch();
if(!$data){header('Location: /');die();}
$tr = load_tr($lang, 'cat');
$cat_id = $data['id'];
$titre = str_replace('{{site}}', $nomdusite, $data['name']);
$cat_text = $data['text'];

$args['id'] = $cat_id;
$cheminaudio='/audio/categories/'.$cat_id.'.mp3';
$stats_page='cat'; ?>
<!doctype html>
<html lang="<?php echo $lang; ?>">
<?php require_once 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="<?php echo tr($tr0,'banner_homelink'); ?>"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</div>
<?php include('inclus/son.php');
include('inclus/menu.php'); ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
<?php
echo str_replace('{{site}}', $nomdusite, $cat_text);
#$req = $bdd->prepare('SELECT * FROM softwares WHERE category=? ORDER BY date DESC');
#$req->execute(array($cat_id));

$entries = [];
$req = $bdd->prepare('
	SELECT `softwares_tr`.`id`, `softwares_tr`.`lang`, `softwares_tr`.`name`, `softwares_tr`.`description`, `softwares_tr`.`sw_id`, `softwares`.`hits`, `softwares`.`downloads`, `softwares`.`date`
	FROM `softwares`
	LEFT JOIN `softwares_tr` ON `softwares`.`id`=`softwares_tr`.`sw_id`
	WHERE `softwares`.`category`=? AND `softwares_tr`.`published`=1
	ORDER BY `softwares`.`date` DESC');
$req->execute(array($cat_id));
while($data = $req->fetch()) {
	if(!isset($entries[$data['sw_id']]))
		$entries[$data['sw_id']] = array('hits'=>$data['hits'], 'dl'=>$data['downloads'], 'date'=>$data['date'], 'trs'=>array());
	$entries[$data['sw_id']]['trs'][$data['lang']] = array('id'=>$data['id'], 'title'=>$data['name'], 'desc'=>$data['description']);
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
	
	echo '<div class="software"><a href="a?id='.$sw_id.'" class="software_title" role="heading" aria-level="2">'.str_replace('{{site}}', $nomdusite, $entry['trs'][$entry_tr]['title']).'</a>';
	echo '<p>'.str_replace('{{site}}', $nomdusite, $entry['trs'][$entry_tr]['desc']).'<br /><span class="software_hits">'.tr($tr,'hits',array('hits'=>$entry['hits'])).'</span> <span class="software_date">('.tr($tr,'date',array('date'=>strftime(tr($tr0,'fndatetime'),$entry['date']))).')</span></p></div>';
}
?>
</div>
<?php include 'inclus/footer.php'; ?>
</body>
</html>
