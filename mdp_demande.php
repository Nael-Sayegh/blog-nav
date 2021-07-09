<?php
$nolog = true;
set_include_path($_SERVER['DOCUMENT_ROOT']);
$stats_page = 'mdpforget';
include_once 'inclus/log.php';
require_once 'inclus/consts.php';
$titre='Mot de passe oublié';
$cheminaudio='/audio/sons_des_pages/membre.mp3';
?>
<!doctype html>
<html lang="fr">
<?php $chemincss .= '<link rel="stylesheet" href="/css/slider.css" />';
include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="Retour à l'accueil"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/loginbox.php';
include 'inclus/searchtool.php'; ?>
</div>
<?php include 'inclus/son.php';
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
<p>SI vous avez oublié le mot de passe de votre compte membre sur <?php print $nomdusite; ?>, vous pouvez remplir le formulaire ci-dessous.<br />
Des informations bien précises vous sont demandés telles que votre numéro de membre ou votre date d'inscription, si vous ne les connaissez pas la réinitialisation sera plus compliquée puisqu'il nous sera moins évident de nous assurer que vous êtes bien le propriétaire légitime du compte.<br />
Dans tous les cas, votre demande sera traîtée par un membre de l'équipe administrative qui vous répondra dans les meilleurs délets.</p>
<form action="mdp_verif.php" method="post" spellcheck="true">
<fieldset>
<legend>Informations personnelles :</legend>
<label for="f_identite">Votre nom d'utilisateur :</label><input type="text" name="identite" id="f_identite" autocomplete="off" maxlength="100" required /><br />
<label for="f_email">Votre adresse mail :</label><input type="email" name="email" id="f_email" autocomplete="off" maxlength="100" required /><br />
<label for="f_sujet">Votre numéro de membre :</label>
<select name="sujet" id="f_sujet">
<?php
$req = $bdd->query('SELECT * FROM `accounts` ORDER BY id ASC');
while($data = $req->fetch()) {
echo '<option value="'.$data['id'].'">'.$data['id'].'</option>';
}
?>
<option value="non">Je ne sais pas</option>
</select><br />
<label for="f_msg">Votre date d'inscription :</label>
<select name="msg" id="f_msg">
<?php
$req2 = $bdd->query('SELECT * FROM `accounts` ORDER BY id ASC');
while($data = $req2->fetch()) {
echo '<option value="'.$data['signup_date'].'">'.date('d/m/Y à H:i:s',$data['signup_date']).'</option>';
}
?>
<option value="non">Je ne sais pas</option>
</select><br />
<input type="submit" value="Envoyer" />
</fieldset>
</form>
</div>
<?php include 'inclus/footer.php'; ?>
</body>
</html>