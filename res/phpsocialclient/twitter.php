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
$twdata = get_twitter($page, $tr['HTTP_LANG']);
$now = localtime();

?><!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title><?php echo str_replace('{{title}}', $twdata['title'], $tr['TW_TITLE']); ?></title>
		<style type="text/css">
@font-face {font-family: "Nimbus Sans L";src: url("NimbusSansL-Regular.otf");}
html, body, #thread {margin: 0;padding: 0;font-family: "Nimbus Sans L";background-color: #F6F7F9;}
.msg {margin: 10px 6px 10px 6px;background-color: white;border: 1px solid #F2F3F5;border-radius: 4px;box-shadow: 0 2px 5px rgba(0,0,0,0.3);transition: box-shadow 0.3s;}
.msg:hover {box-shadow: 0 2px 5px rgba(0,0,0,0.4);background-color: rgba(160,200,220,0.12);}
.msg_h {position: relative;min-height: 24px;margin: 0 4px 0 4px;padding: 2px 8px 2px 8px;border-bottom: 1px solid rgba(15,70,100,0.12);}
.msg_d {color: #90949C;transition: color 0.3s;}
.msg:hover .msg_d, .msg_focus .msg_d {color: #4B4F56;}
.msg_b {position: absolute;top: 2px;right: 8px;}
.msg_b a {outline: 0;}
.msg_bt {display: inline-block;margin-left: 4px;width: 24px;height: 24px;background-repeat: no-repeat;}
.msg_c {padding: 4px;}
.msg_t {margin: 0;font-size: 110%;}
.msg_t a, .msg_t a:visited {color: #3b94d9;text-decoration: none;transition: color 0.3s;}
.msg_t a:hover, .msg_t a:focus {color: #55acee;text-decoration: underline;}

.tw_like {background-image: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22%23657786%22%20d%3D%22M12%2021.638h-.014C9.403%2021.59%201.95%2014.856%201.95%208.478c0-3.064%202.525-5.754%205.403-5.754%202.29%200%203.83%201.58%204.646%202.73.813-1.148%202.353-2.73%204.644-2.73%202.88%200%205.404%202.69%205.404%205.755%200%206.375-7.454%2013.11-10.037%2013.156H12zM7.354%204.225c-2.08%200-3.903%201.988-3.903%204.255%200%205.74%207.035%2011.596%208.55%2011.658%201.52-.062%208.55-5.917%208.55-11.658%200-2.267-1.822-4.255-3.902-4.255-2.528%200-3.94%202.936-3.952%202.965-.23.562-1.156.562-1.387%200-.015-.03-1.426-2.965-3.955-2.965z%22%2F%3E%3C%2Fsvg%3E);}
.tw_like:hover, a:focus .tw_like {background-image: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22%23E0245E%22%20d%3D%22M12%2021.638h-.014C9.403%2021.59%201.95%2014.856%201.95%208.478c0-3.064%202.525-5.754%205.403-5.754%202.29%200%203.83%201.58%204.646%202.73.813-1.148%202.353-2.73%204.644-2.73%202.88%200%205.404%202.69%205.404%205.755%200%206.375-7.454%2013.11-10.037%2013.156H12zM7.354%204.225c-2.08%200-3.903%201.988-3.903%204.255%200%205.74%207.035%2011.596%208.55%2011.658%201.52-.062%208.55-5.917%208.55-11.658%200-2.267-1.822-4.255-3.902-4.255-2.528%200-3.94%202.936-3.952%202.965-.23.562-1.156.562-1.387%200-.015-.03-1.426-2.965-3.955-2.965z%22%2F%3E%3C%2Fsvg%3E);}
.tw_share {background-image: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22%23657786%22%20d%3D%22M21.78%2011.47l-5.14-5.14c-.292-.292-.767-.292-1.06%200s-.293.77%200%201.062l3.858%203.858H8.918c-.415%200-.75.336-.75.75s.335.75.75.75h10.52l-3.857%203.858c-.29.293-.29.768%200%201.06.148.147.34.22.53.22s.386-.072.53-.22l5.14-5.138c.294-.293.294-.767%200-1.06z%22%2F%3E%3Cpath%20fill%3D%22%23657786%22%20d%3D%22M9.944%2020.5H4.292c-.437%200-.792-.355-.792-.792V4.292c0-.437.355-.792.792-.792h5.652c.414%200%20.75-.336.75-.75S10.358%202%209.944%202H4.292C3.028%202%202%203.028%202%204.292v15.416C2%2020.972%203.028%2022%204.292%2022h5.652c.414%200%20.75-.336.75-.75s-.336-.75-.75-.75z%22%2F%3E%3C%2Fsvg%3E);}
.tw_share:hover, a:focus .tw_share {background-image: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%3Cpath%20fill%3D%22%231DA1F2%22%20d%3D%22M21.78%2011.47l-5.14-5.14c-.292-.292-.767-.292-1.06%200s-.293.77%200%201.062l3.858%203.858H8.918c-.415%200-.75.336-.75.75s.335.75.75.75h10.52l-3.857%203.858c-.29.293-.29.768%200%201.06.148.147.34.22.53.22s.386-.072.53-.22l5.14-5.138c.294-.293.294-.767%200-1.06z%22%2F%3E%3Cpath%20fill%3D%22%231DA1F2%22%20d%3D%22M9.944%2020.5H4.292c-.437%200-.792-.355-.792-.792V4.292c0-.437.355-.792.792-.792h5.652c.414%200%20.75-.336.75-.75S10.358%202%209.944%202H4.292C3.028%202%202%203.028%202%204.292v15.416C2%2020.972%203.028%2022%204.292%2022h5.652c.414%200%20.75-.336.75-.75s-.336-.75-.75-.75z%22%2F%3E%3C%2Fsvg%3E);}
.tw_status {background-image: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2072%2072%22%3E%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h72v72H0z%22%2F%3E%3Cpath%20class%3D%22icon%22%20fill%3D%22%23e1e8ed%22%20d%3D%22M68.812%2015.14c-2.348%201.04-4.87%201.744-7.52%202.06%202.704-1.62%204.78-4.186%205.757-7.243-2.53%201.5-5.33%202.592-8.314%203.176C56.35%2010.59%2052.948%209%2049.182%209c-7.23%200-13.092%205.86-13.092%2013.093%200%201.026.118%202.02.338%202.98C25.543%2024.527%2015.9%2019.318%209.44%2011.396c-1.125%201.936-1.77%204.184-1.77%206.58%200%204.543%202.312%208.552%205.824%2010.9-2.146-.07-4.165-.658-5.93-1.64-.002.056-.002.11-.002.163%200%206.345%204.513%2011.638%2010.504%2012.84-1.1.298-2.256.457-3.45.457-.845%200-1.666-.078-2.464-.23%201.667%205.2%206.5%208.985%2012.23%209.09-4.482%203.51-10.13%205.605-16.26%205.605-1.055%200-2.096-.06-3.122-.184%205.794%203.717%2012.676%205.882%2020.067%205.882%2024.083%200%2037.25-19.95%2037.25-37.25%200-.565-.013-1.133-.038-1.693%202.558-1.847%204.778-4.15%206.532-6.774z%22%2F%3E%3C%2Fsvg%3E);}
.msg:hover .tw_status, .msg:focus .tw_status, a:focus .tw_status {background-image: url(data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2072%2072%22%3E%3Cpath%20fill%3D%22none%22%20d%3D%22M0%200h72v72H0z%22%2F%3E%3Cpath%20class%3D%22icon%22%20fill%3D%22%231da1f2%22%20d%3D%22M68.812%2015.14c-2.348%201.04-4.87%201.744-7.52%202.06%202.704-1.62%204.78-4.186%205.757-7.243-2.53%201.5-5.33%202.592-8.314%203.176C56.35%2010.59%2052.948%209%2049.182%209c-7.23%200-13.092%205.86-13.092%2013.093%200%201.026.118%202.02.338%202.98C25.543%2024.527%2015.9%2019.318%209.44%2011.396c-1.125%201.936-1.77%204.184-1.77%206.58%200%204.543%202.312%208.552%205.824%2010.9-2.146-.07-4.165-.658-5.93-1.64-.002.056-.002.11-.002.163%200%206.345%204.513%2011.638%2010.504%2012.84-1.1.298-2.256.457-3.45.457-.845%200-1.666-.078-2.464-.23%201.667%205.2%206.5%208.985%2012.23%209.09-4.482%203.51-10.13%205.605-16.26%205.605-1.055%200-2.096-.06-3.122-.184%205.794%203.717%2012.676%205.882%2020.067%205.882%2024.083%200%2037.25-19.95%2037.25-37.25%200-.565-.013-1.133-.038-1.693%202.558-1.847%204.778-4.15%206.532-6.774z%22%2F%3E%3C%2Fsvg%3E);}
#credits {min-height: 48px;padding-left: 52px;background-image: url(images/zetta.svg);background-size: 48px 48px;background-repeat: no-repeat;margin: 8px;color: #808080;}
#credits a {color: #0080FF;text-decoration: none;}
#credits a:hover {text-decoration: underline;}
</style>
	</head>
	<body>
		<div id="thread">
<?php
foreach($twdata['msgs'] as &$msg) {
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
	echo '</span><div class="msg_b"><a href="https://twitter.com/intent/like?tweet_id='.$msg['id'].'" target="_parent" title="'.$tr['TW_LIKE'].'"><div class="msg_bt tw_like" aria-label="'.$tr['TW_LIKE_ARIA'].'"></div></a><a href="#" target="_parent" title="'.$tr['TW_SHARE'].'"><div class="msg_bt tw_share" aria-label="'.$tr['TW_SHARE_ARIA'].'"></div></a><a href="https://twitter.com/'.$page.'/status/'.$msg['id'].'" target="_parent" title="'.$tr['TW_TWLNK'].'"><div class="msg_bt tw_status" aria-label="'.$tr['TW_TWLNK_ARIA'].'"></div></a></div></div><div class="msg_c"><p class="msg_t">'.$msg['text'].'</p></div></div>';
}
unset($msg);
?>
		</div>
		<!-- License: GNU Lesser General Public License v3 http://www.gnu.org/licenses/ -->
	</body>
</html>
