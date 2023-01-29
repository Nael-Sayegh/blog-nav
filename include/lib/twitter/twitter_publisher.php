<?php
$document_root = __DIR__.'/../../..';
require_once $document_root.'/include/config.local.php';
require_once $document_root.'/include/lib/ca-bundle/src/CaBundle.php';
require_once $document_root.'/include/lib/twitteroauth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
function send_twitter($message) {
	$connection = new \Abraham\TwitterOAuth\TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
	$post_tweets = $connection->post("statuses/update", ["status" => $message]);
}
?>
