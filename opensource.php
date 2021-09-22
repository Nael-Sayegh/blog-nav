<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
include_once 'inclus/log.php';
require_once 'inclus/consts.php';
$titre='Open-source';
$cheminaudio='/audio/sons_des_pages/harp_notif.mp3';
$stats_page='open-source'; ?>
<!doctype html>
<html lang="fr">
<?php require_once 'inclus/header.php'; ?>
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
	<p>Nous avons développé durant des années le site en source fermée, et avons décidé durant l'été 2018 de le libérer. Le code source est donc désormais disponible librement <a href="https://gitlab.com/ProgAccess/ProgAccess">sur GitLab</a> sous licence GNU AGPL.</p>
	<p>L'équipe compte deux développeurs aux manières assez différentes voire contradictoires en certains points et le code n'est pas organisé pour être compris facilement (l'essentiel consiste en des ajouts et réparations les uns sur les autres, et au final personne n'y comprend plus rien). La libération du code peut donc avoir peu de sens pour le moment, mais nous travaillons beaucoup à la réorganisation, pour avoir un design plus solide, logique, pratique, léger, sécurisé... enfin bref meilleur.</p>
	
	<h3>Organisation du code</h3>
	<p>Nous utilisons PHP et MySQL. Les fichiers d'index par défaut (lu par le serveur si l'adresse est celle d'un dossier) est accueil.php et accueil.html.</p>
	<p>À la racine se trouvent les pages publiques en PHP ainsi que les fichiers utiles (comme l'icône ou quelques XML). Voici une liste des dossiers et de leur contenu&nbsp;:</p>
	<ul>
		<li><span class="dir">pro/admin</span>&nbsp;: Outils d'administration.</li>
		<li><span class="dir">inclus</span>&nbsp;: Fichiers de ressources et bibliothèques pour tout le site.</li>
		<li><span class="dir">locales</span>&nbsp;: Fichiers de traduction</li>
		<li><span class="dir"></span>image&nbsp;: Images.</li>
		<li><span class="dir"></span>scripts&nbsp;: Scripts JS.</li>
		<li><span class="dir"></span>css&nbsp;: Styles CSS.</li>
		<li><span class="dir"></span>api&nbsp;: Fichiers de l'API JSON.</li>
		<li><span class="dir"></span>cache&nbsp;: Fichiers de cache générés automatiquement.</li>
		<li><span class="dir"></span>tasks&nbsp;: Programmes à exécuter automatiquement périodiquement.</li>
		<li><span class="dir"></span>audio&nbsp;: Fichiers son joués au chargement des pages.</li>
		<li><span class="dir"></span>files&nbsp;: Fichiers téléchargeables (gestion automatique).</li>
		<li><span class="dir"></span>gadgets&nbsp;: Pages et ressources des gadgets.</li>
		<li><span class="dir"></span>r&nbsp;: Sert à fournir une adresse plus courte pour le téléchargement.</li>
	</ul>
	
	<h3>Installation</h3>
	<p>apfr.sql contient les requêtes de création des tables de la base de données. Vous pouvez le supprimer après l'avoir importé. Entrez les identifiants du serveur MySQL dans /inclus/dbconnect.php.</p>
	<p>La configuration du serveur doit interdire l'accès aux dossiers suivants&nbsp;: inclus, cache, tasks, files, locales.</p>
	<p>Le fichier /tasks/document_root.php doit contenir le chemin absolu de la racine du serveur (valeur de $_SERVER['DOCUMENT_ROOT'], constante qui n'existe pas quand le script n'est pas lancé par le serveur.</p>
	<p>Les traductions ne sont pas incluses dans l'archive du code source car elles peuvent être modifiées via le site lui-même et car elles sont sous licence CC BY-SA. Vous pourrez bientôt les récupérer via l'API.</p>
	<p>Le fichier tasks.txt contient la liste des tâches à automatiser (avec cron par exemple).</p>
	<p>L'accès à l'interface d'administration nécessite un compte membre. Créez-en un via le formulaire d'inscription puis dans la table `accounts`, colonne `settings`, mettez au champ JSON "rank" la valeur "a". Votre compte a maintenant les droits d'administration. Pour ajouter d'autres membres à l'équipe d'administration, il vous pouvez maintenant passer par la page d'administration "Gestion des membres".</p>
	<p>Ce site contient beaucoup d'éléments dans la base de données, y compris des morceaux de code. Le contenu de la base de données n'est pas publié dans le code source. Nous allons éventuellement refaire l'interface d'administration pour la rendre plus intuitive pour ceux qui ne la connaissent pas, voire faire un assistant d'installation (il n'est pas encore possible d'installer le site sans toucher manuellement à la bdd). N'hésitez pas à nous contacter en cas de problème.</p>
	
	<h3>Licence</h3>
	<p>ProgAccess V<?php echo $lastosv; ?> is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.<br />
	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.<br />
	See the <a href="https://www.gnu.org/licenses/licenses.html#AGPL">GNU Affero General Public License</a> for more details.</p>
	<p>Vous avez le droit d'utiliser, d'étudier, de partager et de modifier le code. Mais si vous le publiez, vous devez citer les auteurs (l'équipe ProgAccess) et publier le code source sous la même licence ou une autre compatible. Il est également obligatoire de publier le code source modifié si vous l'exécutez publiquement sur un serveur. Évidemment, le simple fait d'ajouter ses identifiants MySQL ou de générer des fichiers de cache via l'interface d'administration ne déclanche pas cette obligation.</p>
	<p>Le texte du site (fichiers dans /locales/) est sous licence CC BY-SA. Plus d'informations concernant la licence des traductions, voir dans l'archive locales.zip disponible ci-dessous.</p>
	
	<h3>Participer</h3>
	<a href="https://gitlab.com/ProgAccess/ProgAccess">Dépôt GitLab de ProgAccess</a>
	<p>Si vous avez le courage de télécharger et de décrypter notre code, nous serions très heureux que vous puissiez nous aider à intégrer de nouvelles fonctionnalités, chasser et réparer les bugs, organiser le code...</p>
	<p>Dès que possible, nous créerons un dépôt Git pour faciliter le développement. Pour le moment, nous avons une zone dev publique mais à source fermée pour des raisons de sécurité (le code de la zone dev n'est donc pas soumis à la licence GNU AGPL). Il y a une todo-list (privée pour le moment) et une liste des changements (publique). Nous faisons des modifications en zone dev puis déplaçons tout simplement les fichiers en prod (appelé aussi "pâdev", par opposition avec le "dev").</p>
	
	<h3>Traductions</h3>
	<ul>
		<?php if(file_exists($_SERVER['DOCUMENT_ROOT'].'/source/locales.zip')) { ?>
		<li>Traductions&nbsp;: <a href="/source/locales.zip">locales.zip</a> (<?php echo strftime(tr($tr0,'fndatetime'), filemtime($_SERVER['DOCUMENT_ROOT'].'/source/locales.zip')); ?>) <?php echo numberlocale(human_filesize(filesize($_SERVER['DOCUMENT_ROOT'].'/source/locales.zip'))).tr($tr0,'byte_letter'); ?></li>
		<?php } ?>
	</ul>
</div>
<?php include 'inclus/footer.php'; ?>
</body>
</html>