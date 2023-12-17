<?php
$noapi = true;
require_once 'api_inc.php';
set_include_path($_SERVER['DOCUMENT_ROOT']);
require 'include/log.php';
require_once 'include/consts.php';
$tr = load_tr($lang, 'api_home');
$title = tr($tr,'title');
$sound_path = '/audio/page_sounds/contact.mp3';
$stats_page = 'contact'; ?>
<!doctype html>
<html lang="fr">
	<?php require_once 'include/header.php'; ?>
	<body>
		<div id="hautpage" role="banner">
			<h1><a href="/" title="<?php echo tr($tr0,'banner_homelink'); ?>"><?php print $site_name; ?></a></h1>
			<?php if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'include/trident.php';
include 'include/searchtool.php';
include 'include/loginbox.php'; ?>
		</div>
		<?php include('include/load_sound.php');
include 'include/menu.php'; ?>
		<div id="container" role="main">
			<h1 id="contenu"><?php print $title; ?></h1>
			
			<p><?php echo tr($tr,'intro_text'); ?></p>
			
			<h2><?php echo tr($tr,'doc_title'); ?></h2>
			
			<h3><?php echo tr($tr,'version_title'); ?></h3>
			<p><?php echo tr($tr,'version_text', array('apiversion'=>$api_version)); ?></p>
			
			<h3>Données</h3>
			<p>Les données renvoyées par le serveur peuvent être choisies en modifiant les paramètres GET. Plusieurs paramètres peuvent être utilisés simultanément. La racine est <em>/api/get.php</em>.</p>
			<ul>
				<li aria-level="2"><strong>Par défaut</strong>&nbsp;:
					<ul>
						<li><em>api_version</em>: Version de l'API</li>
					</ul>
				</li>
				<li aria-level="2"><strong>g</strong>: Informations générales
					<ul>
						<li><em>name</em>: Nom du site</li>
						<li><em>slogan</em>: Slogan du site</li>
						<li><em>lang</em>: Langue actuelle (peut être changée avec le paramètre ?lang)</li>
						<li><em>version_id</em>: ID de la version du site</li>
						<li><em>version_name</em>: Numéro de la version du site</li>
						<li><em>version_time</em>: Date de publication de la version du site</li>
						<li><em>maintenance</em>: Mode maintenance</li>
						<li><em>domain</em>: Domaine courant</li>
						<li><em>visits_year</em>: Nombre de visites sur ce domaine depuis 1 an</li>
						<li><em>visits_day</em>: Nombre de visites sur ce domaine depuis minuit</li>
						<li><em>visitors_week</em>: Nombre de visiteurs sur ce domaine depuis 7 jours</li>
						<li><em>visitors_day</em>: Nombre de visiteurs sur ce domaine depuis minuit</li>
						<li><em>languages</em>: liste des langues [id, code, nom, priorité]</li>
					</ul>
				</li>
				<li aria-level="2"><strong>slides</strong>: Slides
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: lang</li>
						<li><em>[2]</em>: label</li>
						<li><em>[3]</em>: style</li>
						<li><em>[4]</em>: title</li>
						<li><em>[5]</em>: title_style</li>
						<li><em>[6]</em>: content</li>
						<li><em>[7]</em>: content_style</li>
						<li><em>[8]</em>: date</li>
					</ul>
				</li>
				<li aria-level="2"><strong>c</strong>: Catégories
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: name</li>
						<li><em>[2]</em>: text</li>
					</ul>
				</li>
				<li aria-level="2"><strong>a</strong>(=id)?: Articles
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: name</li>
						<li><em>[2]</em>: category</li>
						<li><em>[3]</em>: date</li>
						<li><em>[4]</em>: hits</li>
						<li><em>[5]</em>: downloads</li>
						<li><em>[6]</em>: author</li>
						<li><em>[7]</em>: archive_after</li>
					</ul>
				</li>
				<li aria-level="2"><strong>ca(=id)</strong>: Articles de la catégorie {id}
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: name</li>
						<li><em>[2]</em>: date</li>
						<li><em>[3]</em>: hits</li>
						<li><em>[4]</em>: downloads</li>
						<li><em>[5]</em>: author</li>
						<li><em>[6]</em>: archive_after</li>
					</ul>
				</li>
				<li aria-level="2"><strong>at</strong>(=id)?: Contenus d'articles (sans le contenu)
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: lang</li>
						<li><em>[2]</em>: article_id</li>
						<li><em>[3]</em>: name</li>
						<li><em>[4]</em>: date</li>
						<li><em>[5]</em>: keywords</li>
						<li><em>[6]</em>: description</li>
						<li><em>[7]</em>: website</li>
						<li><em>[8]</em>: author</li>
					</ul>
				</li>
				<li aria-level="2"><strong>cat(=id)</strong>: Contenus d'articles de la catégorie {id} (sans le contenu)
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: lang</li>
						<li><em>[2]</em>: article_id</li>
						<li><em>[3]</em>: name</li>
						<li><em>[4]</em>: date</li>
						<li><em>[5]</em>: keywords</li>
						<li><em>[6]</em>: description</li>
						<li><em>[7]</em>: website</li>
						<li><em>[8]</em>: author</li>
					</ul>
				</li>
				<li aria-level="2"><strong>att</strong>(=id)?: Contenus d'articles (avec le contenu)
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: lang</li>
						<li><em>[2]</em>: article_id</li>
						<li><em>[3]</em>: name</li>
						<li><em>[4]</em>: date</li>
						<li><em>[5]</em>: keywords</li>
						<li><em>[6]</em>: description</li>
						<li><em>[7]</em>: website</li>
						<li><em>[8]</em>: author</li>
						<li><em>[9]</em>: text</li>
					</ul>
				</li>
				<li aria-level="2"><strong>catt(=id)</strong>: Contenus d'articles de la catégorie {id} (avec le contenu)
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: lang</li>
						<li><em>[2]</em>: article_id</li>
						<li><em>[3]</em>: name</li>
						<li><em>[4]</em>: date</li>
						<li><em>[5]</em>: keywords</li>
						<li><em>[6]</em>: description</li>
						<li><em>[7]</em>: website</li>
						<li><em>[8]</em>: author</li>
						<li><em>[9]</em>: text</li>
					</ul>
				</li>
				<li aria-level="2"><strong>su</strong>: Mises à jour du site
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: name</li>
						<li><em>[2]</em>: text</li>
						<li><em>[3]</em>: date</li>
						<li><em>[4]</em>: authors</li>
						<li><em>[5]</em>: codestat</li>
					</ul>
				</li>
				<li aria-level="2"><strong>af</strong>(=id)?: Fichiers d'articles
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: article_id</li>
						<li><em>[2]</em>: name</li>
						<li><em>[3]</em>: filetype</li>
						<li><em>[4]</em>: title</li>
						<li><em>[5]</em>: date</li>
						<li><em>[6]</em>: filesize</li>
						<li><em>[7]</em>: hits</li>
						<li><em>[8]</em>: label</li>
						<li><em>[9]</em>: md5</li>
						<li><em>[10]</em>: sha1</li>
						<li><em>[11]</em>: arch</li>
						<li><em>[12]</em>: platform</li>
					</ul>
				</li>
				<li aria-level="2"><strong>aaf(=id)</strong>: Fichiers de l'article {id}
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: name</li>
						<li><em>[2]</em>: filetype</li>
						<li><em>[3]</em>: title</li>
						<li><em>[4]</em>: date</li>
						<li><em>[5]</em>: filesize</li>
						<li><em>[6]</em>: hits</li>
						<li><em>[7]</em>: label</li>
						<li><em>[8]</em>: md5</li>
						<li><em>[9]</em>: sha1</li>
						<li><em>[10]</em>: arch</li>
						<li><em>[11]</em>: platform</li>
					</ul>
				</li>
				<li aria-level="2"><strong>afl</strong>(=label)?: Fichiers d'articles (par label)
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: article_id</li>
						<li><em>[2]</em>: name</li>
						<li><em>[3]</em>: filetype</li>
						<li><em>[4]</em>: title</li>
						<li><em>[5]</em>: date</li>
						<li><em>[6]</em>: filesize</li>
						<li><em>[7]</em>: hits</li>
						<li><em>[8]</em>: label</li>
						<li><em>[9]</em>: md5</li>
						<li><em>[10]</em>: sha1</li>
						<li><em>[11]</em>: arch</li>
						<li><em>[12]</em>: platform</li>
					</ul>
				</li>
				<li aria-level="2"><strong>am</strong>(=id)?: Miroirs d'articles
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: article_id</li>
						<li><em>[2]</em>: links</li>
						<li><em>[3]</em>: title</li>
						<li><em>[4]</em>: date</li>
						<li><em>[5]</em>: hits</li>
						<li><em>[6]</em>: label</li>
					</ul>
				</li>
				<li aria-level="2"><strong>aam(=id)</strong>: Miroirs de l'article {id}
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: links</li>
						<li><em>[2]</em>: title</li>
						<li><em>[3]</em>: date</li>
						<li><em>[4]</em>: hits</li>
						<li><em>[5]</em>: label</li>
					</ul>
				</li>
				<li aria-level="2"><strong>aml</strong>(=label)?: Miroirs d'articles (par label)
					<ul>
						<li><em>[0]</em>: id</li>
						<li><em>[1]</em>: article_id</li>
						<li><em>[2]</em>: links</li>
						<li><em>[3]</em>: title</li>
						<li><em>[4]</em>: date</li>
						<li><em>[5]</em>: hits</li>
						<li><em>[6]</em>: label</li>
					</ul>
				</li>
				<p>Exemples&nbsp;:</p>
				<ul>
					<li><a href="https://www.progaccess.net/api/get.php?g&a&att">https://www.progaccess.net/api/get.php?g&amp;a&amp;att</a> affichera les infos générales du site, ainsi que la liste des articles, leurs métadonnées et leurs contenus dans toutes les langues.</li>
					<li><a href="https://www.progaccess.net/api/get.php?a=42">https://www.progaccess.net/api/get.php?a=42</a> affichera l'article 42</li>
				</ul>
				<p>Note&nbsp;: Les fichiers et miroirs d'articles ont tous un ID unique, mais également un label (optionnel). Lors d'une mise à jour d'un fichier/miroir, l'ID change mais le label peut rester le même. Par exemple, "Firefox ESR pour Windows 64 bits" aura toujours le même label <em>firefoxesr64</em>.</p>
			</ul>
			
		</div>
		<?php include 'include/footer.php'; ?>
	</body>
</html>
