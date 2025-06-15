<?php $logonly = true;
$adminonly = true;
$justbn = true;
$titlePAdm = 'Envoyer la lettre d\'informations';
require_once($_SERVER['DOCUMENT_ROOT'].'/include/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/include/consts.php');
requireAdminRight('manage_newsletter');

if (isset($_GET['act']) && $_GET['act'] === 'form')
{
    if (isset($_POST['mail']) && !empty($_POST['mail']))
    {
        $debug = $_POST['mail'];
    }
    if (isset($_POST['simulate']))
    {
        $simulate = true;
    }
    header('Content-type: text/plain');
    header('Content-disposition: inline');
    require_once($_SERVER['DOCUMENT_ROOT'].'/tasks/nl_manager.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Administration - <?php print $site_name; ?></title>
<?php print $admin_css_path; ?>
<script type="text/javascript" src="/scripts/default.js"></script>
</head>
<body>
<?php require_once('include/banner.php'); ?>
<form action="?act=form" method="post">
<label for="maildebug">Debuguer pour&nbsp;:</label>
<input type="email" name="mail" id="maildebug"><br>
<label for="mailsimulate">Simulation (n'envoie aucun mail, ne modifie pas la bdd)&nbsp;:</label>
<input type="checkbox" name="simulate" id="mailsimulate"><br>
<input type="submit" value="Envoyer">
</form>
</body>
</html>
