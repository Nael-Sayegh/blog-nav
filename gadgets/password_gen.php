<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title=("Générateur de mots de passe by "."$site_name");
$sound_path="/audio/page_sounds/gadget.mp3";
$stats_page = 'parampasswd'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<p>Vous avez bien été redirigé vers notre générateur de mots de passe.</p>
<form method="post">
<label for="nombre">Nombre de mots de passe a générer :</label>
<input id="nombre" name="nbrPasswd" type="number" min="1" max="10" value="1" required>
<br>
<label for="taille">Nombre de caractères :</label>
<input id="taille" name="nbrChr" type="number" min="1" max="250" value="12" required>
<br>
<label for="type">Type de mot de passe :</label>
<select id="type" name="typePasswd" onchange="showother()">
<option value="1">Chiffres uniquement</option>
<option value="2">Lettres uniquement</option>
<option value="3">Caractères alphanumériques</option>
<option value="4">Caractères alphanumériques et autres</option>
<option value="5">Caractères alphanumériques et autres (personnalisés)</option>
</select>
<br>
<label for="f_charpers" style="display: none;">Spécifier les caractères spéciaux (tous collés)&nbsp;:</label>
<input type="text" name="charpers" id="f_charpers" style="display: none;">
<noscript>Ne spécifier les caractères que si "Caractères alphanumériques et autres (personnalisés)" est sélectionné</noscript><br>
<label for="maj">Majuscules aléatoires :</label>
<input type="checkbox" name="maj" id="maj"><br>
<input type="submit" value="Générer">
<script>
function showother() {
	if(document.getElementById("type").value == "5") {
		document.getElementById("f_charpers").style = "display: block;";
		document.getElementById("charpers").style = "display: block;";
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
if(isset($_POST['nbrPasswd']) and $_POST['nbrPasswd'] <= 10 and isset($_POST['nbrChr']) and $_POST['nbrChr'] <= 250 and isset($_POST['typePasswd'])) {
	$result = "";
	if($_POST['typePasswd'] == '1') $caract = '0123456789';
	else if($_POST['typePasswd'] == '2') $caract = 'abcdefghijklmnopqrstuvwxyz';
	else if($_POST['typePasswd'] == '3') $caract = 'abcdefghijklmnopqrstuvwxyz0123456789';
	else if($_POST['typePasswd'] == '4') $caract = 'abcdefghijklmnopqrstuvwxyz0123456789@!:;,/?*$=+.-_ &)(][{}#"\'';
	else if($_POST['typePasswd'] == '5' && isset($_POST['charpers']) && !empty($_POST['charpers'])) $caract = 'abcdefghijklmnopqrstuvwxyz0123456789'.$_POST['charpers'];
	for($nbrPasswd = 1; $nbrPasswd <=  $_POST['nbrPasswd']; $nbrPasswd++) {
		$password = '';
		for($i = 1; $i <= $_POST['nbrChr']; $i++) {
			if(isset($_POST['maj']) and $_POST['maj'] == 'on' and rand(0,2) == 1)
				$password .= strtoupper($caract[mt_rand(0,(strlen($caract)-1))]);
			else
				$password .= $caract[mt_rand(0,(strlen($caract)-1))];
		}
		$result .= $password . "\n";
	}
		$result = trim($result);
		echo nl2br($result);
}
?>
</p>
<?php
if(isset($_POST['nbrPasswd']) and isset($_POST['nbrChr']) and isset($_POST['typePasswd'])) { ?>
<button id="BtnCopy" onclick="CopyToClipboard('result')">Copier le résultat</button><br>
<script type="text/javascript">
function CopyToClipboard(containerid) {
	var text = document.getElementById(containerid).innerText;
	navigator.clipboard.writeText(text).then(() => {
		document.querySelector('#BtnCopy').innerHTML = 'Résultat copié !';
		var delayInMilliseconds = 3000;
		setTimeout(function() {
			document.querySelector('#BtnCopy').innerHTML = 'Copier le résultat';
			},
		delayInMilliseconds);
		}).catch((error) => {
			console.error('Erreur lors de la copie', error); });
		}
</script>
<?php } ?>
<a href="/gadgets.php">Retour à la liste des gadgets.</a>
</div>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>