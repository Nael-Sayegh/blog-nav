<?php
$logonly = true;
$adminonly = true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';

$categories = array();
$req = $bdd->query('SELECT * FROM `softwares_categories` ORDER BY `name` ASC');
while($data = $req->fetch()) {$categories[$data['id']] = $data['name'];}

/*if(isset($_GET['form']) and isset($_POST['name']) and isset($_POST['category']) and isset($_POST['keywords']) and isset($_POST['description']) and isset($_POST['website']) and isset($_POST['text'])) {
	$time = time();
	$req = $bdd->prepare('INSERT INTO softwares(name,category,text,date,description,keywords,website,author) VALUES(?,?,?,?,?,?,?,?)');
	$req->execute(array(htmlspecialchars($_POST['name']), $_POST['category'], $_POST['text'], $time, $_POST['description'], $_POST['keywords'], $_POST['website'], htmlspecialchars($nom)));
	$req = $bdd->prepare('SELECT id FROM softwares WHERE name=? AND category=? AND date=? AND description=? AND keywords=?');
	$req->execute(array(htmlspecialchars($_POST['name']), $_POST['category'], $time, $_POST['description'], $_POST['keywords']));
	if($data = $req->fetch()) {
		header('Location: sw_mod.php?addfile='.$data['id']);
if(isset($_POST['publier'])) {
$messagesocial = 'Nouvel article : '.$_POST['name'].' (A'.$data['id'].').'."\n\n".'https://www.progaccess.net/article.php?id='.$data['id']."\n\n".$nom.', Administration';
			include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/facebook/envoyer.php');
			send_facebook($messagesocial);
			include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/twitter/twitter.php');
			send_twitter($messagesocial);
		}
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
	}
	$req->closeCursor();
}*/
if(isset($_GET['form']) and isset($_POST['sname']) and isset($_POST['category'])) {
	$sname = '';
	if(strlen($_POST['sname']) < 256 and !empty($_POST['sname']))
		$sname = $_POST['sname'];
	else $log .= '<li>Le nom interne de l\'article doit comporter entre 1 et 255 caractères.</li>';
	
	$category = '';
	if(isset($categories[$_POST['category']]))
		$category = $_POST['category'];
	else $log .= '<li>La catégorie choisie n\'existe pas.</li>';
	
	$f_lang = '';
	if(isset($_POST['lang']) and !empty($_POST['lang'])) {
		if(in_array($_POST['lang'], $langs_prio))
			$f_lang = $_POST['lang'];
		else $log .= '<li>La langue choisie n\'est pas répertoriée.</li>';
		
		$name = '';
		if(strlen($_POST['name']) < 256 and !empty($_POST['name']))
			$name = $_POST['name'];
		else $log .= '<li>Le nom traduit de l\'article doit comporter entre 1 et 255 caractères.</li>';
		
		$keywords = '';
		if(strlen($_POST['keywords']) < 511 and !empty($_POST['keywords']))
			$keywords = $_POST['keywords'];
		else $log .= '<li>Les mots-clef doivent comporter entre 1 et 511 caractères.</li>';
		
		$description = '';
		if(strlen($_POST['description']) < 511 and !empty($_POST['description']))
			$description = $_POST['description'];
		else $log .= '<li>La description courte doit comporter entre 1 et 511 caractères.</li>';
		
		$website = '';
		if(strlen($_POST['website']) < 256)
			$website = $_POST['website'];
		else $log .= '<li>L\'adresse du site officiel doit comporter moins de 256 caractères.</li>';
		
		$text = '';
		if(strlen($_POST['text']) <= 20000 and !empty($_POST['text']))
			$text = $_POST['text'];
		else $log .= '<li>Le texte de l\'article doit comporter entre 1 et 20&#8239;000 caractères.</li>';
		
		$social = isset($_POST['social']) and $_POST['social'] == 'on';
		$published = intval(isset($_POST['published']) and $_POST['published'] == 'on');
	}
	
	if(empty($log)) {
		$req = $bdd->prepare('INSERT INTO `softwares` (`name`, `category`, `date`, `author`) VALUES (?,?,?,?)');
		$req->execute(array($sname, $category, time(), $nom));
		$lastid = $bdd->lastInsertId();
		
		if(!empty($f_lang)) {
			$req = $bdd->prepare('INSERT INTO `softwares_tr` (`sw_id`, `lang`, `date`, `name`, `text`, `keywords`, `description`, `website`, `author`, `published`, `todo_level`) VALUES (?,?,?,?,?,?,?,?,?,?,0)');
			$req->execute(array($lastid, $f_lang, time(), $name, $text, $keywords, $description, $website, $nom, $published));
		
			if($social) {
				$somsg = 'Nouvel article : '.$name.' (A'.$lastid.').'."\n".'https://www.progaccess.net/article.php?id='.$lastid."\n".$nom;
				include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/facebook/envoyer.php');
				send_facebook($somsg);
				include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/twitter/twitter.php');
				send_twitter($somsg);
			}
			include($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
			include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
		}
		
		header('Location: sw_mod.php?listfiles='.$lastid);
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<title>Ajout d'un logiciel sur <?php print $nomdusite; ?></title>
		<?php print $cssadmin; ?>
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
		<h1>Ajout logiciel &#8211; <a href="/"><?php print $nomdusite.' '.$versionnom; ?></a></h1>
				<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php';
if(!empty($log)) echo '<ul>'.$log.'</ul>';
		?>
		<form action="?form" method="post">
			<fieldset><legend>Structure</legend>
				<label for="f_sname">Nom (en interne)&nbsp;:</label>
				<input type="text" name="sname" id="f_sname"<?php if(isset($sname))echo ' value="'.htmlentities($sname).'"'; ?> maxlength="255" required /><br />
				<label for="f_category">Catégorie&nbsp;:</label>
				<select name="category" id="f_category"><?php foreach($categories as $cid => $cname) {echo '<option value="'.$cid.'"'.((isset($category) and $category==$cid) ? ' selected':'').'>'.$cname.'</option>';} ?></select>
			</fieldset>
			<fieldset><legend>Données de référence</legend>
				<label for="f_lang">Langue&nbsp;:</label>
				<select id="f_lang" name="lang" autocomplete="off"><option value=""<?php if(isset($f_lang) and $f_lang=='')echo ' selected'; ?>>Ne pas créer de traduction initiale</option><?php echo langs_html_opts(isset($f_lang)?$f_lang:$lang); ?></select><br />
				<label for="f_name">Nom&nbsp;:</label>
				<input type="text" name="name" id="f_name"<?php if(isset($name))echo ' value="'.htmlentities($name).'"'; ?> maxlength="255" /><br />
				<label for="f_keywords">Mots clés&nbsp;:</label>
				<input type="text" name="keywords" id="f_keywords"<?php if(isset($keywords))echo ' value="'.htmlentities($keywords).'"'; ?> maxlength="511" /><br />
				<label for="f_description">Description courte&nbsp;:</label>
				<input type="text" name="description" id="f_description"<?php if(isset($description))echo ' value="'.htmlentities($description).'"'; ?> maxlength="511" /><br />
				<label for="f_website">Adresse du site officiel (facultatif)&nbsp;:</label>
				<input type="url" name="website" id="f_website"<?php if(isset($website))echo ' value="'.htmlentities($website).'"'; ?> maxlength="255" /><br />
				<label for="f_text">Texte long (HTML)&nbsp;:</label><br />
				<textarea name="text" id="f_text" maxlength="20000" style="width:100%;height:10em;"><?php if(isset($text))echo htmlentities($text); ?></textarea><br />
				<p>Il est possible de modifier ces informations et de rajouter des liens et fichiers ultérieurement.</p>
				<label for="f_so">Annoncer l'ajout sur les réseaux sociaux&nbsp;:</label>
				<input type="checkbox" id="f_so" name="social"<?php if((isset($social) and $social) or !isset($social))echo ' checked'; ?> /><br />
				<label for="f_published">Publier&nbsp;:</label>
				<input type="checkbox" id="f_published" name="published"<?php if((isset($published) and $published) or !isset($published))echo ' checked'; ?> />
			</fieldset>
			<input type="submit" value="Ajouter" />
		</form>
	</body>
</html>