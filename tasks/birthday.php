<?php
$document_root = __DIR__.'/..';
require_once($document_root.'/include/config.local.php');
require_once($document_root.'/include/consts.php');
require_once($document_root.'/include/lib/facebook/fb_publisher.php');
require_once($document_root.'/include/lib/Mastodon/mastodon_publisher.php');
$req = $bdd->prepare('SELECT * FROM `team` ORDER BY `age` DESC');
$req->execute();
while($data = $req->fetch()) {
	if(date('d/m') == date('d/m', $data['age'])) {
		if($data['mastodon']) {
			$messaget = '🎂 L\'équipe '.$site_name.' souhaite un joyeux anniversaire à '.$data['short_name'].' (@'.$data['mastodon'].') qui souffle aujourd\'hui ses '.intval((time()-$data['age'])/31557600).' 🕯️ !';
		} else {
			$messaget = '🎂 L\'équipe '.$site_name.' souhaite un joyeux anniversaire à '.$data['short_name'].' qui souffle aujourd\'hui ses '.intval((time()-$data['age'])/31557600).' 🕯️ !';
		}
		$messagef = '🎂 L\'équipe '.$site_name.' souhaite un joyeux anniversaire à '.$data['short_name'].' qui souffle aujourd\'hui ses '.intval((time()-$data['age'])/31557600).' 🕯️ !';
		send_mastodon($messaget);
		send_facebook($messagef);
	}
}
if(date('d/m') == '24/12') {
	$req = $bdd->prepare('SELECT * FROM `team` ORDER BY `age` DESC');
	$req->execute();
	while($data = $req->fetch()) {
		if($data['works'] == '1' or $data['works'] == '2') {
			$noms.=$data['short_name'].', ';
		}
	}
	$message='L\'équipe '.$site_name.' souhaite d\'excellentes fêtes de fin d\'année à l\'ensemble de sa communauté 🎉🎄🎅🤶🎁🎁🎁 !!!!'."\n".substr($noms,0,-2);
	send_mastodon($message);
	send_facebook($message);
} else if(date('d/m') == '01/01') {
	$req = $bdd->prepare('SELECT * FROM `team` ORDER BY `age` DESC');
	$req->execute();
	while($data = $req->fetch()) {
		if($data['works'] == '1' or $data['works'] == '2') {
			$noms.=$data['short_name'].', ';
		}
	}
	$message='L\'équipe '.$site_name.' souhaite une bonne année '.date('Y').' à l\'ensemble de sa communauté !!!!'."\n".substr($noms,0,-2);
	send_mastodon($message);
	send_facebook($message);
}
?>