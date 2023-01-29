<?php
require_once('include/log.php');
require_once('include/consts.php');
if(!empty($_SERVER['HTTP_REFERER']) and substr_count('commentcamarche.net', $_SERVER['HTTP_REFERER']) > 0) {
	header('Location: /');
	exit();
}
if(isset($_GET['id']) and $_GET['id'] != '')
{
	require_once('include/dbconnect.php');
	
	if(isset($_GET['m'])) {
		$req = $bdd->prepare('SELECT * FROM softwares_mirrors WHERE id=?');
		$req->execute(array($_GET['id']));
		if($data = $req->fetch()) {
			$links = json_decode($data['links'], true);
			if(empty($_GET['m']))
				header('Location: '.$links[rand(0,count($links)-1)][1]);
			else
				header('Location: '.$links[intval($_GET['m'])][1]);
			require_once('include/isbot.php');
			if(!(isset($_COOKIE['admincookie_nostats']) and $_COOKIE['admincookie_nostats'] == 'f537856b32e9e5e0418b224167576240') and !$isbot) {
				$req2 = $bdd->prepare('UPDATE softwares_mirrors SET hits=hits+1 WHERE id=? LIMIT 1');
				$req2->execute(array($_GET['id']));
				$req2 = $bdd->prepare('UPDATE softwares SET downloads=downloads+1 WHERE id=? LIMIT 1');
				$req2->execute(array($data['sw_id']));
			}
		}
		else
			echo 'Erreur: Miroir introuvable';
		$req->closeCursor();
	} else {
		$req = $bdd->prepare('SELECT * FROM softwares_files WHERE id=?');
		$req->execute(array($_GET['id']));
		if($data = $req->fetch()) {
			$file = fopen('files/'.$data['hash'], 'rb');
			if(FALSE === $file)
				exit('Erreur grave: Fichier inexistant');
			header('Content-type: '.$data['filetype']);
			header('Content-Disposition: attachment; filename="'.str_replace('"','',$data['name']).'"');
			header('Content-Length: '.$data['filesize']);
			//readfile('files/'.$data['hash']);
			while(!feof($file)) {
				echo fread($file, 8192);
			}
			fclose($file);
			require_once('include/isbot.php');
			if(!(isset($_COOKIE['admincookie_nostats']) and $_COOKIE['admincookie_nostats'] == 'f537856b32e9e5e0418b224167576240') and !$isbot) {
				$req2 = $bdd->prepare('UPDATE softwares_files SET hits=hits+1 WHERE id=? LIMIT 1');
				$req2->execute(array($_GET['id']));
				$req2 = $bdd->prepare('UPDATE softwares SET downloads=downloads+1 WHERE id=? LIMIT 1');
				$req2->execute(array($data['sw_id']));
			}
		}
		else
			echo 'Erreur: Fichier introuvable';
		$req->closeCursor();
	}
}
else if(isset($_GET['p']) and $_GET['p'] != '')
{
	require_once('include/dbconnect.php');
	
	if(isset($_GET['m'])) {
		$req = $bdd->prepare('SELECT * FROM softwares_mirrors WHERE label=?');
		$req->execute(array($_GET['p']));
		if($data = $req->fetch()) {
			$links = json_decode($data['links'], true);
			if(empty($_GET['m']))
				header('Location: '.$links[rand(0,count($links)-1)][1]);
			else
				header('Location: '.$links[intval($_GET['m'])][1]);
			require_once('include/isbot.php');
			if(!(isset($_COOKIE['admincookie_nostats']) and $_COOKIE['admincookie_nostats'] == 'f537856b32e9e5e0418b224167576240') and !$isbot) {
				$req2 = $bdd->prepare('UPDATE softwares_mirrors SET hits=hits+1 WHERE id=? LIMIT 1');
				$req2->execute(array($data['id']));
				$req2 = $bdd->prepare('UPDATE softwares SET downloads=downloads+1 WHERE id=? LIMIT 1');
				$req2->execute(array($data['sw_id']));
			}
		}
		else
			echo 'Erreur: Miroir introuvable';
		$req->closeCursor();
	} else {
		$req = $bdd->prepare('SELECT * FROM softwares_files WHERE label=? LIMIT 1');
		$req->execute(array($_GET['p']));
		if($data = $req->fetch()) {
			$file = fopen('files/'.$data['hash'], 'rb');
			if(FALSE === $file)
				exit('Erreur grave: Fichier inexistant');
			header('Content-type: '.$data['filetype']);
			header('Content-Disposition: attachment; filename="'.str_replace('"','',$data['name']).'"');
			header('Content-Length: '.$data['filesize']);
			//readfile('files/'.$data['hash']);
			while(!feof($file)) {
				echo fread($file, 8192);
			}
			fclose($file);
			require_once('include/isbot.php');
			if(!(isset($_COOKIE['admincookie_nostats']) and $_COOKIE['admincookie_nostats'] == 'f537856b32e9e5e0418b224167576240') and !$isbot) {
				$req2 = $bdd->prepare('UPDATE softwares_files SET hits=hits+1 WHERE id=? LIMIT 1');
				$req2->execute(array($data['id']));
				$req2 = $bdd->prepare('UPDATE softwares SET downloads=downloads+1 WHERE id=? LIMIT 1');
				$req2->execute(array($data['sw_id']));
			}
		}
		else
			echo 'Erreur: Fichier introuvable';
		$req->closeCursor();
	}
}
else
	header('Location: /');
?>