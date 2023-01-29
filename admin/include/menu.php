<?php switch($_SERVER['DOCUMENT_URI']) {
	case '/admin/index.php':
		echo '<table><caption>Zone de gestion pour '.urank($login['rank']).'</caption>
		<thead><tr><th>Catégorie</th><th>Option</th></tr></thead>
		<tbody>
		<tr><td rowspan="4" role="heading" aria-level="3">Contenu utilisateur</td><td><a href="sw_mod.php">Articles</a></td></tr>
		<tr><td><a href="translate_todo.php">Traductions</a></td></tr>
		<tr><td><a href="sw_categories.php">Catégories</a></td></tr>
		<tr><td><a href="cache_update.php">Caches</a></td></tr>
		<tr><td rowspan="7" role="heading" aria-level="3">Communication & actualités</td><td><a href="tickets.php">Tickets</a></td></tr>
		<tr><td><a href="publication.php">Publications sociales</a></td></tr>
		<tr><td><a href="update_article.php">Mises à jour d\'article proposées par les membres</a></td></tr>
		<tr><td><a href="slidermgr.php">Slider</a></td></tr>
		<tr><td rowspan="3" role="heading" aria-level="3">Lettre d\'informations</td><td><a href="nl_send.php">Lancer un envoi maintenant</a></td></tr>
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
		</table>';
		break;
	case '/admin/sw_mod.php':
		echo '<details><summary>Menu</summary><ul style="list-style-type: none;"><li><a href="sw_add.php">Ajouter un article</a></li><li><a href="sw_cat.php">Catégories</a></li><li><a href="translate_todo.php">Traductions</a></li><li><a href="cache_update.php">Caches</a></li></ul></details>';
		break;
	case '/admin/sw_add.php':
		echo '<details><summary>Menu</summary><ul style="list-style-type: none;"><li><a href="sw_mod.php">Modifier un article</a></li><li><a href="sw_cat.php">Catégories</a></li><li><a href="translate_todo.php">Traductions</a></li></ul></details>';
		break;
	case 'showstats.php':
	case 'slidermgr.php':
		echo '<details><summary>Menu</summary><ul style="list-style-type: none;"><li><a href="cache_update.php">Caches</a></li></ul></details>';
		break;
	case 'nl_list.php':
		echo '<details><summary>Menu</summary><ul style="list-style-type: none;"><li><a href="nl_send.php">Envoyer l\'actu</a></li></ul></details>';
		break;
	case 'nl_send.php':
		echo '<details><summary>Menu</summary><ul style="list-style-type: none;"><li><a href="nl_list.php">Voir les inscrits à l\'actu</a></li></ul></details>';
		break;
	case 'up_publish.php':
		echo '<details><summary>Menu</summary><ul style="list-style-type: none;"><li><a href="cache_update.php">Caches</a></li></ul></details>';
		break;
	case 'members_gestion':
		echo '<details><summary>Menu</summary><ul style="list-style-type: none;"><li><a href="team_gestion.php">Gérer l\'équipe</a></li></ul></details>';
		break;
	case 'team_gestion.php':
		echo '<details><summary>Menu</summary><ul style="list-style-type: none;"><li><a href="members_gestion.php">Gérer les membres</a></li></ul></details>';
		break;
}
?>