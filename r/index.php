<?php
$params = '';
if(isset($_GET['p']) and preg_match('/[0-9a-z_]+/',$_GET['p']))
	$params .= 'p='.$_GET['p'];
elseif(isset($_GET['i']) and preg_match('/[0-9]+/',$_GET['i']))
	$params .= 'id='.$_GET['i'];
elseif(isset($_GET['id']) and preg_match('/[0-9]+/',$_GET['id']))
	$params .= 'id='.$_GET['id'];
if(!empty($params)) {
	if(isset($_GET['m']))
		$params .= '&m';
	header('Location: /r.php?'.$params);
}
?>