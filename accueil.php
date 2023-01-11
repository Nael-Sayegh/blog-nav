<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
$stats_page = 'accueil';
require_once('inclus/log.php');
require_once('inclus/consts.php');
$tr = load_tr($lang, 'accueil');
$titre = tr($tr,'title');
$cheminaudio='/audio/sons_des_pages/accueil.mp3'; ?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php $chemincss .= '<link rel="stylesheet" href="/css/slider.css">';
require_once('inclus/header.php'); ?>
<body>
<?php require_once('inclus/banner.php');
require_once('inclus/son.php'); ?>
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
<?php require_once('inclus/footer.php'); ?>
</body>
</html>