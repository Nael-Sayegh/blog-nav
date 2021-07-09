<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/config.local.php';
require_once('vendor/autoload.php');

function send_facebook($message) {
$fb = new Facebook\Facebook([
  'app_id' => $db_app_id,
  'app_secret' => $fb_app_secret,
  'default_graph_version' => 'v2.10',
  ]);

$linkData = [
  'message' => $message,
  ];

try {
  $response = $fb->post('/me/feed', $linkData, $fb_token);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$graphNode = $response->getGraphNode();
}
?>
