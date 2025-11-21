<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require(__dir__ . 'include/log.php');
require_once(__dir__ . 'include/consts.php');
$tr = load_tr($lang, 'contact');
$title = tr($tr, 'title');
$stats_page = 'contact'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<?php require_once(__dir__ . 'include/header.php'); ?>
<body>
<?php require_once(__dir__ . 'include/banner.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?php
echo tr($tr, 'maintext');
?>
</main>
<?php require_once(__dir__ . 'include/footer.php'); ?>
</body>
</html>
