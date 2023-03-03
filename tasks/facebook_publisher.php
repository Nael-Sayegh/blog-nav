<?php
$document_root = __DIR__.'/..';
require_once($document_root.'/include/consts.php');

$req = $bdd->prepare('SELECT * FROM `softwares_files` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-86400));# modifiés aujourd'hui
$files = '';
while($data = $req->fetch()) {
	$files .= "\n".$data['title'].' '.SITE_URL.'/r?';
	if(!empty($data['label']))
		$files .= 'p='.$data['label'];
	else
		$files .= 'i='.$data['id'];
}
$req = $bdd->prepare('SELECT * FROM `softwares_mirrors` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-86400));# modifiés aujourd'hui
while($data = $req->fetch()) {
	$files .= "\n".$data['title'].' '.SITE_URL.'/r?m&';
	if(!empty($data['label']))
		$files .= 'p='.$data['label'];
	else
		$files .= 'i='.$data['id'];
}

if(!empty($files)) {
	$message = 'Mises à jour d\'aujourd\'hui :'.$files."\n".'Administration '.$site_name;
	if(isset($debug))
	   echo $message;
	else {
		require_once($document_root.'/include/lib/facebook/fb_publisher.php');
		send_facebook($message);
	}
}
?>
