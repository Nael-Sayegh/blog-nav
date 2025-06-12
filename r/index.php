<?php

$params = '';
if (isset($_GET['p']) && preg_match('/[0-9a-z_]+/', (string) $_GET['p']))
{
    $params .= 'p='.$_GET['p'];
}
elseif (isset($_GET['i']) && preg_match('/[0-9]+/', (string) $_GET['i']))
{
    $params .= 'id='.$_GET['i'];
}
elseif (isset($_GET['id']) && preg_match('/[0-9]+/', (string) $_GET['id']))
{
    $params .= 'id='.$_GET['id'];
}
if (!empty($params))
{
    if (isset($_GET['m']))
    {
        $params .= '&m';
    }
    header('Location: /r.php?'.$params);
    exit();
}
