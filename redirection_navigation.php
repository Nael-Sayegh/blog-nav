<?php
$pages = array('/', '/newsletter.php', 'journal_modif.xml', 'journal_modif.php', 'update.php', '/param.php', '/gadgets.php', '/contact.php', '/contacter.php', '/confidentialite.php');
if(isset($_GET['d']) and !empty($_GET['d']) and (in_array($_GET['d'], $pages) or preg_match('#^/c\\?id=[0-9]{1,3}$#', $_GET['d']))) {
header('Location: '.$_GET['d']);
} else {
header('Location: /'); }
?>