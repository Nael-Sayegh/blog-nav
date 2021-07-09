<?php
$params = '';
if(isset($_GET['id']) and preg_match('/[0-9]+/',$_GET['id']))
	$params .= 'id='.$_GET['id'];
if(!empty($params)) {
header('Location: /cat.php?'.$params);
}
?>