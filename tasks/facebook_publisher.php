<?php
$document_root = __DIR__.'/..';
require_once($document_root.'/inclus/consts.php');

$req = $bdd->prepare('SELECT * FROM `softwares_files` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-86400));# modifiés aujourd'hui
$files = '';
while($data = $req->fetch()) {
	$files .= "\n".$data['title'].' https://www.progaccess.net/r?';
	if(!empty($data['label']))
		$files .= 'p='.$data['label'];
	else
		$files .= 'i='.$data['id'];
}
$req = $bdd->prepare('SELECT * FROM `softwares_mirrors` WHERE `date`>=? ORDER BY `date` DESC');
$req->execute(array(time()-86400));# modifiés aujourd'hui
while($data = $req->fetch()) {
	$files .= "\n".$data['title'].' https://www.progaccess.net/r?m&';
	if(!empty($data['label']))
		$files .= 'p='.$data['label'];
	else
		$files .= 'i='.$data['id'];
}

if(!empty($files)) {
	$message = 'Mises à jour d\'aujourd\'hui :'.$files."\n".'Administration '.$nomdusite;
	if(isset($debug))
	   echo $message;
	else {
		require_once($document_root.'/inclus/lib/facebook/envoyer.php');
		send_facebook($message);
	}
}
?>
