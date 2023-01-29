<?php
$document_root = __DIR__.'/..';
require_once($document_root.'/include/consts.php');

# delete visitors entries up to 1 week
$req = $bdd->prepare('DELETE FROM `count_visitors` WHERE `lastvisit`<?');
$req->execute(array(time()-604800));

# count daily visitors
$visitors = array();
$date = date('Y-m-d', strtotime('-1 day'));
$req = $bdd->prepare('SELECT `domain` FROM `count_visitors` WHERE `lastvisit` BETWEEN ? AND ?');
$req->execute(array(time()-86400, time()));
while($data = $req->fetch()) {
	if(isset($visitors[$data['domain']]))
		$visitors[$data['domain']][0] ++;
	else
		$visitors[$data['domain']] = array(1, $data['domain']);
}
foreach($visitors as &$domain) {
	$req = $bdd->prepare('INSERT INTO `daily_visitors` (`date`,`visitors`,`domain`) VALUES (?,?,?)');
	$req->execute(array($date, $domain[0], $domain[1]));
}
?>
