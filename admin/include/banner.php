<h1><?php if (isset($titlePAdm))
{
    echo $titlePAdm.' - ';
} ?><a href="/admin">Administration</a> - <a href="/"><?php print $site_name; ?></a></h1>
<h2>Connecté en tant que <?php print $admin_name; ?></h2>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/include/loginbox.php');
require_once('include/menu.php'); ?>