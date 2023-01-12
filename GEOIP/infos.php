<?php
require_once('vendor/autoload.php');
use GeoIp2\Database\Reader;
$reader = new Reader('GeoLite2-City.mmdb',array('fr'));
$reader2 = new Reader('GeoLite2-ASN.mmdb',array('fr'));
$record = $reader->city($_SERVER['REMOTE_ADDR']);
$record2 = $reader2->asn($_SERVER['REMOTE_ADDR']);
function getCurrentIP() {
$ip = (getenv(HTTP_X_FORWARDED_FOR))
?  getenv(HTTP_X_FORWARDED_FOR)
:  getenv(REMOTE_ADDR);
return $ip;
}
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('inclus/log.php');
require_once('inclus/consts.php');
$titre=("Infos vous concernant");
$cheminaudio="/audio/sons_des_pages/gadget.mp3";
$stats_page = 'ip'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('inclus/header.php'); ?>
<body>
<?php require_once('inclus/banner.php');
require_once('inclus/son.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $titre; ?></h1>
<p>Cette page va afficher plusieurs infos sur vous, tel que votre IP, votre localisation et bien plus encore...</p>
<h2>Note importante</h2>
<p>Avec certains opérateurs (Free surtout) certaines infos ne sont pas renvoyées et ne seront donc pas affichées.</p>
<h2>Infos connues</h2>
<ul>
<li>Localisation&nbsp;:
<ul>
<li>Pays&nbsp;: <?php echo $record->country->name; ?></li>
<li>Département&nbsp;: <?php echo $record->mostSpecificSubdivision->name; ?> (<?php echo $record->mostSpecificSubdivision->isoCode; ?>)</li>
<li>Ville&nbsp;: <?php echo $record->city->name; ?> (<?php echo $record->postal->code; ?>)</li>
<li>Latitude&nbsp;: <?php echo $record->location->latitude; ?></li>
<li>Longitude&nbsp;: <?php echo $record->location->longitude; ?></li>
</ul></li>
<li>Réseau&nbsp;:
<ul>
<li>FAI&nbsp;: <?php echo $record2->autonomousSystemOrganization; ?></li>
<li>Adresse IP&nbsp;: <?php echo $_SERVER['REMOTE_ADDR']; ?></li>
<li>Hôte&nbsp;: <?php echo gethostbyaddr($_SERVER['REMOTE_ADDR']); ?></li>
</ul></li>
</ul>
<a href="/gadgets.php">Retour à la liste des gadgets.</a>
</main>
<?php require_once('inclus/footer.php'); ?>
</body>
</html>