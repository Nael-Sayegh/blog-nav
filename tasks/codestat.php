<?php
$document_root = __DIR__.'/..';
require_once($document_root.'/include/consts.php');

$files = array('Slider.php', 'index.php', 'browser_homepage.php', 'members_list.php', 'art_list.php', 'article.php', 'auth_forum.php', 'cat.php', 'privacy.php', 'confirm.php', 'contact.php', 'contact_form.php', 'gadgets.php', 'home.php', 'history.php', 'login.php', 'logout.php', 'fg_password.php', 'mdp_verif.php', 'newsletter.php', 'nlmod.php', 'opensearch.xml.php', 'opensource.php', 'settings.php', 'r.php', 'nav_redirect.php', 'robots.txt', 'search.php', 'signup.php', 'sitemap.xml', 'trident.php', 'update.php',
	'403/403.html', '403B.php',
	'a/index.php',
	'admin/index.php', 'admin/cache_update.php', 'admin/nl_send.php', 'admin/maintenance.php', 'admin/members_gestion.php', 'admin/mvtrsw.php', 'admin/nl_last.php', 'admin/nl_list.php', 'admin/publication.php', 'admin/showstats.php', 'admin/slidermgr.php', 'admin/sw_add.php', 'admin/sw_categories.php', 'admin/sw_mod.php', 'admin/team_gestion.php', 'admin/techniques.php', 'admin/tickets.php', 'admin/translate.php', 'admin/translate_todo.php', 'admin/up_publish.php',
	'admin/css/admin.css', 'admin/css/showstats.css', 'admin/css/tickets.css', 'admin/css/translate.css',
	'admin/js/sliderstyles.js', 'admin/js/translate.js',
	'api/account.php', 'api/index.php', 'api/api_inc.php', 'api/get.php',
	'c/index.php',
	'css/default.css', 'css/search.css', 'css/slider.css',
	'gadgets/IP.php', 'gadgets/clock.php', 'GEOIP/infos.php', 'gadgets/password_gen.php', 'gadgets/pof.php',
	'include/403.php', 'include/OS.php', 'include/compteur.php', 'include/config.php', 'include/consts.php', 'include/dbconnect.php', 'include/flarum.php', 'include/footer.php', 'include/header.php', 'include/isbot.php', 'include/log.php', 'include/loginbox.php', 'include/maintenance_mode.php', 'include/maintenancemode.html', 'include/menu.php', 'include/searchtool.php', 'include/sendconfirm.php', 'include/load_sound.php', 'include/sontrident.php', 'include/stats.php', 'include/trident.php', 'include/user_rank.php',
	'include/lib/facebook/fb_publisher.php', 'include/lib/twitter/twitter_publisher.php',
	'res/phpsocialclient/facebook.php', 'res/phpsocialclient/twitter.php', 'res/phpsocialclient/locales/en.php', 'res/phpsocialclient/locales/fr.php', 'res/phpsocialclient/templates/getdata.php', 'res/phpsocialclient/templates/locales.php',
	'r/index.php',
	'scripts/default.js', 'scripts/clock.js', 'scripts/menu.js', 'scripts/menu2.js', 'scripts/pa_api.js', 'scripts/script1.js', 'scripts/slider.js',
	'tasks/accounts_manager.php', 'tasks/birthday.php', 'tasks/codestat.php', 'tasks/comments_tasker.php', 'tasks/facebook_publisher.php', 'tasks/history_cache.php', 'tasks/langs_cache.php', 'tasks/nl_manager.php', 'tasks/notif_tasker.php', 'tasks/slider_cache.php', 'tasks/stats_daily.php',
	'u/index.php');

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
