<?php $logonly = true;
$adminonly=true;
$justpa = true;
$titlePAdm='Modification d\'un article';
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/log.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php');
$time = time();
$addfile_hash = '';
$addfile_path = '';
// listing categories
$req = $bdd->query('SELECT * FROM `softwares_categories`');
$cat = array();
while($data = $req->fetch()) {$cat[$data['id']] = $data['name'];}

$sw_mode = null;
$sw_id = null;
if(isset($_GET['id'])) {$sw_id = $_GET['id'];$sw_mode = 1;}
elseif(isset($_GET['listfiles'])) {$sw_id = $_GET['listfiles'];$sw_mode = 2;}
elseif(isset($_GET['addfile'])) {$sw_id = $_GET['addfile'];$sw_mode = 3;}

if((isset($_GET['token']) and $_GET['token'] == $login['token']) or (isset($_POST['token']) and $_POST['token'] == $login['token'])) {
	if(isset($_GET['mod']) and isset($_POST['name']) and isset($_POST['category'])) {
		$mod_keywords = isset($_POST['keywords']) ? $_POST['keywords'] : '';
		$mod_description = isset($_POST['description']) ? $_POST['description'] : '';
		$mod_text = isset($_POST['text']) ? $_POST['text'] : '';
		$mod_website = isset($_POST['website']) ? $_POST['website'] : '';
		
		$req = $bdd->prepare('UPDATE softwares SET name=?, category=?, date=?, description=?, text=?, keywords=?, website=?, author=? WHERE id=?');
		$req->execute(array($_POST['name'], $_POST['category'], $time, $mod_description, $mod_text, $mod_keywords, $mod_website, $nom, $_GET['mod']));
		header('Location: sw_mod.php?list='.$_POST['category']);
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
		exit();
	}
	if(isset($_GET['rsw'])) {
		$req = $bdd->prepare('DELETE FROM `softwares` WHERE `id`=? LIMIT 1');
		$req->execute(array($_GET['rsw']));
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
	}
	if(isset($_GET['modf2'])) {
		$req = $bdd->prepare('SELECT * FROM `softwares_files` WHERE `id`=? LIMIT 1');
		$req->execute(array($_GET['modf2']));
		if($data = $req->fetch()) {
			
			$nofile = true;
			if(isset($_POST['method']) and !empty($_POST['method'])) {
				$ok = false;
				$file = $_SERVER['DOCUMENT_ROOT'].'/files/'.$data['hash'];
				$filename = null;
				$filesize = null;
				$filetype = null;
				switch($_POST['method']) {
					case 'form':
						if(isset($_FILES['file']) and $_FILES['file'] > 0 and $_FILES['file']['size'] <= 2147483648 and !empty($_FILES['file']['name'])) {
							unlink($file);
							move_uploaded_file($_FILES['file']['tmp_name'], $file);
							$filename = (isset($_POST['overwrite_name']) and $_POST['overwrite_name'] == 'on') ? $_FILES['file']['name'] : $_POST['name'];
							$filesize = $_FILES['file']['size'];
							$filetype = $_FILES['file']['type'];
							$ok = true;
							$nofile = false;
						} else if(isset($_POST['name']) and !empty($_POST['name'])) {
							$ok = true;
							$nofile = true;
						}
					break;
					case 'url':
						if(isset($_POST['url']) and !empty($_POST['url']) and isset($_POST['name']) and !empty($_POST['name'])) {
							$stream = fopen($_POST['url'], 'r');
							file_put_contents($file, $stream);
							fclose($stream);
							$filename = $_POST['name'];
							$filesize = filesize($file);
							$filetype = mime_content_type($file);
							if($filetype === false)
								$filetype = 'application/octet-stream';
							$ok = true;
							$nofile = false;
						}
					break;
				}
				if(!$nofile) {
					if($ok) {
						$req = $bdd->prepare('UPDATE `softwares_files` SET `name`=?, `filetype`=?, `title`=?, `date`=?, `filesize`=?, `label`=?, `md5`=?, `sha1`=? WHERE `id`=? LIMIT 1');
						$req->execute(array($filename, $filetype, $_POST['title'], time(), $filesize, $_POST['label'], md5_file($file), sha1_file($file), $_GET['modf2']));
					} else
						die('erreur');
				}
			}
			if($nofile) {
				$req = $bdd->prepare('UPDATE `softwares_files` SET `name`=? , `title`=? , `label`=?, `date`=? WHERE `id`=? LIMIT 1');
				$req->execute(array($_POST['name'], $_POST['title'], $_POST['label'], time(), $_GET['modf2']));
			}
			
			header('Location: sw_mod.php?listfiles='.$data['sw_id']);
			$req = $bdd->prepare('UPDATE `softwares` SET `date`=?, `author`=? WHERE `id`=? LIMIT 1');
			$req->execute(array(time(), $nom, $data['sw_id']));
			include($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
			include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
			
			if(isset($_POST['social']) and $_POST['social'] == 'on') {
				$reqf=$bdd->prepare('SELECT * FROM `softwares_files` ORDER BY `date` DESC LIMIT 1');
				$reqf->execute();
				if($data=$reqf->fetch()) {
				$somsg = $_POST['title'].' : https://www.progaccess.net/r?'.(!empty($_POST['label']) ? ('p='.$_POST['label']):('id='.$data['id'])).' https://www.progaccess.net/a?id='.$data['sw_id'].' '.$nom;
				include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/facebook/envoyer.php');
				send_facebook($somsg);
				include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/Mastodon/Post.php');
				send_mastodon($somsg);
				include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/twitter/twitter.php');
				send_twitter($somsg);
				}
			}
			exit();
		}
	}
	if(isset($_GET['modm2'])) {
		$req = $bdd->prepare('UPDATE `softwares_mirrors` SET `title`=? , `links`=? , `label`=?, `date`=? WHERE `id`=? LIMIT 1');
		$req->execute(array($_POST['title'], $_POST['urls'], $_POST['label'], time(), $_GET['modm2']));
		header('Location: sw_mod.php?listfiles='.$_GET['modm2']);
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
		exit();
	}
	if(isset($_GET['vfile'])) {
		$req1 = $bdd->prepare('SELECT `id`, `sw_id`, `title`, `label` FROM `softwares_files` WHERE `hash`=? LIMIT 1');
		$req1->execute(array($_GET['vfile']));
		if($data = $req1->fetch()) {
			$file = $_SERVER['DOCUMENT_ROOT'].'/files/'.$_GET['vfile'];
			if(file_exists($file)) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$req2 = $bdd->prepare('UPDATE `softwares_files` SET `filetype`=?, `date`=?, `filesize`=?, `md5`=?, `sha1`=? WHERE `id`=? LIMIT 1');
				$req2->execute(array(finfo_file($finfo,$file), time(), filesize($file), md5_file($file), sha1_file($file), $data['id']));
				finfo_close($finfo);
				if(isset($_GET['social']) and $_GET['social'] == 'on') {
					$somsg = $data['title'].' : https://www.progaccess.net/r?'.(!empty($data['label']) ? ('p='.$data['label']):('id='.$data['id'])).' https://www.progaccess.net/a?id='.$data['sw_id'].' '.$nom;
					include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/facebook/envoyer.php');
					send_facebook($somsg);
					include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/Mastodon/Post.php');
					send_mastodon($somsg);
					include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/twitter/twitter.php');
					send_twitter($somsg);
				}
				include($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
				include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
				header('Location: sw_mod.php?listfiles='.$data['sw_id']);
				exit();
			}
			$addfile_hash = $_GET['vfile'];
			$addfile_path = $file;
		}
		else {
			header('Location: sw_mod.php');
			exit();
		}
	}
	if(isset($_GET['addmirror']) and isset($_POST['title']) and isset($_POST['urls'])) {
		$req1 = $bdd->prepare('SELECT `id` FROM `softwares` WHERE `id`=? LIMIT 1');
		$req1->execute(array($_GET['addmirror']));
		if($req1->fetch()) {
			if(isset($_POST['label']) and !empty($_POST['label'])) {
				$label = htmlspecialchars($_POST['label']);
				$req = $bdd->prepare('UPDATE `softwares_mirrors` SET `label`="" WHERE `label`=? LIMIT 1');
				$req->execute(array($label));
			}
			else
				$label = '';
			$req2 = $bdd->prepare('INSERT INTO `softwares_mirrors` (`sw_id`,`links`,`title`,`date`,`label`) VALUES (?,?,?,?,?)');
			$req2->execute(array($_GET['addmirror'], $_POST['urls'], $_POST['title'], time(), $label));
		}
		$req = $bdd->prepare('SELECT * FROM softwares_mirrors WHERE id=?');
		$req->execute(array($_GET['addmirror']));
		if($data = $req->fetch())
			header('Location: sw_mod.php?listfiles='.$data['sw_id']);
	}
	if(isset($_GET['upload']) and isset($_POST['title']) and isset($_POST['method'])) {
		$ok = false;
		$complete = false;
		$hash = null;
		$file = null;
		$filename = null;
		$filesize = null;
		$filetype = null;
		
		$req1 = $bdd->prepare('SELECT `id` FROM `softwares` WHERE `id`=? LIMIT 1');
		$req1->execute(array($_GET['upload']));
		if($article = $req1->fetch()) {
			$hash = base_convert(sha1($_POST['name'].time()), 16, 36);
			$file = $_SERVER['DOCUMENT_ROOT'].'/files/'.$hash;
			
			switch($_POST['method']) {
				case 'form':
					if(!file_exists($file)) {
						if(isset($_FILES['file']) and $_FILES['file'] > 0 and $_FILES['file']['size'] <= 2147483648) {
							move_uploaded_file($_FILES['file']['tmp_name'], $file);
							$filename = (isset($_POST['name']) and !empty($_POST['name'])) ? $_POST['name'] : $_FILES['file']['name'];
							$filesize = $_FILES['file']['size'];
							$filetype = $_FILES['file']['type'];
							$ok = true;
							$complete = true;
						}
					}
				break;
				case 'ext':
					$addfile_hash = $hash;
					$addfile_path = $file;
					$addfile_so = isset($_POST['social']) and $_POST['social'] == 'on';
					$ok = true;
				break;
				case 'url':
					if(isset($_POST['name']) and !empty($_POST['name']) and isset($_POST['url']) and !empty($_POST['url'])) {
						$stream = fopen($_POST['url'], 'r');
						file_put_contents($file, $stream);
						fclose($stream);
						$filename = $_POST['name'];
						$filesize = filesize($file);
						$filetype = mime_content_type($file);
						if($filetype === false)
							$filetype = 'application/octet-stream';
						$ok = true;
						$complete = true;
					}
				break;
			}
			
			if($ok) {
				$label = '';
				if(isset($_POST['label']) and !empty($_POST['label'])) {
					$label = htmlspecialchars($_POST['label']);
					$req = $bdd->prepare('UPDATE `softwares_files` SET `label`="" WHERE `label`=? LIMIT 1');
					$req->execute(array($label));
				}
				
				$req = $bdd->prepare('UPDATE `softwares` SET `date`=?, `author`=? WHERE `id`=? LIMIT 1');
				$req->execute(array(time(), $nom, $_GET['upload']));
				
				if($complete) {
					$req = $bdd->prepare('INSERT INTO softwares_files(sw_id,name,hash,filetype,title,date,filesize,label,`md5`,`sha1`) VALUES(?,?,?,?,?,?,?,?,?,?)');
					$req->execute(array($_GET['upload'], $filename, $hash, $filetype, $_POST['title'], time(), $filesize, $label, md5_file($file), sha1_file($file)));
					include($_SERVER['DOCUMENT_ROOT'].'/tasks/journal_cache.php');
					include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
					
					if(isset($_POST['social']) and $_POST['social'] == 'on') {
						$somsg = $_POST['title'].' :';
						if(!empty($label))
							$somsg .= ' https://www.progaccess.net/r?p='.$label;
						$somsg .= ' https://www.progaccess.net/a?id='.$_GET['upload'].' '.$nom;
						include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/facebook/envoyer.php');
						send_facebook($somsg);
						include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/Mastodon/Post.php');
						send_mastodon($somsg);
						include_once($_SERVER['DOCUMENT_ROOT'].'/inclus/lib/twitter/twitter.php');
						send_twitter($somsg);
					}
					
					header('Location: sw_mod.php?listfiles='.$_GET['upload']);
					exit();
				} else {
					$req = $bdd->prepare('INSERT INTO softwares_files(sw_id,name,hash,title,label) VALUES(?,?,?,?,?)');
					$req->execute(array($_GET['upload'], $_POST['name'], $hash, $_POST['title'], $label));
				}
			}
		}
	}
	
	if(isset($_GET['rfiles'])) {
		$req1 = $bdd->prepare('SELECT id, hash FROM softwares_files WHERE sw_id=?');
		$req1->execute(array($_GET['rfiles']));
		while($data = $req1->fetch()) {
			if(isset($_GET['rfile'.$data['id']])) {
				$req2 = $bdd->prepare('DELETE FROM softwares_files WHERE id=? LIMIT 1');
				$req2->execute(array($data['id']));
				header('Location: sw_mod.php?addfile='.$_GET['rfiles']);
				unlink($_SERVER['DOCUMENT_ROOT'].'/files/'.$data['hash']);
			}
		}
		$req1 = $bdd->prepare('SELECT `id` FROM `softwares_mirrors` WHERE `sw_id`=?');
		$req1->execute(array($_GET['rfiles']));
		while($data = $req1->fetch()) {
			if(isset($_GET['rmir'.$data['id']])) {
				$req2 = $bdd->prepare('DELETE FROM `softwares_mirrors` WHERE `id`=? LIMIT 1');
				$req2->execute(array($data['id']));
				header('Location: sw_mod.php?addfile='.$_GET['rfiles']);
			}
		}
		$req1->closeCursor();
		include($_SERVER['DOCUMENT_ROOT'].'/tasks/slider_cache.php');
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title><?php
if($sw_id != null and $sw_mode != null) {
	$req = $bdd->prepare('SELECT `name` FROM `softwares` WHERE `id`=? LIMIT 1');
	$req->execute(array($sw_id));
	if($sw_mode == 1) { echo 'Modifier '; $titlePAdm='Modifier '; }
	if($sw_mode == 2) { echo 'Fichiers de '; $titlePAdm='Fichiers de '; }
	if($sw_mode == 3) { echo 'Nouveau fichier à '; $titlePAdm='Nouveau fichier à '; }
	if($data = $req->fetch()) {
		echo $data['name'];
		$titlePAdm.=$data['name'];
	}
	else {
		echo 'un article';
		$titlePAdm.='un article';
	}
}
else
	echo 'Modifier un article';
?> &#8211; admin <?php print $nomdusite; ?></title>
<?php print $cssadmin; ?>
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
<?php require_once('inclus/banner.php');
if(empty($_GET)) {
	echo '<ul title="Lister les articles de&nbsp;:">';
	$req2 = $bdd->query('SELECT * FROM softwares_categories ORDER BY name ASC');
	while($data2 = $req2->fetch()) {
		echo '<li><a href="sw_mod.php?list='.$data2['id'].'">'.$data2['name'].'</a></li>';
	}
	$req2->closeCursor();
	echo '</ul>';
} else {
	echo '<a href="sw_mod.php">Retourner à la liste des catégories</a>';
}
if($addfile_hash != '' and $addfile_path != '') {
	echo '<p>L\'ajout du fichier n\'est pas terminé.<br>Veuillez envoyer le fichier à cet emplacement&nbsp;:<br><strong>'.$addfile_path.'</strong><br>Son nom doit être <br><em>'.$addfile_hash.'</em><br> sans extension.<br>Une fois ceci fait, suivez ce lien&nbsp;:<br><a href="?vfile='.$addfile_hash.(($addfile_so==true)?'&social=on':'').'">Vérifier le fichier</a></p>';
}

if(isset($_GET['list'])) { ?><table border="1">
			<thead><tr><th>Nom</th><th>Catégorie</th><th>Dernière modification</th><th>Actions</th></tr></thead>
			<tbody>
<?php
// listing softwares
if(empty($_GET['list']))
	$req = $bdd->query('SELECT * FROM softwares ORDER BY name ASC');
else {
	$req = $bdd->prepare('SELECT * FROM softwares WHERE category=? ORDER BY name ASC');
	$req->execute(array($_GET['list']));
}
while($data = $req->fetch()) {
	echo '<tr>
		<td><a href="?id='.$data['id'].'" role="heading" aria-level="6">'.$data['name'].'</a></td>
		<td>'.$cat[$data['category']].'</td>
		<td>'.date('d/m/Y H:i',$data['date']).' par '.$data['author'].'</td>
		<td><a href="?listfiles='.$data['id'].'">Afficher les fichiers</a> | <a href="translate.php?type=article&id='.$data['id'].'">Traductions</a>'.(($nom == $data['author']) ? ' | <a href="?rsw='.$data['id'].'">supprimer</a>':'').'</td>
	</tr>';
}
?>
			</tbody>
		</table><?php }
if(isset($_GET['listfiles'])) {
	$req1 = $bdd->prepare('SELECT id,name,category,website FROM softwares WHERE id=? ORDER BY date ASC');
	$req1->execute(array($_GET['listfiles']));
	if($data1 = $req1->fetch()) { ?>
		<p>Liste des fichiers de&nbsp;: <a href="?id=<?php echo $data1['id']; ?>"><?php echo $data1['name']; ?></a></p>
		<ul><li><a href="?addfile=<?php echo $data1['id']; ?>">Ajouter un fichier<?php if(DEV)echo ' (Zone dev&nbsp;: lien à l\'usage des développeurs, pour le test uniquement)'; ?></a></li><li><a href="?list=<?php echo $data1['category']; ?>"><?php echo $cat[$data1['category']]; ?></a></li><li><a href="translate.php?type=article&id=<?php echo $data1['id']; ?>">Traductions</a></li><?php if($data1['website'] != '') echo '<li><a target="_blank" rel="noopener" href="'.$data1['website'].'">Site officiel</a></li>'; ?></ul>
		<form action="#" method="get">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<input type="hidden" name="rfiles" value="<?php echo $data1['id']; ?>">
			<table border="1">
				<thead><tr><th>Nom</th><th>Titre</th><th>Label</th><th>Type</th><th>Modifié le</th><th>Taille</th><th>Supprimer</th></tr></thead>
				<tbody>
	<?php	$req2 = $bdd->prepare('SELECT * FROM softwares_files WHERE sw_id=? ORDER BY date ASC');
			$req2->execute(array($_GET['listfiles']));
			while($data2 = $req2->fetch()) {
				echo '<tr><td><a href="?modf='.$data2['id'].'">'.$data2['name'].'</a></td><td>'.$data2['title'].'</td><td><a href="/r.php?p='.$data2['label'].'">'.$data2['label'].'</a></td><td>'.$data2['filetype'].'</td><td>'.date('d/m/Y H:i',$data2['date']).'</td><td>'.human_filesize($data2['filesize']).'o</td><td><input type="checkbox" name="rfile'.$data2['id'].'" autocomplete="off"></td></tr>';
			} $req2->closeCursor(); ?></tbody>
			</table>
			<table border="1">
				<thead><tr><th>Titre</th><th>Adresses</th><th>Label</th><th>Modifié le</th><th>Supprimer</th></tr></thead>
				<tbody>
	<?php	$req2 = $bdd->prepare('SELECT * FROM `softwares_mirrors` WHERE `sw_id`=? ORDER BY `date` ASC');
			$req2->execute(array($_GET['listfiles']));
			while($data2 = $req2->fetch()) {
				echo '<tr><td><a href="?modm='.$data2['id'].'">'.$data2['title'].'</a></td><td><textarea name="lmir'.$data2['id'].'" readonly>'.htmlentities($data2['links']).'</textarea></td><td><a href="/r.php?m&p='.$data2['label'].'">'.$data2['label'].'</a></td><td>'.date('d/m/Y H:i',$data2['date']).'</td><td><input type="checkbox" name="rmir'.$data2['id'].'" autocomplete="off"></td></tr>';
			} $req2->closeCursor(); ?></tbody>
			</table>
			<input type="submit" value="Supprimer">
		</form>
		<?php }
	$req1->closeCursor();
}

if(isset($_GET['id'])) {
	$req = $bdd->prepare('SELECT * FROM softwares WHERE id=?');
	$req->execute(array($_GET['id']));
	if($data = $req->fetch()) {
?>
		<a href="?list=<?php echo $data['category']; ?>"><?php echo $cat[$data['category']]; ?></a><br>
		<a href="?listfiles=<?php echo $data['id']; ?>">Lister les fichiers</a><br>
		<a href="translate.php?type=article&id=<?php echo $data['id']; ?>">Traductions</a><br><br>
		<form action="?mod=<?php echo $_GET['id']; ?>" method="post">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<label for="f_mod_name">Nom&nbsp;:</label><input type="text" name="name" value="<?php echo $data['name']; ?>" id="f_mod_name" maxlength="255" required><br>
			<label for="f_mod_category">Catégorie&nbsp;:</label><select name="category" id="f_mod_category"><?php
$rq2 = $bdd->query('SELECT * FROM softwares_categories ORDER BY name ASC');
while($dat2 = $rq2->fetch()) {
	echo '<option value="'.$dat2['id'].'"';
	if($dat2['id'] == $data['category']) echo ' selected';
	echo '>'.$dat2['name'].'</option>';
}
$rq2->closeCursor()
?></select><br>
			<label for="f_mod_keywords">Mots clés&nbsp;:</label><input type="text" name="keywords" value="<?php echo $data['keywords']; ?>" id="f_mod_keywords" maxlength="255"><br>
			<label for="f_mod_description">Description courte&nbsp;:</label><input type="text" name="description" value="<?php echo $data['description']; ?>" id="f_mod_description" maxlength="1024"><br>
			<label for="f_website">Adresse du site officiel (facultatif)&nbsp;:</label><input type="url" name="website" value="<?php echo $data['website']; ?>" id="f_website" maxlength="255"><br>
			<label for="f_mod_text">Texte long (HTML)&nbsp;:</label><br>
			<textarea name="text" id="f_mod_text" maxlength="20000" rows="20" cols="500" onkeyup="close_confirm=true"><?php echo $data['text']; ?></textarea><br>
			<input type="submit" value="Modifier">
		</form>
		<script type="text/javascript">init_close_confirm();</script><?php }$req->closeCursor();}
if(isset($_GET['addfile'])) {
	$req = $bdd->prepare('SELECT * FROM softwares WHERE id=? ORDER BY name ASC');
	$req->execute(array($_GET['addfile']));
	if($data = $req->fetch()) { ?>
		<p>Ajouter un fichier pour <a href="?listfiles=<?php echo $_GET['addfile']; ?>"><?php echo $data['name']; ?></a></p>
		<form action="?upload=<?php echo $_GET['addfile']; ?>" method="post" enctype="multipart/form-data">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
		
			<fieldset><legend>Ajouter un fichier</legend>
				<label for="f_addfile_title">Titre du fichier&nbsp;:</label>
				<input type="text" name="title" id="f_addfile_title" placeholder="Toto Installateur Windows" required><br>
				
				<p>Si le fichier fait plus de 2Go ou si votre connexion est très lente, utilisez la méthode <em>Hors formulaire</em>. Vous nécessiterez généralement un accès FTP. Si le fichier est directement accessible via une URL, vous pouvez essayer la méthode <em>URL</em>. Dans ce dernier cas, utiliser un miroir doit être considéré.</p>
				
				<label for="f_addfile_method">Méthode d'envoi&nbsp;:</label>
				<select name="method" id="f_addfile_method" onchange="f_addfile_group_method()">
					<option value="form" selected>Simple (&lt; 2Go)</option>
					<option value="ext">Hors formulaire</option>
					<option value="url">URL</option>
				</select><br>
				
				<div id="f_addfile_group_method_form">
					<label for="f_addfile_file">Fichier&nbsp;:</label>
					<input type="file" name="file" id="f_addfile_file">
					<noscript>Ne choisir un fichier que si la méthode <em>Simple</em> est choisie.</noscript>
					<p>Si le nom souhaité est différent du nom actuel du fichier, remplissez le champ suivant. Sinon, laissez vide.</p>
				</div>
				
				<div id="f_addfile_group_method_url">
					<label for="f_addfile_url">URL&nbsp;:</label>
					<input type="text" name="url" id="f_addfile_url">
					<noscript>Ne choisir une URL que si la méthode <em>URL</em> est choisie.</noscript>
				</div>
				
				<label for="f_addfile_name">Nom du fichier&nbsp;:</label>
				<input type="text" name="name" id="f_addfile_name" placeholder="toto-v1.2.3.installer.exe"><br>
				
				<label for="f_addfile_label">Label&nbsp;:</label>
				<input type="text" name="label" id="f_addfile_label" placeholder="toto-win-install"><br>
				
				<label for="f_addfile_social">Annoncer sur les médias sociaux&nbsp;:</label>
				<input type="checkbox" name="social" id="f_addfile_social"<?php if(!DEV) echo ' checked'; ?>><br>
				
				<input type="submit" value="Ajouter">
				
				<script type="text/javascript">
function f_addfile_group_method() {
	var val = document.getElementById("f_addfile_method").value;
	switch(val) {
		case "form":
			document.getElementById("f_addfile_group_method_form").style = "";
			document.getElementById("f_addfile_file").required = true;
			document.getElementById("f_addfile_group_method_url").style = "display: none;";
			document.getElementById("f_addfile_url").required = false;
			document.getElementById("f_addfile_name").required = false;
		break;
		case "ext":
			document.getElementById("f_addfile_group_method_form").style = "display: none;";
			document.getElementById("f_addfile_file").required = false;
			document.getElementById("f_addfile_group_method_url").style = "display: none;";
			document.getElementById("f_addfile_url").required = false;
			document.getElementById("f_addfile_name").required = true;
		break;
		case "url":
			document.getElementById("f_addfile_group_method_form").style = "display: none;";
			document.getElementById("f_addfile_file").required = false;
			document.getElementById("f_addfile_group_method_url").style = "";
			document.getElementById("f_addfile_url").required = true;
			document.getElementById("f_addfile_name").required = true;
		break;
	}
}
f_addfile_group_method();
</script>
			</fieldset>
		</form>
		<form action="?addmirror=<?php echo $_GET['addfile']; ?>" method="post">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<fieldset><legend>Ajouter un miroir</legend>
				<label for="f_addmirror_title">Titre du fichier&nbsp;:</label>
				<input type="text" name="title" id="f_addmirror_title"><br>
				<label for="f_addmirror_urls">URLs des miroirs&nbsp;:</label><br>
				<textarea name="urls" id="f_addmirror_urls" style="width: 100%;" onkeyup="close_confirm=true"></textarea>
				<p>Exemple&nbsp;: [["ZettaScript","https://zettascript.org/fichier.tar.gz"],["CommentÇaMarche","https://commentcamarche.net/download/fichier"]]</p>
				<label for="f_addmirror_label">Label&nbsp;:</label>
				<input type="text" name="label" id="f_addmirror_label"><br>
				<input type="submit" value="Ajouter">
			</fieldset>
		</form>
		<script type="text/javascript">init_close_confirm();</script>
<?php }$req->closeCursor();}
if(isset($_GET['modf'])) {
	$req = $bdd->prepare('SELECT * FROM softwares_files WHERE id=?');
	$req->execute(array($_GET['modf']));
	if($data = $req->fetch()) { ?>
		<a href="?listfiles=<?php echo $data['sw_id']; ?>">Liste des fichiers de l'article</a>
		<form action="?modf2=<?php echo $data['id']; ?>" method="post" enctype="multipart/form-data" onsubmit="f_modf_submit(event)">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<h2>Modifier un fichier</h2>
			<fieldset>
				<legend>Métadonnées</legend>
				<label for="f_modf_title">Titre du fichier&nbsp;:</label>
				<input type="text" name="title" id="f_modf_title" value="<?php echo $data['title']; ?>" required><br>
				<label for="f_modf_name">Nom du fichier&nbsp;:</label>
				<input type="text" name="name" id="f_modf_name" value="<?php echo $data['name']; ?>" required><br>
				<label for="f_modf_label">Label&nbsp;:</label>
				<input type="text" name="label" id="f_modf_label" value="<?php echo $data['label']; ?>">
			</fieldset>
			<fieldset>
				<legend>Remplacer le fichier</legend>
				
				<label for="f_modf_method">Méthode d'envoi&nbsp;:</label>
				<select name="method" id="f_modf_method" onchange="f_modf_group_method()">
					<option value="">Ne pas remplacer</option>
					<option value="form" selected>Simple (&lt; 2Go)</option>
					<option value="url">URL</option>
				</select><br>
				
				<noscript>Laissez vides les champs suivants si <em>Ne pas remplacer</em> est choisi.</noscript>
				
				<div id="f_modf_group_method_form">
					<label for="f_modf_file">Fichier&nbsp;:</label>
					<input type="file" name="file" id="f_modf_file">
					<noscript>Ne choisir un fichier que si la méthode <em>Simple</em> est choisie.</noscript><br>
					<label for="f_modf_overwrite_name">Utiliser le nom du nouveau fichier envoyé&nbsp;:</label>
					<input type="checkbox" name="overwrite_name" id="f_modf_overwrite_name" checked>
				</div>
				
				<div id="f_modf_group_method_url">
					<label for="f_modf_url">URL&nbsp;:</label>
					<input type="text" name="url" id="f_modf_url">
					<noscript>Ne choisir une URL que si la méthode <em>URL</em> est choisie.</noscript>
				</div>
			</fieldset>
			
			<label for="f_modf_social">Annoncer sur les médias sociaux&nbsp;:</label>
			<input type="checkbox" name="social" id="f_modf_social"<?php if(!DEV) echo ' checked'; ?>><br>
			
			<input type="submit" value="Modifier">
				
				<script type="text/javascript">
function f_modf_group_method() {
	var val = document.getElementById("f_modf_method").value;
	switch(val) {
		case "":
			document.getElementById("f_modf_group_method_form").style = "display: none;";
			document.getElementById("f_modf_group_method_url").style = "display: none;";
			document.getElementById("f_modf_url").required = false;
			document.getElementById("f_modf_name").required = true;
		break;
		case "form":
			document.getElementById("f_modf_group_method_form").style = "";
			document.getElementById("f_modf_group_method_url").style = "display: none;";
			document.getElementById("f_modf_url").required = false;
			document.getElementById("f_modf_name").required = false;
		break;
		case "url":
			document.getElementById("f_modf_group_method_form").style = "display: none;";
			document.getElementById("f_modf_group_method_url").style = "";
			document.getElementById("f_modf_url").required = true;
			document.getElementById("f_modf_name").required = true;
		break;
	}
}
function f_modf_submit(e) {
	if(document.getElementById("f_modf_method").value == "form" && document.getElementById("f_modf_name").value == "" && document.getElementById("f_modf_file").files.length == 0) {
		alert("Les champs Nom et Fichier ne doivent pas tous être vides.");
		e.preventDefault();
	}
}
f_modf_group_method();
</script>
		</form>
<?php }$req->closeCursor();}
if(isset($_GET['modm'])) {
	$req = $bdd->prepare('SELECT * FROM `softwares_mirrors` WHERE `id`=? LIMIT 1');
	$req->execute(array($_GET['modm']));
	if($data = $req->fetch()) { ?>
		<form action="?modm2=<?php echo $data['id']; ?>" method="post">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<h2>Modifier un miroir</h2>
			<label for="f_modf_title">Titre du fichier&nbsp;:</label>
			<input type="text" name="title" id="f_modm_title" value="<?php echo $data['title']; ?>"><br>
			<label for="f_modm_urls">URLs des miroirs&nbsp;:</label><br>
			<textarea name="urls" id="f_modm_urls"><?php echo htmlentities($data['links']); ?></textarea><br>
			<label for="f_modf_label">Label&nbsp;:</label>
			<input type="text" name="label" id="f_modm_label" value="<?php echo $data['label']; ?>"><br>
			<input type="submit" value="Modifier">
		</form>
<?php }$req->closeCursor();} ?>
	</body>
</html>