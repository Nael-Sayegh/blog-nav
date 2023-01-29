<?php
$logonly = true;
$adminonly = true;
require_once('include/log.php');
$stats_page='liste_comptes';
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/consts.php');
$sound_path='/audio/page_sounds/member.mp3';
$css_path .= '<style>.tr2{background-color:#E0E0E0;}</style>';
$title = 'Liste des membres '.$site_name;
?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
	<h1 id="contenu"><?php print $title; ?></h1>
	<form action="/members_list.php" method="get">
		<label for="f1_sort">Trier par&nbsp;:</label>
		<select name="sort" id="f1_sort"><option value="date" selected>Date d'inscription</option><option value="username">Ordre alphabétique</option></select>
		<input type="submit" value="Trier" style="cursor:pointer;">
	</form>
	<table style="width:100%;">
		<thead><tr><th>Numéro de membre</th><th>Nom</th><th>Inscription</th><th>Rang</th><?php /*<th>Actions</th>*/ ?></tr></thead>
		<tbody>
<?php
include_once('include/user_rank.php');
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
	
	echo '</td><td>'.date('d/m/Y',$data['account_signup_date']).'</td><td>'.urank($data['account_rank']).'</td>';/*<td><a href="/sendpm.php?id='.$data['id64'].'" title="Envoyer un message privé"><img alt="Envoyer un message privé" src="/image/message.png"></a></td>*/
	echo '</tr>';
	$tr2 = !$tr2;
	$n ++;
}
?>
		</tbody>
	</table>
	<p><b><?php echo $n; ?></b> membres trouvés</p>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>