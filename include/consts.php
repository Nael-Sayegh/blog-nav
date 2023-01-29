<?php
require_once 'config.local.php';

function urlsafe_b64encode($str) {
	return strtr(preg_replace('/[\=]+\z/', '', base64_encode($str)), '+/=', '-_');
}

function urlsafe_b64decode($data) {
	$data = preg_replace('/[\t-\x0d\s]/', '', strtr($data, '-_', '+/'));
	$mod4 = strlen($data) % 4;
	if($mod4)
		$data .= substr('====', $mod4);
	return base64_decode($data);
}

function zeros($n, $d=3) {
	$l = floor(log10($n)+1);
	if($l < $d)
		return str_repeat('0', $d-$l) . $n;
	else
		return strval($n);
}

function args_html_form($args) {
	$r = '';
	foreach($args as $name => $value) {
		$r .= '<input type="hidden" name="'.$name.'" value="'.$value.'">';
	}
	return $r;
}

function bparse($text, $vars) {
	global $site_name, $slogan;
	$vars['site'] = $site_name;
	$vars['slogan'] = $slogan;
	foreach($vars as $var1 => $var2) {
		$text = str_replace('{{'.$var1.'}}', $var2, $text);
	}
	return $text;
}

function numberlocale($n) {
	global $tr0;
	return str_replace('.', tr($tr0,'decimal_separator'), strval($n));
}

function human_filesize($bytes, $decimals = 1) {
	$sz = ' kMGTP';
	$factor = floor((strlen($bytes) - 1) / 3);
	return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$sz[$factor];
}

function get_article_trs($article_id) {
	global $bdd;
	$req = $bdd->prepare('
		SELECT `softwares_tr`.`id`, `softwares_tr`.`lang`, `softwares_tr`.`name`, `softwares_tr`.`description`, `softwares_tr`.`sw_id`, `softwares`.`hits`, `softwares`.`downloads`, `softwares`.`date`, `softwares`.`category`
		FROM `softwares`
		LEFT JOIN `softwares_tr` ON `softwares`.`id`=`softwares_tr`.`sw_id`
		WHERE `softwares`.`id`=?');
	$req->execute(array($article_id));
	if($data = $req->fetch()) {
		$article = array('cat'=>$data['category'], 'hits'=>$data['hits'], 'dl'=>$data['downloads'], 'date'=>$data['date'], 'trs'=>array());
		$article['trs'][$data['lang']] = array('id'=>$data['id'], 'title'=>$data['name'], 'desc'=>$data['description']);
		return $article;
	} return false;
}

function get_article_prefered_tr($article_id, $lang) {
	global $langs_prio;
	if(!$article = get_article_trs($article_id))
		return false;
	$tr = '';
	if(array_key_exists($lang, $article['trs']))
		$tr = $lang;
	else {
		foreach($langs_prio as &$i_lang) {
			if(array_key_exists($i_lang, $article['trs'])) {
				$tr = $i_lang;
				break;
			}
		}
	}
	$article["prefered_tr"] = $tr;
	return $article;
}

function getLastGitCommit()
{
	global $tr0;
	$hash = shell_exec('git --git-dir="'.GIT_DIR.'" rev-parse --verify HEAD');
	$commitDate = strftime(tr($tr0,'fndatetime'), shell_exec('git --git-dir="'.GIT_DIR.'" show -s --format=%ct '.$hash));
	$commitURL = '<a href="'.GIT_COMMIT_BASE_URL.$hash.'">Commit '.shell_exec('git --git-dir="'.GIT_DIR.'" show -s --format=%h').'</a>';
echo tr($tr0,'footer_lastcommit',array('date'=>$commitDate,'url'=>$commitURL,'site'=>$site_name));
}

function isDev()
{
if(strstr($_SERVER['HTTP_HOST'], 'dev.') || DEV == true)
return true;
else
return false;
}

date_default_timezone_set('Europe/Paris'); 
//setlocale(LC_TIME,'fr_FR.UTF8');
if(!(isset($noct) and $noct))
	header('Content-Type: text/html; charset=UTF-8');
ini_set('default_charset', 'utf-8');
include_once 'maintenance_mode.php';
if(isset($modemaintenance) and $modemaintenance and !(isset($logged) and $logged and $login['rank'] == 'a') and !(isset($nomm) and $nomm)) {
	http_response_code(503);
	include 'maintenancemode.html';
	exit();
}
require_once 'dbconnect.php';

// LANGUAGE
include_once DOCUMENT_ROOT.'/cache/langs.php';
$lang = '';
if(isset($_GET['lang']) and !empty($_GET['lang']) and in_array($_GET['lang'], $langs_prio)) {
	$lang = $_GET['lang'];
	setcookie('lang', $lang, time()+31557600, '/', NULL, false, false);
}
elseif(isset($_COOKIE['lang']) and strlen($_COOKIE['lang']) == 2)
	$lang = $_COOKIE['lang'];
elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
if(!in_array($lang, $langs_prio)) $lang = $langs_prio[0];
putenv('LANG='.$lang);
setlocale(LC_ALL, $lang);
setlocale(LC_NUMERIC, 'en');

// MISC CONSTS/VARS
$tr0 = load_tr($lang, 'default');
$site_name = (isDev()?tr($tr0,'sitename').'-Dev':tr($tr0,'sitename'));
$css_path = '<link rel="stylesheet" href="/css/default.css">';
$admin_css_path = '<link rel="stylesheet" href="/admin/css/admin.css">';
$slogan = tr($tr0,'slogan');
$lastosv = '17.0';
$tr_todo = array(0=>'Référence', 1=>'OK', 2=>'À vérifier', 3=>'À modifier', 4=>'À terminer');
$args = array();
// VERSION
$derniereversion = '';
$versionnom = '';
$versiondate = 0;
$versionid=0;
$req = $bdd->prepare('SELECT * FROM site_updates ORDER BY date DESC LIMIT 1');
$req->execute();
if($data = $req->fetch()) {
	$derniereversion = 'V'.$data['id'];
	$versionnom = substr($data['name'],1);
	$versiondate = strftime(tr($tr0,'fndatetime'), $data['date']);
	$versionid=$data['id'];
}
?>
