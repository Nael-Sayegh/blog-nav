<?php
if(!isset($_GET['id'])) {header('Location: /');die();}
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$req = $bdd->prepare('SELECT * FROM softwares_categories WHERE id=?');
$req->execute(array($_GET['id']));
$data = $req->fetch();
if(!$data){header('Location: /');die();}
$tr = load_tr($lang, 'cat');
$cat_id = $data['id'];
$title = str_replace('{{site}}', $site_name, $data['name']);
$cat_text = $data['text'];

$args['id'] = $cat_id;
$sound_path='/audio/categories/'.$cat_id.'.mp3';
$stats_page='cat'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?php
echo str_replace('{{site}}', $site_name, $cat_text);
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
	
	echo '<div class="software"><a href="a'.$sw_id.'" class="software_title" role="heading" aria-level="2">'.str_replace('{{site}}', $site_name, $entry['trs'][$entry_tr]['title']).'</a>';
	echo '<p>'.str_replace('{{site}}', $site_name, $entry['trs'][$entry_tr]['desc']).'<br><span class="software_hits">'.tr($tr,'hits',array('hits'=>$entry['hits'])).'</span> <span class="software_date">('.tr($tr,'date',array('date'=>strftime(tr($tr0,'fndatetime'),$entry['date']))).')</span></p></div>';
}
?>
</main>
<script>
<?php $php_ulli_id="ulli_linkcat_".$cat_id; $php_sel_id="sel_linkcat_".$cat_id; ?>
	var ulli_id=<?php echo json_encode($php_ulli_id); ?>;
	var sel_id=<?php echo json_encode($php_sel_id); ?>;
	if(document.getElementById(ulli_id))
		document.getElementById(ulli_id).setAttribute("aria-current", "page");
	if(document.getElementById(sel_id))
		document.getElementById(sel_id).setAttribute("aria-current", "page");
</script>
<?php require_once('include/footer.php'); ?>
</body>
</html>