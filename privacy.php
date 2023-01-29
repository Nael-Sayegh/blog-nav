<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title=' politique de confidentialité '.$site_name;
$sound_path='/audio/page_sounds/privacy.mp3';
$stats_page = 'conf'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<h2><?php print $site_name; ?> respecte votre vie privée</h2>
<p>Aucune de vos données personnelles (cookies, adresse IP, etc.) n'est transmise à un tiers.<br>
Votre adresse IP est conservée temporairement pour des raisons techniques (comptage anonyme des visiteurs, antispam, utilisation de l'espace membre, modification d'un commentaire sur un logiciel), mais ne servira pas à vous identifier.<br>
À part de manière temporaire (votre position n'est pas enregistrée) dans le gadget "Informations diverses", nous n'utilisons pas vos données personnelles pour vous géolocaliser.</p>
<p>Nous n'utilisons pas et n'utiliserons jamais de méthodes de <a href="https://fr.wikipedia.org/wiki/Empreinte_digitale_d'appareil">fingerprint</a> pour vous identifier contre votre gré.</p>

<h3>Sécurité</h3>
<p>La connexion entre votre ordinateur et ProgAccess est sécurisée de bout en bout grâce à un certificat TLS.<br>
Cela signifie qu'il est impossible pour un tiers de falsifier les pages web du site ou d'intercepter vos données.<br>
Vos mots de passe stockés dans la base de données sont sécurisés de sorte qu'il est impossible, aussi bien pour nous que pour d'éventuels pirates, d'y accéder.</p>

<h3>Les cookies</h3>
<p>Un cookie est une petite donnée déposée dans votre navigateur par un site internet.<br>
Vous avez à tout moment la possibilité de bloquer l'usage des cookies ou de les supprimer.<br>
Un cookie ne peut aucunement représenter une menace de sécurité.</p>
<p>En tout cas, ProgAccess ne dépose des cookies dans votre ordinateur qu'avec votre consentement au préalable&nbsp;: un message vous prévient si l'usage des cookies est nécessaire pour utiliser une certaine fonctionnalité.<br>
Les cookies utilisés servent exclusivement à la sauvegarde des paramètres de personnalisation et à l'utilisation de l'espace membres ProgAccess.</p>
<h3>En savoir plus</h3>
<p>Si vous désirez en savoir plus sur la sécurité de vos données, nos méthodes, votre anonymat, ou toute autre chose, vous pouvez nous contacter via le <a href="/contact_form.php">formulaire de contact</a> avec le sujet "<i>demande sur la politique de confidentialité</i>".</p>
<p>Vous disposez également d'un droit d'accès, de modification et de suppression de vos données, à condition de pouvoir vous identifier comme le propriétaire de ces données.</p>
<h3>Esprit critique</h3>
<p>Il nous est absolument impossible de vous prouver que les promesses ci-dessus seront tenues. Il en va de même pour n'importe quel service en ligne. Nous vous invitons donc à ne pas les croire si vous ne leur accordez aucune confiance particulière, et à ne pas donner des informations à des gens à qui vous ne voudriez pas donner ces informations. Considérez que dès que vous donnez une information à quelqu'un (ou à une machine appartenant à quelqu'un), vous lui donnez tout contrôle dessus.</p>
<p>Pour cela, nous vous conseillons d'utiliser des logiciels libres (Firefox, Chromium), un bloqueur de publicités et de traqueurs (µBlock Origin), ne pas utiliser de VPN centralisé.</p>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>