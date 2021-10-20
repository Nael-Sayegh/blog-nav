<?php
if(date('dm') == '0104' and !isset($_GET['noredirfool'])) {
	header('Location: https://www.nvda-fr.org?noredirfool');
	exit();
}
set_include_path($_SERVER['DOCUMENT_ROOT']);
$stats_page = 'accueil';
include_once 'inclus/log.php';
require_once 'inclus/consts.php';
$tr = load_tr($lang, 'accueil');
$titre = tr($tr,'title');
$cheminaudio='/audio/sons_des_pages/accueil.mp3'; ?>
<!doctype html>
<html lang="<?php echo $lang; ?>">
<?php $chemincss .= '<link rel="stylesheet" href="/css/slider.css" />';
include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="<?php echo tr($tr0,'banner_homelink'); ?>"><?php print $nomdusite; ?></a></h1>
<?php
if(date('dm') == '0104' and isset($_GET['noredirfool'])) {
	echo 'Eh non, NVDA-FR n\'est plus, la maison mère a pris le contrôle !!!!! 🐟🐟 Jetez un oeil à la date 😉, aller, un petit indice, on est le 1er jour du 4ème mois de l\'année...';
}
if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</div>
<?php include 'inclus/son.php';
include 'inclus/menu.php'; ?>
<div id="container">
<?php
if((isset($_COOKIE['infosdef']) and $_COOKIE['infosdef'] == '1') or !isset($_COOKIE['infosdef'])) {
include 'cache/slider_'.$lang.'.html'; }
else {
	echo '<h2>'.tr($tr,'sliderinactitle').'</h2>'.tr($tr,'sliderinactext');
	include('Slider.php');
}
?>
<div id="contenu" role="main">
<?php if($_GET['contactconfirm']) echo '<p role="alert">'.tr($tr,'mailconfirmtext').'</p>'; ?>
<h2 style="margin:0;"><?php echo tr($tr,'texttitle'); ?></h2>
<?php if(date('m') == '01') echo str_replace('{{year}}', date('Y'), tr($tr,'happynewyear')); ?>
<?php echo tr($tr,'maintext',array('lastosv'=>$lastosv)); ?>
</div>
</div>
<script src="/scripts/jquery.js"></script>
<script src="/scripts/slider.js"></script>
<?php include 'inclus/footer.php'; ?>
</body>
</html>
