<?php
if(!isset($noapi)) {
	header('Content-type: application/json');
	$noct = true;
	require_once $_SERVER['DOCUMENT_ROOT'].'/include/consts.php';
}
$api_version = '0.5.2-3';
?>
