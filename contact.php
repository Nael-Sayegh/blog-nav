<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require 'inclus/log.php';
require_once 'inclus/consts.php';
$tr = load_tr($lang, 'contact');
$titre = tr($tr,'title');
$cheminaudio = '/audio/sons_des_pages/harp_notif.mp3';
$stats_page = 'contact'; ?>
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
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
<?php
$teamlist = '';
$req = $bdd->query('SELECT * FROM `team` ORDER BY `age` DESC');
while($data = $req->fetch()) {
	switch($data['works']) {
		case '0': $workss = 'NVDA-FR'; break;
		case '1': $workss = $nomdusite; break;
		case '2': $workss = 'NVDA-FR & '.$nomdusite; break;
	}
	$teamlist .= '<li style="margin-top:8px;margin-bottom:8px;"><span class="rk rk_a">'.$data['name'].'</span> <span style="color:#505050;">(E'.$data['id'].')</span>, '.$data['status'].' ('.tr($tr, 'teamwork').' '.$workss.')<br /><span style="color:#047d04;padding-left:16px;">'.tr($tr, 'teamlist_item', array('age'=>intval((time()-$data['age'])/31557600), 'date'=>date('d/m/Y',$data['date']), 'time'=>date('H:i:s',$data['date']))).'</span><p style="margin-top:0;padding-left:16px;color:#202000;">'.$data['bio'].'</p></li>';
	
}
echo tr($tr,'maintext',array('teamlist'=>$teamlist,'lastv'=>$versionnom,'lastvdate'=>$versiondate,'lastvid'=>$derniereversion,'lastvopensource'=>$lastosv,'lastvu'=>$versionid));
?>
</div>
<?php include 'inclus/footer.php'; ?>
</body>
</html>