<head>
<meta charset="utf-8">
<title><?php echo $title.' – '.$site_name; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link title="<?php echo $site_name; ?>" type="application/opensearchdescription+xml" rel="search" href="/opensearch.xml.php">
<?php print $css_path; ?>
<style>body {font-size: <?php if(isset($_COOKIE['fontsize']) and preg_match('#[0-9]{1,2}#',$_COOKIE['fontsize'])) echo $_COOKIE['fontsize']; else echo '16'; ?>px;}</style>
<script src="/scripts/default.js"></script>
<meta property="og:title" content="<?php print $title; ?>">
<link rel="stylesheet" href="/include/lib/mastodon_share/mastodonShare.css">
<script src="/include/lib/mastodon_share/mastodonShare.js"></script>
</head>