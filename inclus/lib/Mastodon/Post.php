<?php
$document_root = __DIR__.'/../../..';
require_once($document_root.'/inclus/config.local.php');
require_once('Mastodon.php');

function send_mastodon($message) {
	$token = MASTODON_TOKEN;
	$base_url = MASTODON_URL;
	$visibility = MASTODON_VISIBILITY;
	$language = MASTODON_LANG;
	$mastodon = new MastodonAPI($token, $base_url);
	$status_data = ['visibility' => $visibility, 'language' => $language, 'status' => $message];
	$mastodon->postStatus($status_data);
}
?>