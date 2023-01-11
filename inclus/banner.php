<header id="hautpage">
<h1><a href="/" title="<?php echo tr($tr0,'banner_homelink'); ?>"><?php print $nomdusite; ?></a></h1>
<?php
if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</header>
<?php include 'inclus/menu.php'; ?>