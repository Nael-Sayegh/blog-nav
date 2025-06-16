<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang, 'legal');
$title = tr($tr, 'title');
$stats_page = 'legal'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?php
echo tr($tr, 'maintext');
?>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>
