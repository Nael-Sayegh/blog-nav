<?php
set_include_path($_SERVER['DOCUMENT_ROOT']);
$stats_page = 'index';
require_once('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang, 'index');
$title = tr($tr, 'title'); ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<?php $css_path .= '<link rel="stylesheet" href="/css/slider.css">';
require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php'); ?>
<div id="container">
<?php
if ((isset($_COOKIE['infosdef']) && $_COOKIE['infosdef'] === '1') || !isset($_COOKIE['infosdef']))
{
    include('cache/slider_'.$lang.'.html');
}
else
{
    echo '<h2>'.tr($tr, 'sliderinactitle').'</h2>'.tr($tr, 'sliderinactext');
    include('Slider.php');
}
?>
<main id="contenu">
<?php if (isset($_GET['contactconfirm']) && $_GET['contactconfirm'])
{
    echo '<p role="alert" id="contactconfirm">'.tr($tr, 'mailconfirmtext').'</p>';
} ?>
<h2 style="margin:0;"><?= tr($tr, 'texttitle') ?></h2>
<?php if (date('m') === '01')
{
    echo str_replace('{{year}}', date('Y'), tr($tr, 'happynewyear'));
} ?>
<?= tr($tr, 'maintext', ['lastosv' => $lastosv]) ?>
</main>
</div>
<?php if ((isset($_COOKIE['infosdef']) && $_COOKIE['infosdef'] === '1') || !isset($_COOKIE['infosdef']))
{ ?>
<script src="/scripts/jquery.js"></script>
<script src="/scripts/slider.js"></script>
<?php } ?>
<?php require_once('include/footer.php'); ?>
</body>
</html>
