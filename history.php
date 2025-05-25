<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title = 'Journal des modifications '.$site_name;
$sound_path = '/audio/page_sounds/history.mp3';
$stats_page = 'journal'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<p>Vous avez ici la liste des logiciels mis à jour ou ajoutés ce mois-ci.</p>
<p>Abonnez-vous à <a href="/rss_feed.xml">notre flux RSS</a> ou à <a href="/newsletter.php">notre lettre d'informations</a> pour être au courant de toutes les mises à jour!</p>
<p>Notez que le flux RSS et la page que vous consultez actuellement sont actualisés automatiquement, sans intervention de l'équipe, en temps réel lors d'une mise à jour.</p>
<?php include('cache/journal.html'); ?>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>