<?php
$logonly = true;
$adminonly = true;
$justpa = true;
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/include/consts.php');

if(!isset($_GET['yes'])) {
	echo 'Use GET param "yes" execute this program.';
	exit();
}

$req = $bdd->query('SELECT * FROM `softwares`');
while($data = $req->fetch()) {
	$req2 = $bdd->prepare('INSERT INTO `softwares_tr` (`sw_id`,`lang`,`date`,`name`,`text`,`keywords`,`description`,`website`,`author`,`published`,`todo_level`) VALUES (?,"fr",?,?,?,?,?,?,?,1,0)');
	$req2->execute(array($data['id'],$data['date'],$data['name'],$data['text'],$data['keywords'],$data['description'],$data['website'],$data['author']));
}
?>