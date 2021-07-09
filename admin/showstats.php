<?php $logonly = true;
$adminonly=true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';
$from = date('Y-m-d', time()-604800);# à partir de quand (en format date SQL) (1 semaine dans le passé par défaut)
$to = date('Y-m-d', time());# jusqu'à quand (en format date SQL) (aujourd'hui par défaut)

if(isset($_GET['from']) and !empty($_GET['from']))
	$from = $_GET['from'];

if(isset($_GET['to']) and !empty($_GET['to']))
	$to = $_GET['to'];

$domain = '';
if(isset($_GET['domain']) and in_array($_GET['domain'], array('pa33','pa33_dev','pa33_onion','pa33_onion_dev')))
	$domain = $_GET['domain'];
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Visionnage des statistiques de <?php print $nomdusite; ?></title>
<?php print $cssadmin; ?>
		<link rel="stylesheet" href="css/showstats.css" />
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<h1>Statistiques - <a href="/"><?php print $nomdusite.' '.$versionnom; ?></a></h1>
<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php'; ?>
		<form action="showstats.php" method="get">
			<label for="f1_from">Depuis le (AAAA-MM-JJ)&nbsp;:</label><input type="text" id="f1_from" name="from" value="<?php echo $from; ?>" maxlength="10" /><br />
			<label for="f1_to">Jusqu'au (AAAA-MM-JJ)&nbsp;:</label><input type="text" id="f1_to" name="to" value="<?php echo $to; ?>" maxlength="10" /><br />
			<label for="f1_dom">Domaine&nbsp;:</label><select id="f1_dom" name="domain"><option value="" selected>Tout<option value="pa33">httpdocs</option><option value="pa33_dev">dev</option><option value="pa33_onion">onion</option><option value="pa33_onion_dev">onion dev</option></select><br />
			<input type="submit" value="rechercher" />
		</form><br />
		<p>Domaine&nbsp;: <?php echo $domain; ?></p>
		<table class="tstats">
<?php

$reqp = '';
if(!empty($domain))
	$reqp = ' `domain`="'.$domain.'" AND';

$req = $bdd->prepare('SELECT * FROM `count_visits` WHERE'.$reqp.' `date` BETWEEN ? AND ? ORDER BY `date` ASC');
$req->execute(array($from, $to));

$visits = array();# toutes les entrées
$pages = array();# les différentes pages
$maxv = 0;
while($data = $req->fetch()) {
	$visits[] = $data;
	if(!in_array($data['page'], $pages))
		$pages[] = $data['page'];
	if($data['visits'] > $maxv)
		$maxv = $data['visits'];
}

$visitors = array();
$req = $bdd->prepare('SELECT `date`,`visitors` FROM `daily_visitors` WHERE'.$reqp.' `date` BETWEEN ? AND ? ORDER BY `date` ASC');
$req->execute(array($from, $to));
while($data = $req->fetch()) {
	if(isset($visitors[$data['date']]))
		$visitors[$data['date']] += $data['visitors'];
	else
		$visitors[$data['date']] = $data['visitors'];
}

echo '<thead><tr class="tstats_th tstats_2"><th class="tstats_hd">Date</th><th class="tstats_ht">Total</th><th class="tstats_ht">Visiteurs</th>';
foreach($pages as &$page) {
	echo '<th class="tstats_hp">'.$page.'</th>';
}
unset($page);
echo '</tr></thead><tbody>';
$maxv *= 2;

$date = array();# une date avec la liste des visites avec page en index
$curdate = '';
$total = 0;
$subtotal = 0;
$subtotals = array();
$allpages = array();
$alldvisitors = array();
$line = false;
foreach($visits as &$visit) {
	if($curdate != $visit['date'] and !empty($date)) {
		echo '<tr';
		if($line)
			echo ' class="tstats_2"';
		$dvisitors = 0;
		if(isset($visitors[$curdate]))
			$dvisitors = $visitors[$curdate];
		$alldvisitors[] = $dvisitors;
		echo '><td class="tstats_date">'.$curdate.'</td><td class="tstats_dt">'.$subtotal.'</td><td class="tstats_dt">'.$dvisitors.'</td>';
		foreach($pages as &$page) {
			echo '<td class="tstats_pt"';
			if(isset($date[$page])) {
				echo ' style="background-color:rgba(4,180,4,'.$date[$page]/$maxv.');">'.$date[$page];
				if(isset($allpages[$page])) $allpages[$page] += $date[$page];
				else $allpages[$page] = $date[$page];
			}
			else {
				echo '>0';
				if(!isset($allpages[$pages])) $allpages[$page] = 0;
			}
			echo '</td>';
		}
		echo '</tr>';
		$date = array();
		$subtotals[] = $subtotal;
		$subtotal = 0;
		$line = !$line;
	}
	$curdate = $visit['date'];
	$date[$visit['page']] = $visit['visits'];
	$total += $visit['visits'];
	$subtotal += $visit['visits'];
}
if(!empty($date)) {
	echo '<tr';
	if($line)
		echo ' class="tstats_2"';
	$dvisitors = 0;
	if(isset($visitors[$curdate]))
		$dvisitors = $visitors[$curdate];
	echo '><td class="tstats_date">'.$curdate.'</td><td class="tstats_dt">'.$subtotal.'</td><td class="tstats_dt">'.$dvisitors.'</td>';
	foreach($pages as &$page) {
		echo '<td class="tstats_pt"';
		if(isset($date[$page]))
			echo ' style="background-color:rgba(4,180,4,'.$date[$page]/$maxv.');">'.$date[$page];
		else
			echo '>0';
		echo '</td>';
	}
	echo '</tr>';
	$line = !$line;
}

//Means
$ndays = count($subtotals);
echo '<tr';
if($line)
	echo ' class="tstats_2"';
$dvisitors = 0;
if(isset($visitors[$curdate]))
	$dvisitors = $visitors[$curdate];
echo '><td class="tstats_date">Moyenne</td><td class="tstats_dt">'.intval(array_sum($subtotals)/$ndays).'</td><td class="tstats_dt">'.intval(array_sum($alldvisitors)/$ndays).'</td>';
foreach($pages as &$page) {
	echo '<td class="tstats_pt">'.intval($allpages[$page]/$ndays).'</td>';
}
echo '</tr>';

echo '</tbody>';
?>
		</table>
		<p>Total de la période&nbsp;: <strong><?php echo $total; ?></strong> visites sur <?php echo $ndays; ?> jours.</p>
	</body>
</html>
