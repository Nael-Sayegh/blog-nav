<?php
$pages = array('/', '/newsletter.php', 'rss_feed.xml', 'history.php', 'update.php', '/settings.php', '/gadgets.php', '/contact.php', '/contact_form.php', '/privacy.php');
if(isset($_GET['d']) and !empty($_GET['d']) and (in_array($_GET['d'], $pages) or preg_match('#^/c\\?id=[0-9]{1,3}$#', $_GET['d']))) {
header('Location: '.$_GET['d']);
} else {
header('Location: /'); }
?>