<?php
if(!isset($noapi)) {
	header('Content-type: application/json');
	$noct = true;
	require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';
}
$api_version = '0.4.1-2';
?>
