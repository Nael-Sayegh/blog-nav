<?php
$document_root = __DIR__.'/..';
require_once($document_root.'/include/dbconnect.php');

// Make PHP languages cache
$req = $bdd->query('SELECT `lang`,`name` FROM `languages` ORDER BY `name`ASC');
$langs = array();// associative list of languages' codes and names in alphabetical order
$langs_alpha = array();//list of languages' codes in alphabetical order
while($data = $req->fetch()) {
	$langs[$data['lang']] = $data['name'];
	$langs_alpha[] = $data['lang'];
}

$req = $bdd->query('SELECT `lang` FROM `languages` ORDER BY `priority` ASC');
$langs_prio = array();// list of languages' codes in priority order
while($data = $req->fetch()) {
	$langs_prio[] = $data['lang'];
}

$langs_html_opts = '';
foreach($langs_alpha as &$i) {
	$langs_html_opts .= '<option value="'.$i.'" title="'.$i.'">'.str_replace('\'','\\\'',str_replace('\\','\\\\',$langs[$i])).'</option>';
}

if(file_exists($document_root.'/source/locales.zip'))
	unlink($document_root.'/source/locales.zip');
$zip = new ZipArchive();
$zip->open($document_root.'/source/locales.zip', ZipArchive::CREATE);
$zip->addFile($document_root.'/locales/LICENSE.txt', 'LICENSE.txt');

$available_trs = array();
$available_trs_index = array();
$trsdirs = array_diff(scandir($document_root.'/locales'), array('..', '.', 'LICENSE.txt'));
foreach($trsdirs as &$trsdir) {
	$trsfiles = array_diff(scandir($document_root.'/locales/'.$trsdir), array('..', '.', 'LICENSE.txt'));
	foreach($trsfiles as &$trsfile) {
		if(preg_match('/^(.+)\\.tr\\.php$/', $trsfile, $match)) {
			$available_trs[$trsdir][] = $match[1];
			$filename = $document_root.'/locales/'.$trsdir.'/'.$match[1].'.tr.php';
			include $filename;
			$available_trs_index[$trsdir][$tr['_']] = array(
				'todo_level' => isset($tr['_todo_level'])? $tr['_todo_level']:NULL,
				'last_author' => isset($tr['_last_author'])? $tr['_last_author']:NULL,
				'last_modif' => isset($tr['_last_modif'])? $tr['_last_modif']:NULL
			);
			$zip->addFile($filename, 'locales/'.$trsdir.'/'.$match[1].'.tr.php');
		}
	}
	unset($trsfile);
}
$zip->close();

$file = fopen($document_root.'/cache/langs.php', 'w');
fwrite($file, '<?php
$langs='.var_export($langs, true).';
$langs_prio='.var_export($langs_prio, true).';
$langs_html_opts=\''.$langs_html_opts.'\';
function langs_html_opts($selected=\'\') {global $langs_html_opts;return str_replace(\'value="\'.$selected.\'"\', \'value="\'.$selected.\'" selected\', $langs_html_opts);}
$available_trs = '.var_export($available_trs, true).';
function load_tr($trlang, $trname) {
	global $available_trs;
	if(isset($available_trs[$trlang]) and in_array($trname,$available_trs[$trlang])) {include \''.$document_root.'/locales/\'.$trlang.\'/\'.$trname.\'.tr.php\';return $tr;}
	global $langs_prio;
	foreach($langs_prio as &$i) {if(isset($available_trs[$i]) and in_array($trname,$available_trs[$i])) {include \''.$document_root.'/locales/\'.$i.\'/\'.$trname.\'.tr.php\';return $tr;}}
	return array();
}
function tr(&$ttr, $tkey, $vars=array()) {
	if(isset($ttr[$tkey])) return bparse($ttr[$tkey], $vars);
	global $langs_prio;
	foreach($langs_prio as &$i) {$tr = load_tr($i, $ttr[\'_\']);if(isset($tr[$tkey])) {$ttr[$tkey] = $tr[$tkey];return bparse($tr[$tkey],$vars);}}
	return \'\';
}
?>');
fclose($file);

$file = fopen($document_root.'/cache/langs_index.php', 'w');
fwrite($file, '<?php
$available_trs_index = '.var_export($available_trs_index, true).';
?>');
fclose($file);
?>
