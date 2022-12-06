<?php
$document_root = __DIR__.'/../../..';
require_once $document_root.'/inclus/config.local.php';
require_once($document_root.'/inclus/lib/twitteroauth/vendor/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;
function send_twitter($message) {
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
	$post_tweets = $connection->post("statuses/update", ["status" => $message]);
}
?>
