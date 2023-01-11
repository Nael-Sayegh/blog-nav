<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require('inclus/log.php');
require_once('inclus/consts.php');
$tr = load_tr($lang, 'contact');
$titre = tr($tr,'title');
$cheminaudio = '/audio/sons_des_pages/harp_notif.mp3';
$stats_page = 'contact'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php require_once('inclus/header.php'); ?>
<body>
<?php require_once('inclus/banner.php');
require_once('inclus/son.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $titre; ?></h1>
<?php
$teamlist = '';
$req = $bdd->query('SELECT * FROM `team` WHERE `works` = 1 OR `works` = 2');
while($data = $req->fetch()) {
	$teamlist .= '<li style="margin-top:8px;margin-bottom:8px;"><span class="rk rk_a">'.$data['short_name'].'</span> <span style="color:#505050;">(E'.$data['id'].')</span>, '.$data['status'].'<br><span style="color:#047d04;padding-left:16px;">'.tr($tr, 'teamlist_item', array('age'=>intval((time()-$data['age'])/31557600), 'date'=>date('d/m/Y',$data['date']))).'</span><p style="margin-top:0;padding-left:16px;color:#202000;">'.$data['bio'].'</p></li>';
	
}
echo tr($tr,'maintext',array('teamlist'=>$teamlist,'lastv'=>$versionnom,'lastvdate'=>$versiondate,'lastvid'=>$derniereversion,'lastvopensource'=>$lastosv,'lastvu'=>$versionid));
?>
</main>
<?php require_once('inclus/footer.php'); ?>
</body>
</html>