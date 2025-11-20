<?php
declare(strict_types=1);

$logonly = true;
$adminonly = true;
$justbn = true;
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');
requireAdminRight('view_phpinfo');
echo phpinfo();
echo '<a href="index.php">Retour</a>';
