<?php
require_once 'inclus/config.local.php';
require_once('../ca-bundle/src/CaBundle.php');
require_once('inclus/lib/twitteroauth/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;
function send_twitter($message) {
	$connection = new \Abraham\TwitterOAuth\TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
	$post_tweets = $connection->post("statuses/update", ["status" => $message]);
}
send_twitter("Test");
?>
