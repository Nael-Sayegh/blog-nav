<?php

$pages = ['/', '/newsletter.php', '/rss_feed.xml', '/history.php', '/settings.php', '/gadgets.php', '/contact.php', '/contact_form.php', '/privacy.php'];
if (isset($_GET['d']) && !empty($_GET['d']) && (in_array($_GET['d'], $pages) || preg_match('#^/c[0-9]{1,3}$#', (string) $_GET['d'])))
{
    header('Location: '.$_GET['d']);
    exit();
}
else
{
    header('Location: /');
    exit();
}
