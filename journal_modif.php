<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'inclus/log.php';
require_once 'inclus/consts.php';
$titre='Journal des modifications '.$nomdusite;
$cheminaudio='/audio/sons_des_pages/autremail.mp3';
$stats_page='journal'; ?>
<!doctype html>
<html lang="fr">
<?php include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="<?php echo tr($tr0,'banner_homelink'); ?>"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</div>
<?php include('inclus/son.php');
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
<p>Vous avez ici la liste des logiciels mis à jour ou ajoutés ce mois-ci.</p>
<p>Abonnez-vous à <a href="/journal_modif.xml">notre flux RSS</a> ou à <a href="/newsletter.php">notre lettre d'informations</a> pour être au courant de toutes les mises à jour!</p>
<p>Notez que le flux RSS et la page que vous consultez actuellement sont actualisés automatiquement, sans intervention de l'équipe, en temps réel lors d'une mise à jour.</p>
<?php include('cache/journal.html'); ?>
</div>
<?php include 'inclus/footer.php'; ?>
</body>
</html>
