<?php
$logonly = true;
$adminonly = true;
include_once 'inclus/log.php';
$stats_page='liste_comptes';
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once 'inclus/consts.php';
$cheminaudio='/audio/sons_des_pages/membre.mp3';
$chemincss .= '<style>.tr2{background-color:#E0E0E0;}</style>';
$titre = 'Liste des membres '.$nomdusite;
?>
<!doctype html>
<html lang="fr">
<?php include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="Retour à l'accueil"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</div>
<?php include('inclus/son.php');
include('inclus/menu.php'); ?>
<div id="container" role="main">
	<h1 id="contenu"><?php print $titre; ?></h1>
	<form action="/alist.php" method="get">
		<label for="f1_sort">Trier par&nbsp;:</label>
		<select name="sort" id="f1_sort"><option value="date" selected>Date d'inscription</option><option value="username">Ordre alphabétique</option></select>
		<input type="submit" value="Trier" style="cursor:pointer;" />
	</form>
	<table style="width:100%;">
		<thead><tr><th>Numéro de membre</th><th>Nom</th><th>Inscription</th><th>Rang</th><?php /*<th>Actions</th>*/ ?></tr></thead>
		<tbody>
<?php
include_once('inclus/user_rank.php');
$order = 'signup_date';
if(isset($_GET['sort'])) {
	switch($_GET['sort']) {
		case 'date': $order = 'signup_date'; break;
		case 'username': $order = 'username'; break;
	}
}
$req = $bdd->prepare('
	SELECT `accounts`.`id` AS `account_id`, `accounts`.`username` AS `account_name`, `accounts`.`signup_date` AS `account_signup_date`, `accounts`.`rank` AS `account_rank`, `accounts`.`settings` AS `settings`, `team`.`id` AS `team_id` 
	FROM `accounts` 
	LEFT JOIN `team` ON `team`.`account_id` = `accounts`.`id` 
	ORDER BY `accounts`.`'.$order.'`');
$req->execute();
$tr2 = true;
$n = 0;
while($data = $req->fetch()) {
	$sets = json_decode($data['settings'], true);
	echo '<tr';
	if($tr2) echo ' class="tr2"';
	echo '><td>M'.$data['account_id'];
	if($data['account_rank'] == 'a')
		echo '/E'.$data['team_id'];
	echo '</td><td>'.urank($data['account_rank'],htmlentities($data['account_name']),false);
	$date=getdate();
	
	// Check birthday
	if(isset($sets['bd_m']) and isset($sets['bd_d']) and (($sets['bd_m']==$date['mon'] and $sets['bd_d']==$date['mday']) or ($sets['bd_m']==2 and $date['mon']==3 and $sets['bd_d']==29 and $date['mday']==1 and $date['year']%4==0))) echo ' &#127874;';
	
	echo '</td><td>'.date('d/m/Y',$data['account_signup_date']).'</td><td>'.urank($data['account_rank']).'</td>';/*<td><a href="/sendpm.php?id='.$data['id64'].'" title="Envoyer un message privé"><img alt="Envoyer un message privé" src="/image/message.png" /></a></td>*/
	echo '</tr>';
	$tr2 = !$tr2;
	$n ++;
}
?>
		</tbody>
	</table>
	<p><b><?php echo $n; ?></b> membres trouvés</p>
</div>
<?php include 'inclus/footer.php'; ?>
</body>
</html>
