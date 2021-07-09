<?php
require_once(__DIR__.'/../inclus/consts.php');

$files = array('Slider.php', 'accueil.php', 'accueil_navigateurs.php', 'alist.php', 'art_list.php', 'article.php', 'auth_forum.php', 'cat.php', 'confidentialite.php', 'confirm.php', 'contact.php', 'contacter.php', 'gadgets.php', 'home.php', 'journal_modif.php', 'login.php', 'logout.php', 'mdp_demande.php', 'mdp_verif.php', 'newsletter.php', 'nlmod.php', 'opensearch.xml.php', 'opensource.php', 'param.php', 'r.php', 'redirection_navigation.php', 'robots.txt', 'search.php', 'signup.php', 'sitemap.xml', 'trident.php', 'update.php',
	'403/403.html', '403B.php',
	'a/accueil.php',
	'admin/accueil.php', 'admin/cache_update.php', 'admin/envoinl.php', 'admin/maintenance.php', 'admin/members_gestion.php', 'admin/mvtrsw.php', 'admin/nl_last.php', 'admin/nl_list.php', 'admin/publication.php', 'admin/showstats.php', 'admin/slidermgr.php', 'admin/sw_add.php', 'admin/sw_categories.php', 'admin/sw_mod.php', 'admin/team_gestion.php', 'admin/techniques.php', 'admin/tickets.php', 'admin/translate.php', 'admin/translate_todo.php', 'admin/up_publish.php',
	'admin/css/admin.css', 'admin/css/showstats.css', 'admin/css/tickets.css', 'admin/css/translate.css',
	'admin/js/sliderstyles.js', 'admin/js/translate.js',
	'api/account.php', 'api/accueil.php', 'api/api_inc.php', 'api/get.php',
	'c/accueil.php',
	'css/default.css', 'css/forum.css', 'css/search.css', 'css/slider.css',
	'gadgets/IP.php', 'gadgets/horloge.php', 'GEOIP/infos.php', 'gadgets/ParamPasswd.php', 'gadgets/pof.php',
	'inclus/403.php', 'inclus/OS.php', 'inclus/compteur.php', 'inclus/config.php', 'inclus/consts.php', 'inclus/dbconnect.php', 'inclus/flarum.php', 'inclus/footer.php', 'inclus/header.php', 'inclus/isbot.php', 'inclus/log.php', 'inclus/loginbox.php', 'inclus/maintenance_mode.php', 'inclus/maintenancemode.html', 'inclus/menu.php', 'inclus/searchtool.php', 'inclus/sendconfirm.php', 'inclus/son.php', 'inclus/sontrident.php', 'inclus/stats.php', 'inclus/trident.php', 'inclus/user_rank.php',
	'inclus/lib/facebook/envoyer.php', 'inclus/lib/twitter/twitter.php',
	'res/phpsocialclient/facebook.php', 'res/phpsocialclient/twitter.php', 'res/phpsocialclient/locales/en.php', 'res/phpsocialclient/locales/fr.php', 'res/phpsocialclient/templates/getdata.php', 'res/phpsocialclient/templates/locales.php',
	'r/accueil.php',
	'scripts/default.js', 'scripts/horloge.js', 'scripts/menu.js', 'scripts/menu2.js', 'scripts/pa_api.js', 'scripts/script1.js', 'scripts/slider.js',
	'tasks/accounts_manager.php', 'tasks/annivs.php', 'tasks/codestat.php', 'tasks/comments_tasker.php', 'tasks/facebook_publisher.php', 'tasks/journal_cache.php', 'tasks/langs_cache.php', 'tasks/nl_manager.php', 'tasks/notif_tasker.php', 'tasks/slider_cache.php', 'tasks/stats_daily.php',
	'u/accueil.php');

$n_files = 0;
$n_lines = 0;
$n_chars = 0;

foreach($files as &$file) {
	$n_files ++;
	if($f = fopen($document_root.'/'.$file, 'r')) {
		while(!feof($f)) {
			fgets($f, 8192);
			$n_lines ++;
		}
		fclose($f);
		$n_chars += filesize($document_root.'/'.$file);
	}
	else
		print 'Not found: '.$file.'\n';
}

$outfile = fopen($document_root.'/cache/codestatc.php', 'w');
fputs($outfile, '<?php $codestat_n_files='.$n_files.';$codestat_n_lines='.$n_lines.';$codestat_n_chars='.$n_chars.'; ?>');
fclose($outfile);
?>
