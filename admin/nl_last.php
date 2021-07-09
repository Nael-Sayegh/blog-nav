<?php
require('dbconnect.php');
$logonly = true;
$adminonly=true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';

$req = $bdd->prepare('UPDATE `newsletter_mails` SET `lastmail`=0');
$req->execute();
echo 'OK';
?>