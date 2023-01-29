<?php
require('dbconnect.php');
$logonly = true;
$adminonly=true;
$justpa = true;
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');

$req = $bdd->prepare('UPDATE `newsletter_mails` SET `lastmail`=0');
$req->execute();
echo 'OK';
?>