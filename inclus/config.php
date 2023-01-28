<?php
// `config.php` IS NOT YOUR config file.
// `config.local.php` IS YOUR config file.
// Copy `config.php` to `config.local.php` then fill it with your informations.

define('DOCUMENT_ROOT', '/var/www/html');
define('DEV', false);

// MySQL
define('DB_STRING', 'mysql:host=localhost;dbname=YOUR_DATABASE;charset=utf8mb4');
define('DB_USER', 'YOUR DB USER');
define('DB_PSW', 'YOUR DB PASSWORD');

// SMTP
define('SMTP_HOST', 'YOUR SMTP HOST');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'YOUR SMTP USERNAME');
define('SMTP_PSW', 'YOUR SMTP PASSWORD');

// Flarum
define('FLARUM_URL', 'https://forum.progaccess.net');
define('FLARUM_TOKEN', 'YOUR FLARUM TOKEN');
define('FLARUM_USERID', '1');

// Twitter
define('CONSUMER_KEY', 'YOUR TWITTER CONSUMER KEY');
define('CONSUMER_SECRET', 'YOUR TWITTER CONSUMER SECRET');
define('ACCESS_TOKEN', 'YOUR TWITTER ACCESS TOKEN');
define('ACCESS_TOKEN_SECRET', 'YOUR TWITTER ACCESS TOKEN SECRET');

// Facebook
define('FB_APP_ID', 'YOUR FACEBOOK APP ID');
define('FB_APP_SECRET', 'YOUR FACEBOOK APP SECRET');
define('FB_TOKEN', 'YOUR FACEBOOK TOKEN');

// Mastodon
define('MASTODON_TOKEN', 'YOUR MASTODON APP ACCESS TOKEN');
define('MASTODON_URL', 'YOUR MASTODON INSTANCE URL');
define('MASTODON_VISIBILITY', 'VISIBILITY OF TOOTS POSTED ON MASTODON (PUBLIC, PRIVATE, UNLISTED OR DIRECT)');
define('MASTODON_LANG', 'LANGUAGE OF TOOTS POSTED ON MASTODON (EN, FR, ZH...)');

// Git
define('GIT_DIR', 'PATH OF .GIT FOLDER');
define('GIT_COMMIT_BASE_URL', 'BEGIN URL OF A COMMIT ON YOUR GIT REPO');

?>
