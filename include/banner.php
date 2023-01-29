<header id="hautpage">
<h1><a href="/" title="<?php echo tr($tr0,'banner_homelink'); ?>"><?php print $site_name; ?></a></h1>
<?php
if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'include/trident.php';
include 'include/loginbox.php';
include 'include/searchtool.php'; ?>
</header>
<?php include 'include/menu.php'; ?>