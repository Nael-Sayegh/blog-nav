<?php

declare(strict_types=1);

$params = '';
if (isset($_GET['label']) && preg_match('/^[a-z0-9_]+$/i', (string) $_GET['label']))
{
    $params .= 'label='.$_GET['label'];
}
elseif (isset($_GET['id']) && preg_match('/^\d+$/', (string) $_GET['id']))
 {
     $params .= 'id='.$_GET['id'];
 }

if ($params !== '' && $params !== '0')
{
    header('Location: /article.php?'.$params);
    exit();
}
