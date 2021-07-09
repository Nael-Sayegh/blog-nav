<?php
// `config.php` IS NOT YOUR config file.
// Copy `config.php` to `config.local.php` then fill it with your informations.

$document_root = '/var/www/html';
$dev = false;

// MySQL
$db_string = 'mysql:host=localhost;dbname=YOUR_DATABASE;charset=utf8mb4';
$db_user = 'YOUR DB USER';
$db_psw = 'YOUR DB PASSWORD';

// SMTP
$smtp_host = 'YOUR SMTP HOST';
$smtp_port = 587;
$smtp_username = 'YOUR SMTP USERNAME';
$smtp_psw = 'YOUR SMTP PASSWORD';

// Flarum
$flarum_url = 'https://forum.progaccess.net';
$flarum_token = 'YOUR FLARUM TOKEN';
$flarum_userid = '1';

// Twitter
define('CONSUMER_KEY', 'YOUR CONSUMER KEY');
define('CONSUMER_SECRET', 'YOUR CONSUMER SECRET');
define('ACCESS_TOKEN', 'YOUR ACCESS TOKEN');
define('ACCESS_TOKEN_SECRET', 'YOUR ACCESS TOKEN SECRET');

// Facebook
$fb_app_id = 'YOUR FACEBOOK APP ID';
$fb_app_secret = 'YOUR FACEBOOK APP SECRET';
$fb_token = 'YOUR FACEBOOK TOKEN';

?>
