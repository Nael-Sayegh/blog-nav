<?php
require_once('../GEOIP/vendor/autoload.php');
use GeoIp2\Database\Reader;

$reader = new Reader('../GEOIP/GeoLite2-City.mmdb', ['fr']);
$reader2 = new Reader('../GEOIP/GeoLite2-ASN.mmdb', ['fr']);
$record = $reader->city($_SERVER['REMOTE_ADDR']);
$record2 = $reader2->asn($_SERVER['REMOTE_ADDR']);
function getCurrentIP()
{
    $ip = getenv(HTTP_X_FORWARDED_FOR) ?: getenv(REMOTE_ADDR);
    return $ip;
}
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title = ('Infos vous concernant');
$sound_path = '/audio/page_sounds/gadget.mp3';
$stats_page = 'ip'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<p>Cette page va afficher plusieurs infos sur vous, tel que votre IP, votre localisation et bien plus encore...</p>
<h2>Note importante</h2>
<p>Avec certains opérateurs (Free surtout) certaines infos ne sont pas renvoyées et ne seront donc pas affichées.</p>
<h2>Infos connues</h2>
<ul>
<li>Localisation&nbsp;:
<ul>
<li>Pays&nbsp;: <?= $record->country->name ?></li>
<li>Département&nbsp;: <?= $record->mostSpecificSubdivision->name ?> (<?= $record->mostSpecificSubdivision->isoCode ?>)</li>
<li>Ville&nbsp;: <?= $record->city->name ?> (<?= $record->postal->code ?>)</li>
<li>Coordonnées GPS&nbsp;: <?= $record->location->latitude ?>, <?= $record->location->longitude ?></li>
</ul></li>
<li>Réseau&nbsp;:
<ul>
<li>FAI&nbsp;: <?= $record2->autonomousSystemOrganization ?></li>
<li>Adresse IP&nbsp;: <?= $_SERVER['REMOTE_ADDR'] ?></li>
<li>Hôte&nbsp;: <?= gethostbyaddr($_SERVER['REMOTE_ADDR']) ?></li>
</ul></li>
<li>Système&nbsp;:
<ul>
<li>Plateforme&nbsp;: <?php echo(trim((string) $_SERVER['HTTP_SEC_CH_UA_PLATFORM'], '"') ?? 'Inconnue'); ?></li>
</ul></li>
</ul>
<a href="/gadgets.php">Retour à la liste des gadgets.</a>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>