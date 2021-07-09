<?php
/* Copyright (c) 2017 Pascal Engélibert
This file is part of PHPSocialClient.
PHPSocialClient is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
PHPSocialClient is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
You should have received a copy of the GNU Lesser General Public License along with PHPSocialClient. If not, see <http://www.gnu.org/licenses/>.
*/
error_reporting(E_ERROR | E_PARSE);
function get_facebook($page, $httplang) {
	$qpage = preg_quote($page, '/');
	$url = 'https://www.facebook.com/'.$page.'/posts/';
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_USERAGENT, 'User-Agent: PHPSocialClient');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: text/html','Accept-Language: '.$httplang,'DNT: 1','Connection: close','Upgrade-Insecure-Requests: 1','Pragma: no-cache','Cache-Control: no-cache'));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($curl);
	curl_close($curl);
	$dom = new DOMDocument();
	try {
		$dom->loadHTML($data);
	} catch(Exception $e) {}
	
	$title = '';
	$nodes = $dom->getElementsByTagName('meta');
	foreach($nodes as $node) {
		if($node->attributes->getNamedItem('property')->value == 'og:title')
			$title = $node->attributes->getNamedItem('content')->value;
	}
	unset($node);
	
	$msgs = array();
	$domx = new DOMXPath($dom);
	$nodes = $domx->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' userContentWrapper ')]");
	foreach($nodes as $node) {
		$msghtml = $dom->saveHTML($node);
		$msgdom = new DOMDocument();
		$msgdom->loadHTML('<meta charset="utf-8" />'.$msghtml);
		$msgtimenode = $msgdom->getElementsByTagName('abbr');
		$msgid = '';
		$msgtime = '';
		if(isset($msgtimenode[0]))
			$msgtime = $msgtimenode[0]->attributes->getNamedItem('data-utime')->value;
			$msgid = preg_replace('/\/'.$qpage.'\/posts\/([0-9]+)/', '$1', $msgtimenode[0]->parentNode->attributes->getNamedItem('href')->value);
		preg_match('/<p>(.+)<\/p>/', $msghtml, $msgtexthtml);
		$msgtext = $msgtexthtml[1];
		$msgtext = preg_replace('/<a href="https:\/\/l\.facebook\.com\/l\.php\?u=(.+?)\&amp;.+?<\/a>/', '<a href="$1" target="_parent">$1</a>', $msgtext);
		$msgs[] = array('id'=>$msgid, 'text'=>urldecode($msgtext), 'time'=>intval($msgtime));
	}
	unset($node);
	return array('title'=>$title, 'msgs'=>$msgs);
}

function get_twitter($page, $httplang) {
	$url = 'https://twitter.com/'.$page.'/';
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_USERAGENT, 'User-Agent: PHPSocialClient');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: text/html','Accept-Language: '.$httplang,'DNT: 1','Connection: close','Upgrade-Insecure-Requests: 1','Pragma: no-cache','Cache-Control: no-cache'));
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($curl);
	curl_close($curl);
	$msgs = array();
	$dom = new DOMDocument();
	try {
		$dom->loadHTML($data);
	} catch(Exception $e) {}
	$title = '';
	$nodes = $dom->getElementsByTagName('h1');
	if(isset($nodes[0])) {
		$nodes = $nodes[0]->getElementsByTagName('a');
		if(isset($nodes[0]))
			$title = $nodes[0]->textContent;
	}
	$domx = new DOMXPath($dom);
	$nodes = $domx->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' content ')]");
	foreach($nodes as $node) {
		$msgid = $node->parentNode->attributes->getNamedItem('data-item-id')->value;
		$msghtml = $dom->saveHTML($node);
		$msgdom = new DOMDocument();
		$msgdom->loadHTML('<meta charset="utf-8" />'.$msghtml);
		$msgtime = '';
		$msgtimenode = $msgdom->getElementsByTagName('small');
		if(isset($msgtimenode[0])) {
			$msgtimenodes = $msgtimenode[0]->getElementsByTagName('span');
			if(isset($msgtimenodes[0]))
				$msgtime = $msgtimenodes[0]->attributes->getNamedItem('data-time')->value;
		}
		preg_match('/<p.+?>(.+?)<\/p>/', $msghtml, $msgtexthtml);
		$msgtext = $msgtexthtml[1];
		$msgtext = preg_replace('/<a href=".+?data-expanded-url="(.+?)".+?<\/a>/', '<a href="$1" target="_parent">$1</a>', $msgtext);
		$msgs[] = array('id'=>$msgid, 'text'=>urldecode($msgtext), 'time'=>intval($msgtime));
	}
	return array('title'=>$title, 'msgs'=>$msgs);
}
?>
