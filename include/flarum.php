<?php
require_once 'config.local.php';

function flarum_create_user($username, $psw, $email) {
	$ch = curl_init(FLARUM_URL.'/api/users');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Authorization: Token ' . FLARUM_TOKEN
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
		'data' => [
			'attributes' => [
				'username' => $username,
				'password' => $psw,
				'email' => $email
			]
		]
	]));
	return json_decode(curl_exec($ch));
}

function flarum_edit_user($userid, $attributes) {
	$ch = curl_init(FLARUM_URL.'/api/users/'.$userid);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Authorization: Token ' . FLARUM_TOKEN . '; userId=' . FLARUM_USERID
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
		'data' => [
			'attributes' => $attributes
		]
	]));
	return json_decode(curl_exec($ch));
}

function flarum_get_token($username, $psw) {
	$ch = curl_init(FLARUM_URL.'/api/token');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json',
		'Authorization: Token ' . FLARUM_TOKEN
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
		'identification' => $username,
		'password' => $psw,
		'remember' => true,
	]));
	return json_decode(curl_exec($ch));
}

function sanitize_forum_username($username, $id=NULL) {
	require_once('dbconnect.php');
	global $bdd;
	
	$ALPHA = str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_');
	$res = '';
	$i = 0;
	while($i < strlen($username)) {
		if(in_array($username[$i], $ALPHA, true))
			$res .= $username[$i];
		$i ++;
	}
	
	while(strlen($res) < 3) {
		$res .= random_int(0, 9);
	}
	
	if($id === NULL) {
		$req = $bdd->prepare('SELECT `id` FROM `accounts` WHERE `forum_username`=? LIMIT 1');
		$req->execute(array($res));
	} else {
		$req = $bdd->prepare('SELECT `id` FROM `accounts` WHERE `forum_username`=? AND `id`!=? LIMIT 1');
		$req->execute(array($res, $id));
	}
	if($req->fetch()) {
		$suffix = 0;
		while($suffix < 100) {
			if($id === NULL) {
				$req = $bdd->prepare('SELECT `id` FROM `accounts` WHERE `forum_username`=? LIMIT 1');
				$req->execute(array($res.$suffix));
			} else {
				$req = $bdd->prepare('SELECT `id` FROM `accounts` WHERE `forum_username`=? AND `id`!=? LIMIT 1');
				$req->execute(array($res.$suffix, $id));
			}
			if($req->fetch()) {}
			else break;
			$suffix += 1;
		}
		if($suffix >= 100)
			return false;
		$res .= $suffix;
	}
	
	return $res;
}

function create_forum_account($id, $username, $email) {
	require_once('dbconnect.php');
	global $bdd;
	
	$forum_username = sanitize_forum_username($username);
	$forum_psw = base64_encode(random_bytes(24));
	$resp = flarum_create_user($username, $forum_psw, $email);
	if(isset($resp->data) and isset($resp->data->id)) {
		$forum_id = $resp->data->id;
		
		$req = $bdd->prepare('UPDATE `accounts` SET `forum_id`=? , `forum_psw`=?, `forum_username`=? WHERE `id`=? LIMIT 1');
		$req->execute(array($forum_id, $forum_psw, $forum_username, $id));
		
		return array('id'=>$forum_id, 'psw'=>$forum_psw, 'username'=>$forum_username);
	}
	return false;
}

function update_forum_account($login, $new_username) {
	require_once('dbconnect.php');
	global $bdd;
	
	$forum_username = sanitize_forum_username($new_username, $login['id']);
	
	$req = $bdd->prepare('UPDATE `accounts` SET `forum_username`=? WHERE `id`=? LIMIT 1');
	$req->execute(array($forum_username, $login['id']));
	
	$req = $bdd->prepare('SELECT `forum_psw` FROM `accounts` WHERE `id`=? LIMIT 1');
	$req->execute(array($login['id']));
	if($data = $req->fetch()) {
		$resp = flarum_edit_user($login['forum_id'], [
			'username' => $forum_username,
			'password' => $data['forum_psw'],
			'email' => $login['email']
		]);
		if(isset($resp->errors))
			error_log('During auth_forum, forum responded:\n'.print_r($resp, true).'\n');
		return true;
	}
	return false;
}

function auth_forum($id) {
	require_once('dbconnect.php');
	global $bdd;
	
	$req = $bdd->prepare('SELECT `forum_username`, `forum_psw` FROM `accounts` WHERE `id`=? LIMIT 1');
	$req->execute(array($id));
	if($data = $req->fetch()) {
		if($data['forum_username'] and $data['forum_psw']) {
			$resp = flarum_get_token($data['forum_username'], $data['forum_psw']);
			
			if(isset($resp->token)) {
				setcookie('flarum_remember', $resp->token, [
					'expires' => time()+31536000,
					'path' => '/',
					'domain' => FLARUM_SHORT_DOMAIN,
					'secure' => true,
					'httponly' => true,
					'samesite' => 'Lax'
				]);
				setcookie('flarum_token', $resp->token, [
					'expires' => time()+31536000,
					'path' => '/',
					'domain' => FLARUM_SHORT_DOMAIN,
					'secure' => true,
					'httponly' => true,
					'samesite' => 'Lax'
				]);
				
				return true;
			} else
				error_log('During auth_forum, forum responded:\n'.print_r($resp, true).'\n');
		}
	}
	return false;
}

?>
