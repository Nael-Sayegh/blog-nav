<?php $logonly = true;
$adminonly = true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<title>Administration - <?php print $nomdusite; ?></title>
<?php print $cssadmin; ?>
<script src="/scripts/default.js"></script>
</head>
<body>
<h1>Administration - <a href="/"><?php print $nomdusite; ?></a></h1>
<h2>Connecté en tant que <?php print $nom; ?></h2>
<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php'; ?>
<table><caption>Zone de gestion pour <?php echo urank($login['rank']); ?></caption>
<thead><tr><th>Catégorie</th><th>Option</th></tr></thead>
<tbody>
<tr><td rowspan="4" role="heading" aria-level="3">Contenu utilisateur</td><td><a href="sw_mod.php">Articles</a></td></tr>
<tr><td><a href="translate_todo.php">Traductions</a></td></tr>
<tr><td><a href="sw_categories.php">Catégories</a></td></tr>
<tr><td><a href="cache_update.php">Caches</a></td></tr>
<tr><td rowspan="7" role="heading" aria-level="3">Communication & actualités</td><td><a href="tickets.php">Tickets</a></td></tr>
<tr><td><a href="publication.php">Publications sociales</a></td></tr>
<tr><td><a href="update_article.php">Mises à jour d'article proposées par les membres</a></td></tr>
<tr><td><a href="slidermgr.php">Slider</a></td></tr>
<tr><td rowspan="3" role="heading" aria-level="3">Lettre d'informations</td><td><a href="envoinl.php">Lancer un envoi maintenant</a></td></tr>
<tr><td><a href="nl_last.php">Réinitialiser la date du dernier envoi</a></td></tr>
<tr><td><a href="nl_list.php">Voir les abonnés</a></td></tr>
<tr><td rowspan="3" role="heading" aria-level="3">Contenu technique</td><td><a href="up_publish.php">Versions</a></td></tr>
<tr><td><a href="showstats.php">Statistiques</a></td></tr>
<tr><td><a href="maintenance.php">Maintenance</a></td></tr>
<tr><td rowspan="2" role="heading" aria-level="3">Communauté</td></tr><td><a href="team_gestion.php">Équipe</a></td></tr>
<tr><td><a href="members_gestion.php">Membres</a></td></tr>
<tr><td rowspan="4" role="heading" aria-level="3">Autre</td><td><a href="adminer/adminer.php">Gestion BDD</a></td></tr>
<tr><td><a href="https://litchi.site-meganet.com:8080">ISPConfig</a></td></tr>
<tr><td><a href="https://roundcube.progaccess.net">Webmail</a></td></tr>
<tr><td><a href="techniques.php">phpinfo()</a></td></tr>
</tbody>
</table>
<!--<h2>Bugs connus</h2>
<ul>
</ul>
<h2>Abréviations de contenus utilisées</h2>
<ul>
<li>A&nbsp;: article</li>
<li>C&nbsp;: catégorie</li>
<li>E&nbsp;: membre de l'équipe</li>
<li>K&nbsp;: commentaire</li>
<li>M&nbsp;: membre</li>
<li>N&nbsp;: inscrit à la lettre d'infos</li>
<li>V&nbsp;: version du site</li>
</ul>
<h2>Statistiques</h2>
<p><?php
include_once($_SERVER['DOCUMENT_ROOT'].'/cache/codestatc.php');
if(isset($codestat_n_files)) echo $codestat_n_files.' fichiers, ';
if(isset($codestat_n_lines)) echo $codestat_n_lines.' lignes, ';
if(isset($codestat_n_chars)) echo $codestat_n_chars.' octets';
?></p>-->
<!-- <p>La géolocalisation utilise <a href="https://www.maxmind.com/">MaxMind</a></p> -->
</body>
</html>
