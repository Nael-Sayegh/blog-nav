<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang, 'settings');
if(isset($_GET['act']) and $_GET['act'] == 'form') {
	$menu = '0';
	if(isset($_POST['menu']))
		$menu = '1';
	setcookie('menu', $menu, time()+31536000, null, null, false, true);
	
	$fontsize = '16';
	if(isset($_POST['fontsize']) and in_array($_POST['fontsize'], ['11','16','20','24']))
		$fontsize = $_POST['fontsize'];
	setcookie('fontsize', $fontsize, time()+31536000, null, null, false, true);
	
	$audio = '0';
	if(isset($_POST['audio']) and in_array($_POST['audio'],['0','1','2','3','4','5','6','7','8','9','10']))
		$audio = $_POST['audio'];
	setcookie('audio', $audio, time()+31536000, null, null, false, true);
	
	$date = '0';
	if(isset($_POST['date']))
		$date = '1';
	setcookie('date', $date, time()+31536000, null, null, false, true);
	
	$infosdef = '0';
	if(isset($_POST['infosdef']))
		$infosdef = '1';
	setcookie('infosdef', $infosdef, time()+31536000, null, null, false, true);
	
	
	if($logged and isset($_POST['token']) and $_POST['token'] == $login['token']) {
		$settings = json_decode($login['settings'], true);
		$settings['menu'] = $menu;
		$settings['fontsize'] = $fontsize;
		$settings['audio'] = $audio;
		$settings['date'] = $date;
		$settings['infosdef'] = $infosdef;
		$settings['IExplore'] = $IExplore;
		$req = $bdd->prepare('UPDATE `accounts` SET `settings`=? WHERE `id`=? LIMIT 1');
		$req->execute(array(json_encode($settings), $login['id']));
	}
	
	header('Location: /');
}
elseif(isset($_GET['act']) and $_GET['act'] == '0') {
	if($logged and isset($_POST['token']) and $_POST['token'] == $login['token']) {
$settings = json_decode($login['settings'], true);
$settings['menu'] = '0';
$settings['fontsize'] = '16';
$settings['audio'] = '0';
$settings['date'] = '0';
$settings['infosdef'] = '1';
$settings['IExplore'] = '0';
$req = $bdd->prepare('UPDATE `accounts` SET `settings`=? WHERE `id`=? LIMIT 1');
$req->execute(array(json_encode($settings), $login['id']));
}
else {
setcookie("menu","",0,"/","",0);
setcookie("fontsize","",0,"/","",0);
setcookie("audio","",0,"/","",0);
setcookie("date","",0,"/","",0);
setcookie("infosdef","",0,"/","",0);
setcookie("IExplore","",0,"/","",0); }
header('Location: /'); }
$stats_page='parametres';
$title=tr($tr,'title');
$sound_path='/audio/page_sounds/settings.mp3'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?php echo tr($tr,'maintext'); ?>
<form action="?act=form" method="post" aria-label="Options">
<?php
if($logged)
	echo '<input type="hidden" name="token" value="'.$login['token'].'">';
$menu = isset($_COOKIE['menu']) ? $_COOKIE['menu'] : '0';
$audio = isset($_COOKIE['audio']) ? $_COOKIE['audio'] : 0;
$date = isset($_COOKIE['date']) ? $_COOKIE['date'] : 0;
$infosdef = isset($_COOKIE['infosdef']) ? $_COOKIE['infosdef'] : 1;
?>
<h3><?php echo tr($tr,'gui'); ?></h3>
<label for="choix_menu"><?php echo tr($tr,'combomenu'); ?></label>
<input type="checkbox" id="choix_menu" name="menu"<?php if($menu==1)echo ' checked="checked"'; ?>><br>
<label for="f_fontsize"><?php echo tr($tr,'textsize'); ?></label>
<?php if(isset($_COOKIE['fontsize'])) $fontsize = $_COOKIE['fontsize']; else $fontsize = '16'; ?>
<select id="f_fontsize" name="fontsize">
<option value="11" style="font-size: 11px;" <?php if($fontsize=='11')echo'selected';?>><?php echo tr($tr,'11'); ?></option><option value="16" style="font-size: 16px;" <?php if($fontsize=='16')echo'selected';?>><?php echo tr($tr,'16'); ?></option><option value="20" style="font-size: 20px;" <?php if($fontsize=='20')echo'selected';?>><?php echo tr($tr,'20'); ?></option><option value="24" style="font-size: 24px;" <?php if($fontsize=='24')echo'selected';?>><?php echo tr($tr,'24'); ?></option></select><br>
<h3><?php echo tr($tr,'audio'); ?></h3>
<label for="f_audio"><?php echo tr($tr,'soundsvolume'); ?></label>
<input type="range" min="0" max="10" step="1" defaultValue="0" value="<?php echo htmlspecialchars($audio); ?>" name="audio" id="f_audio"><br>
<h3><?php echo tr($tr,'other'); ?></h3>
<label for="f_date"><?php echo tr($tr,'datetime'); ?></label>
<input type="checkbox" id="f_date" name="date" <?php if($date==1) echo 'checked="checked"'; ?>><br>
<label for="f_slideridcc"><?php echo tr($tr,'slider'); ?></label>
<input type="checkbox" id="f_slideridcc" name="infosdef" <?php if($infosdef==1) echo 'checked="checked"'; ?>><br>
<input type="submit" value="<?php echo tr($tr,'savebtn'); ?>">
</form>
<form action="?act=0" method="post" aria-label="Réinitialiser">
<?php
if($logged)
	echo '<input type="hidden" name="token" value="'.$login['token'].'">';
$menu = isset($_COOKIE['menu']) ? $_COOKIE['menu'] : '0';
$audio = isset($_COOKIE['audio']) ? $_COOKIE['audio'] : 0;
$date = isset($_COOKIE['date']) ? $_COOKIE['date'] : 0;
$infosdef = isset($_COOKIE['infosdef']) ? $_COOKIE['infosdef'] : 1;
?>
<input type="submit" value="<?php echo tr($tr,'resetbtn'); ?>">
</form>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>