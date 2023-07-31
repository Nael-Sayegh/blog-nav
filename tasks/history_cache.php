<?php
$document_root = __DIR__.'/..';
require_once($document_root.'/include/consts.php');
$cachedir = $document_root.'/cache/';

$time = time();
$ltime = $time - 2678400;# 31 days ago

# Get categories
$cat = array();
$req = $bdd->query('SELECT * FROM `softwares_categories`');
while($data = $req->fetch()) {
	$cat[$data['id']] = $data['name'];
}

# Get softwares
$sft = array();
$req = $bdd->prepare('SELECT * FROM `softwares` WHERE `date`>=?');
$req->execute(array($ltime));
while($data = $req->fetch()) {
	$sft[$data['id']] = $data;
}

# Get files
$files = array();
$req = $bdd->prepare('SELECT * FROM `softwares_files` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array($ltime));
while($data = $req->fetch()) {
	$files[date('Y-m-d',$data['date'])][] = $data;
}

# Get last site update
$maj_date = '';
$req = $bdd->prepare('SELECT * FROM `site_updates` WHERE `date`>=? ORDER BY `date` DESC LIMIT 1');
$req->execute(array($ltime));
if($data = $req->fetch()) {
	if($data['id'] < 10)
		$maj_id = 'V00'.$data['id'];
	elseif($data['id'] < 100)
		$maj_id = 'V0'.$data['id'];
	else
		$maj_id = $data['id'];
	$maj_name = substr($data['name'],1);
	$maj_date = date('Y-m-d', $data['date']);
	$maj_link = SITE_URL.'/u'.$data['id'];
	$maj_time = $data['date'];
}

# Get days
$days = array();
$curtime = $time;
while($curtime >= $ltime) {
	$days[] = array(date('Y-m-d', $curtime), getFormattedDate($curtime, 'EEEE d MMMM'));
	$curtime -= 86400;
}

# Open files
$file_html = fopen($cachedir.'journal.html', 'w');
$file_rss = fopen($document_root.'/rss_feed.xml', 'w');
fwrite($file_rss, '<?xml version="1.0" encoding="utf-8"?><rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/"><channel><title>'.$site_name.'.net</title><link>'.SITE_URL.'</link><atom:link href="'.SITE_URL.'/rss_feed.xml" rel="self" type="application/rss+xml" /><description>Journal des modifications sur '.$site_name.'.</description><copyright>2016-'.date('Y').' L\'administration '.$site_name.'</copyright><language>fr</language>');

foreach($days as &$day) {
	$html = '';
	$rss = '';
	$title = false;
	$space = false;
	
	# Check & write site update
	if($maj_date == $day[0]) {
		$title = true;
		$space = true;
		$html = '<li><a class="jrnl_sft" href="'.$maj_link.'">Mise à jour du site&#160;: '.$site_name.' '.$maj_name.' ('.$maj_id.')</a></li>';
		$rss = '<item><title>Mise à jour du site : '.$site_name.' '.$maj_name.' ('.$maj_id.')</title><link>'.$maj_link.'</link><pubDate>'.date('r', $maj_time).'</pubDate></item>';
	}
	
	# Check & write softwares
	if(isset($files[$day[0]])) {
		$title = true;
		$cursfts = array();
		foreach($files[$day[0]] as &$curfile) {
			$cursfts[$curfile['sw_id']][] = $curfile;
		}
		unset($curfile);
		foreach($cursfts as &$cursft) {
			$c = $sft[$cursft[0]['sw_id']];
			$html .= '<li';
			if($space) $html .= ' class="jrnl_space"';
			$html .= '>Mis à jour par '.$c['author'].' : <a class="jrnl_sft" href="/a'.$c['id'].'">'.$c['name'].'</a> <span class="jrnl_cat">(<a href="/c'.$c['category'].'">'.$cat[$c['category']].'</a>)</span><p class="jrnl_p">'.$c['description'].'</p><ul>';
			foreach($cursft as &$curfile) {
				if($curfile['label'] != '') {
					$html .= '<li><a class="jrnl_r" href="/dl/'.$curfile['label'].'">'.$curfile['title'].'</a></li>';
					$rss .= '<item><title>'.$curfile['title'].'</title><link>'.SITE_URL.'/dl/'.$curfile['label'].'</link><dc:creator>'.$c['author'].'</dc:creator><description>'.$c['description'].'</description><pubDate>'.date('r', $curfile['date']).'</pubDate></item>';
				}
				else {
					$html .= '<li><a class="jrnl_r" href="/dl/'.$curfile['id'].'">'.$curfile['title'].'</a></li>';
					$rss .= '<item><title>'.$curfile['title'].'</title><link>'.SITE_URL.'/dl/'.$curfile['id'].'</link><description>'.$c['description'].'</description><pubDate>'.date('r', $curfile['date']).'</pubDate></item>';
				}
			}
			unset($curfile);
			$html .= '</ul>';
			$space = true;
		}
		unset($cursft);
	}
	
	# Write
	if($title) {
		fwrite($file_html, '<span class="jrnl_date" id="'.$day[0].'" role="heading" aria-level="2">'.$day[1].'</span><ul>'.str_replace('{{site}}',$site_name,$html).'</ul>');
		fwrite($file_rss, str_replace('{{site}}',$site_name,$rss));
	}
}
$req->closeCursor();
fclose($file_html);
fwrite($file_rss, '</channel></rss>');
fclose($file_rss);
?>
