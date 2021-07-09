<?php
require_once(__DIR__.'/../inclus/consts.php');

$SESSION_EXPIRE = 8640000; // time after expiration to delete session (100 days)

// Update account rank

$req = $bdd->prepare('SELECT * FROM `accounts`');
$req->execute();

while($data = $req->fetch()) {
	$change = false;
	$rank = $data['rank'];
	
	if($data['rank'] == '0' and $data['signup_date']+1209600 < time()) {
		$rank = '1';
		$change = true;
	}

	if($change) {
		$req2 = $bdd->prepare('UPDATE `accounts` SET `rank`=? WHERE `id`=? LIMIT 1');
		$req2->execute(array($rank, $data['id']));
	}
}

// Remove expired sessions

$req = $bdd->prepare('DELETE FROM `sessions` WHERE `expire`<?');
$req->execute(array(time()-$SESSION_EXPIRE));

?>
