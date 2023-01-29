<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
$stats_page = 'index';
require_once('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang, 'index');
$title = tr($tr,'title');
$sound_path='/audio/page_sounds/accueil.mp3'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php $css_path .= '<link rel="stylesheet" href="/css/slider.css">';
require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<div id="container">
<?php
if((isset($_COOKIE['infosdef']) and $_COOKIE['infosdef'] == '1') or !isset($_COOKIE['infosdef'])) {
include('cache/slider_'.$lang.'.html'); }
else {
	echo '<h2>'.tr($tr,'sliderinactitle').'</h2>'.tr($tr,'sliderinactext');
	include('Slider.php');
}
?>
<main id="contenu">
<?php if(isset($_GET['contactconfirm']) && $_GET['contactconfirm'] == 1) echo '<p role="alert">'.tr($tr,'mailconfirmtext').'</p>'; ?>
<h2 style="margin:0;"><?php echo tr($tr,'texttitle'); ?></h2>
<?php if(date('m') == '01') echo str_replace('{{year}}', date('Y'), tr($tr,'happynewyear')); ?>
<?php echo tr($tr,'maintext',array('lastosv'=>$lastosv)); ?>
</main>
</div>
<script src="/scripts/jquery.js"></script>
<script src="/scripts/slider.js"></script>
<?php require_once('include/footer.php'); ?>
</body>
</html>