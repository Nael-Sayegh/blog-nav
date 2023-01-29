<?php
function urlsafe_b64encode($str) {
	return strtr(preg_replace('/[\=]+\z/', '', base64_encode($str)), '+/=', '-_');
}

function urlsafe_b64decode($data) {
	$data = preg_replace('/[\t-\x0d\s]/', '', strtr($data, '-_', '+/'));
	$mod4 = strlen($data) % 4;
	if($mod4)
		$data .= substr('====', $mod4);
	return base64_decode($data);
}
$site_name='NVDA-FR';
$admin_css_path='<link rel="stylesheet" href="/admin/css/admin.css">';
require_once('dbconnect.php');
?>
