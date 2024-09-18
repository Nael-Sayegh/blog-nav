<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/log.php');
require_once('include/consts.php');
$title='Open-source';
$sound_path='/audio/page_sounds/contact.mp3';
$stats_page='open-source'; ?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
	<p>Nous avons développé durant des années le site en source fermée, et avons décidé durant l'été 2018 de le libérer. Le code source est donc désormais disponible librement <a href="<?php echo GIT_URL; ?>">sur GitLab</a> sous licence GNU AGPL.</p>
	<p>L'équipe compte deux développeurs aux manières assez différentes voire contradictoires en certains points et le code n'est pas organisé pour être compris facilement (l'essentiel consiste en des ajouts et réparations les uns sur les autres, et au final personne n'y comprend plus rien). La libération du code peut donc avoir peu de sens pour le moment, mais nous travaillons beaucoup à la réorganisation, pour avoir un design plus solide, logique, pratique, léger, sécurisé... enfin bref meilleur.</p>
	
	<h3>Organisation du code</h3>
	<p>Nous utilisons PHP et MySQL. Les fichiers d'index par défaut (lus par le serveur si l'adresse est celle d'un dossier) sont index.php et index.html.</p>
	<p>À la racine se trouvent les pages publiques en PHP ainsi que les fichiers utiles (comme l'icône ou quelques XML). Voici une liste des dossiers et de leur contenu&nbsp;:</p>
	<ul>
		<li><span class="dir">403</span>&nbsp;: Fichiers utilisés pour la page d'accès interdit.</li>
		<li><span class="dir">a</span>&nbsp;: Permet de fournir une URL plus courte pour les articles.</li>
		<li><span class="dir">admin</span>&nbsp;: Outils d'administration.</li>
		<li><span class="dir">api</span>&nbsp;: Fichiers de l'API JSON.</li>
		<li><span class="dir">audio</span>&nbsp;: Fichiers son joués au chargement des pages.</li>
		<li><span class="dir">c</span>&nbsp;: Permet de fournir une URL plus courte pour les catégories.</li>
		<li><span class="dir">cache</span>&nbsp;: Fichiers de cache générés automatiquement.</li>
		<li><span class="dir">css</span>&nbsp;: Styles CSS.</li>
		<li><span class="dir">files</span>&nbsp;: Fichiers téléchargeables (gestion automatique).</li>
		<li><span class="dir">gadgets</span>&nbsp;: Pages et ressources des gadgets.</li>
		<li><span class="dir">image</span>&nbsp;: Images.</li>
		<li><span class="dir">include</span>&nbsp;: Fichiers de ressources et bibliothèques pour tout le site.</li>
		<li><span class="dir">locales</span>&nbsp;: Fichiers de traduction</li>
		<li><span class="dir">r</span>&nbsp;: Permet de fournir une URL plus courte pour les fichiers téléchargeables.</li>
		<li><span class="dir">res</span>&nbsp;: Outils additionnels.</li>
		<li><span class="dir">scripts</span>&nbsp;: Scripts JavaScript.</li>
		<li><span class="dir">tasks</span>&nbsp;: Programmes à exécuter automatiquement périodiquement.</li>
		<li><span class="dir">u</span>&nbsp;: Permet de fournir une URL plus courte pour les mises à jour du site.</li>
	</ul>
	
	<h3>Installation</h3>
	<ol>
	<li>Créez la base de données&nbsp;: apfr.sql contient les requêtes de création des tables de la base de données. Vous pouvez le supprimer après l'avoir importé.</li>
	<li>Renommez le fichier include/config.php en include/config.local.php et complétez le avec vos informations.</li>
	</ol>
	<p>La configuration du serveur doit interdire l'accès aux dossiers suivants&nbsp;: inclus, cache, tasks, files, locales.</p>
	<p>Les traductions ne sont pas incluses dans l'archive du code source car elles peuvent être modifiées via le site lui-même et car elles sont sous licence CC BY-SA. Vous pourrez bientôt les récupérer via l'API.</p>
	<p>Le fichier tasks.txt contient la liste des tâches à automatiser (avec cron par exemple).</p>
	<p>L'accès à l'interface d'administration nécessite un compte membre. Créez-en un via le formulaire d'inscription puis dans la table `accounts`, colonne `settings`, mettez au champ JSON "rank" la valeur "a". Votre compte a maintenant les droits d'administration. Pour ajouter d'autres membres à l'équipe d'administration, vous pouvez maintenant passer par la page d'administration "Gestion des membres".</p>
	<p>Ce site contient beaucoup d'éléments dans la base de données, y compris des morceaux de code. Le contenu de la base de données n'est pas publié dans le code source. Nous allons éventuellement refaire l'interface d'administration pour la rendre plus intuitive pour ceux qui ne la connaissent pas, voire faire un assistant d'installation (il n'est pas encore possible d'installer le site sans toucher manuellement à la bdd). N'hésitez pas à nous contacter en cas de problème.</p>
	
	<h3>Licence</h3>
	<p>ProgAccess is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.<br>
	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.<br>
	See the <a href="https://www.gnu.org/licenses/licenses.html#AGPL">GNU Affero General Public License</a> for more details.</p>
	<p>Vous avez le droit d'utiliser, d'étudier, de partager et de modifier le code. Mais si vous le publiez, vous devez citer les auteurs (l'équipe ProgAccess) et publier le code source sous la même licence ou une autre compatible. Il est également obligatoire de publier le code source modifié si vous l'exécutez publiquement sur un serveur. Évidemment, le simple fait d'ajouter ses identifiants MySQL ou de générer des fichiers de cache via l'interface d'administration ne déclanche pas cette obligation.</p>
	<p>Le texte du site (fichiers dans /locales/) est sous licence CC BY-SA. Plus d'informations concernant la licence des traductions, voir dans l'archive locales.zip disponible ci-dessous.</p>
	
	<h3>Participer</h3>
	<a href="<?php echo GIT_URL; ?>">Dépôt GitLab de <?php echo $site_name; ?></a>
	<p>Si vous avez le courage de lire et de décrypter notre code, nous serions très heureux que vous puissiez nous aider à intégrer de nouvelles fonctionnalités, chasser et réparer les bugs, organiser le code...</p>
	<p>Pour rapporter un bug ou suggérer une fonctionnalité, vous pouvez <a href="<?php echo GIT_URL; ?>">ouvrir un ticket sur le GitLab</a>, ou utiliser le <a href="/contact_form.php">formulaire de contact</a>.</p>
	
	<h3>Traductions</h3>
	<ul>
		<?php if(file_exists($_SERVER['DOCUMENT_ROOT'].'/source/locales.zip')) { ?>
		<li>Traductions&nbsp;: <a href="/source/locales.zip">locales.zip</a> (<?php echo getFormattedDate(filemtime($_SERVER['DOCUMENT_ROOT'].'/source/locales.zip'), tr($tr0,'fndatetime')); ?>) <?php echo numberlocale(human_filesize(filesize($_SERVER['DOCUMENT_ROOT'].'/source/locales.zip'))).tr($tr0,'byte_letter'); ?></li>
		<?php } ?>
	</ul>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>