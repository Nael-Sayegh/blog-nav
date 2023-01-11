<?php
/* Copyright (c) 2017 Pascal Engélibert
This file is part of PHPSocialClient.
PHPSocialClient is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
PHPSocialClient is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
You should have received a copy of the GNU Lesser General Public License along with PHPSocialClient. If not, see <http://www.gnu.org/licenses/>.
*/

/* SETTINGS */

$page = 'ProgAccess';

/* CODE */

$lang = 'fr';
require('templates/locales.php');
date_default_timezone_set('Europe/Paris'); 
setlocale(LC_TIME, $tr['PHP_LANG']);
require_once('templates/getdata.php');
$fbdata = get_facebook($page, $tr['HTTP_LANG']);
$now = localtime();

?><!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title><?php echo str_replace('{{title}}', $fbdata['title'], $tr['FB_TITLE']); ?></title>
		<style type="text/css">
@font-face {font-family: "Nimbus Sans L";src: url("NimbusSansL-Regular.otf");}
html, body, #thread {margin: 0;padding: 0;font-family: "Nimbus Sans L";background-color: #F6F7F9;}
.msg {margin: 10px 6px 10px 6px;background-color: white;border: 1px solid #F2F3F5;border-radius: 4px;box-shadow: 0 2px 5px rgba(0,0,0,0.3);transition: box-shadow 0.3s;}
.msg:hover {box-shadow: 0 2px 5px rgba(0,0,0,0.4);}
.msg_h {position: relative;min-height: 24px;margin: 0 4px 0 4px;padding: 2px 8px 2px 8px;border-bottom: 1px solid #F6F7F9;transition: border-bottom-color 0.3s;}
.msg:hover .msg_h, .msg:focus .msg_h {border-bottom-color: #F2F3F5;}
.msg_d {color: #90949C;transition: color 0.3s;}
.msg:hover .msg_d, .msg_focus .msg_d {color: #4B4F56;}
.msg_b {position: absolute;top: 2px;right: 8px;}
.msg_c {padding: 4px;}
.msg_t {margin: 0;}
.msg_t a, .msg_t a:visited {color: #365899;text-decoration: none;}
.msg_t a:hover, .msg_t a:focus {text-decoration: underline;}
#credits {min-height: 48px;padding-left: 52px;background-image: url(images/zetta.svg);background-size: 48px 48px;background-repeat: no-repeat;margin: 8px;color: #808080;}
#credits a {color: #0080FF;text-decoration: none;}
#credits a:hover {text-decoration: underline;}
</style>
	</head>
	<body>
		<div id="thread">
<?php
foreach($fbdata['msgs'] as &$msg) {
	$time = localtime($msg['time']);
	echo '<div class="msg"><div class="msg_h"><span class="msg_d">';
	if($time[5] == $now[5]) {# same year
		if($time[4] == $now[4]) {# same month
			if($time[3] == $now[3])# same day
				echo strftime($tr['PHP_TIME_HOUR'], $msg['time']);
			else
				echo strftime($tr['PHP_TIME_DAY'], $msg['time']);
		}
		else
			echo strftime($tr['PHP_TIME_MONTH'], $msg['time']);
	}
	else
		echo strftime($tr['PHP_TIME_YEAR'], $msg['time']);
	echo '</span><div class="msg_b"><a href="https://facebook.com/sharer/sharer.php?u=http%3A%2F%2Ffacebook.com%2F'.$page.'%2Fposts%2F'.$msg['id'].'&display=popup&ref=plugin&src=post" target="_parent"><img alt="'.$tr['FB_SHARE'].'" src="images/fb_share24.png"></a><a href="https://facebook.com/'.$page.'/posts/'.$msg['id'].'" target="_parent"><img alt="'.$tr['FB_FBLNK'].'" src="images/fb_logo24.png"></a></div></div><div class="msg_c"><p class="msg_t">'.$msg['text'].'</p></div></div>';
}
unset($msg);
?>
		</div>
		<!-- License: GNU Lesser General Public License v3 http://www.gnu.org/licenses/ -->
	</body>
</html>
