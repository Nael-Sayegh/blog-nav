<?php
$logonly = true;
$adminonly = true;
$justpa = true;
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/include/consts.php');
$url = 'adminer.php';

$data = array(
    'server' => 'localhost',
    'username' => DB_USER,
    'password' => DB_PSW
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) { print('Erreur de redirection'); }

echo $result;
?>