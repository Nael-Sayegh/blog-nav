<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'inclus/log.php';
require_once "inclus/consts.php";
$titre=("Générateur de mots de passe by "."$nomdusite");
$cheminaudio="/audio/sons_des_pages/gadget.mp3";
$stats_page = 'parampasswd'; ?>
<!doctype html>
<html lang="fr">
<?php include 'inclus/header.php'; ?>
<body>
<div id="hautpage" role="banner">
<h1><a href="/" title="Retour à l'accueil"><?php print $nomdusite; ?></a></h1>
<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'inclus/trident.php';
include 'inclus/searchtool.php';
include 'inclus/loginbox.php'; ?>
</div>
<?php include('inclus/son.php');
include 'inclus/menu.php'; ?>
<div id="container" role="main">
<h1 id="contenu"><?php print $titre; ?></h1>
<p>Vous avez bien été redirigé vers notre générateur de mots de passe.</p>
<form action="ParamPasswd.php" method="post">
<label for="nombre">Nombre de mots de passe a générer :</label>
<input id="nombre" name="nbrPasswd" type="number" required />
<br />
<label for="taille">Nombre de caractères :</label>
<input id="taille" name="nbrChr" type="number" required />
<br />
<label for="type">Type de mot de passe :</label>
<select id="type" name="typePasswd" onchange="showother()">
<option value="1">Chiffres uniquement</option>
<option value="2">Lettres uniquement</option>
<option value="3">Caractères alphanumériques</option>
<option value="4">Caractères alphanumériques et autres</option>
<option value="5">Caractères alphanumériques et autres (personnalisés)</option>
</select>
<br />
<label for="f_charpers">Spécifier les caractères spéciaux (tous collés)&nbsp;:</label>
<input type="text" name="charpers" id="f_charpers" />
<noscript>Ne spécifier les caractères que si "Caractères alphanumériques et autres (personnalisés)" est sélectionné</noscript><br />
<label for="maj">Majuscules aléatoires :</label>
<input type="checkbox" name="maj" id="maj" /><br />
<input type="submit" value="Générer" />
<script type="text/javascript">
function showother() {
	if(document.getElementById("type").value == "5") {
		document.getElementById("f_charpers").style = "";
		document.getElementById("charpers").style = "";
	} else {
		document.getElementById("f_charpers").style = "display: none;";
		document.getElementById("charpers").style = "display: none;";
	}
}
showother();
</script>
</form>
<p id="result">
<?php
if(isset($_POST['nbrPasswd']) and isset($_POST['nbrChr']) and isset($_POST['typePasswd'])) {
	if($_POST['typePasswd'] == '1') $caract = '0123456789';
	else if($_POST['typePasswd'] == '2') $caract = 'abcdefghijklmnopqrstuvwxyz';
	else if($_POST['typePasswd'] == '3') $caract = 'abcdefghijklmnopqrstuvwxyz0123456789';
	else if($_POST['typePasswd'] == '4') $caract = 'abcdefghijklmnopqrstuvwxyz0123456789@!:;,/?*$=+.-_ &)(][{}#"\'';
	else if($_POST['typePasswd'] == '5' && isset($_POST['charpers']) && !empty($_POST['charpers'])) $caract = 'abcdefghijklmnopqrstuvwxyz0123456789'.$_POST['charpers'];
	for($nbrPasswd = 1; $nbrPasswd <=  $_POST['nbrPasswd']; $nbrPasswd++) {
		for($i = 1; $i <= $_POST['nbrChr']; $i++) {
			if(isset($_POST['maj']) and $_POST['maj'] == 'on' and rand(0,2) == 1)
				print strtoupper($caract[mt_rand(0,(strlen($caract)-1))]);
			else
				print $caract[mt_rand(0,(strlen($caract)-1))];
		}
		echo '<br />';
	}
}
?>
</p>
<?php
if(isset($_POST['nbrPasswd']) and isset($_POST['nbrChr']) and isset($_POST['typePasswd'])) { ?>
<button id="BtnCopy" onclick="CopyToClipboard('result')">Copier le résultat</button><br />
<script type="text/javascript">
function CopyToClipboard(containerid) {
  if (document.selection) {
    var range = document.body.createTextRange();
    range.moveToElementText(document.getElementById(containerid));
    range.select().createTextRange();
    document.execCommand("copy");
  } else if (window.getSelection) {
    var range = document.createRange();
    range.selectNode(document.getElementById(containerid));
    window.getSelection().addRange(range);
    document.execCommand("copy");
    document.querySelector('#BtnCopy').innerHTML = 'Résultat copié !';
    document.querySelector('#BtnCopy').innerText = 'Résultat copié !';
    document.querySelector('#BtnCopy').textContent = 'Résultat copié !';
    var delayInMilliseconds = 3000;
    setTimeout(function() {
        document.querySelector('#BtnCopy').innerHTML = 'Copier le résultat';
        document.querySelector('#BtnCopy').innerText = 'Copier le résultat';
        document.querySelector('#BtnCopy').textContent = 'Copier le résultat';
    }, delayInMilliseconds);
  }
}
</script>
<?php } ?>
<a href="/gadgets.php">Retour à la liste des gadgets.</a>
</div>
</div>
<?php require_once "inclus/footer.php"; ?>
</body>
</html>