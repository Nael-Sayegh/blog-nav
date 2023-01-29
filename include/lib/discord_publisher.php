<?php
$document_root = __DIR__.'/../..';
require_once $document_root.'/include/config.local.php';
$url = DISCORD_WEBHOOK_URL;
function send_discord($message)
{
$data = array(
'content' => $message,
'username' => 'ProgAccess',
);
$context = array(
'http' => array(
'method' => 'POST',
'header' => "Content-type: application/json\r\n",
'content' => json_encode($data),
)
);
$context  = stream_context_create($context);
$result = @file_get_contents($url, false, $context);
if($result === false) {
return false;
}
return true;
}
?>