<?php

$params = '';
if (isset($_GET['id']) && preg_match('/[0-9]+/', (string) $_GET['id']))
{
    $params .= 'id='.$_GET['id'];
}
if (!empty($params))
{
    header('Location: /article.php?'.$params);
    exit();
}
