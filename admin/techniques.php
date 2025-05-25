<?php

$logonly = true;
$adminonly = true;
$justpa = true;
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');
requireAdminRight('view_phpinfo');
echo phpinfo();
echo '<a href="index.php">Retour</a>';
