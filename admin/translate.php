<?php
$logonly = true;
$adminonly = true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';
$tr_todo = array(0=>'Référence', 1=>'OK', 2=>'À vérifier', 3=>'À modifier', 4=>'À terminer');

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Traductions &#8211; <?php print $nomdusite; ?></title>
		<?php print $cssadmin; ?>
		<link rel="stylesheet" href="css/translate.css">
		<script type="text/javascript" src="/scripts/default.js"></script>
		<script type="text/javascript" src="/scripts/jquery.js"></script>
		<script type="text/javascript" src="js/translate.js"></script>
	</head>
	<body>
		<h1>Outil de traduction &#8211; <a href="/"><?php print $nomdusite; ?></a></h1>
		<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php'; ?>
		<a href="translate_todo.php">Toutes les traductions</a><br>
		
<?php
if(isset($_GET['type'])) {
	if($_GET['type'] == 'article' and isset($_GET['id'])) {
		$req = $bdd->prepare('SELECT `softwares`.*, `softwares_categories`.`name` AS `category_name` 
FROM `softwares` 
LEFT JOIN `softwares_categories` ON `softwares_categories`.`id`=`softwares`.`category` 
WHERE `softwares`.`id`=? LIMIT 1');
		$req->execute(array($_GET['id']));
		if($data = $req->fetch()) {
			// form: back
			if(isset($_GET['a']) and ((isset($_GET['token']) and $_GET['token'] == $login['token']) or (isset($_POST['token']) and $_POST['token'] == $login['token']))) {
				if($_GET['a'] == 'rm' and isset($_GET['tr'])) {
					$req2 = $bdd->prepare('DELETE FROM `softwares_tr` WHERE `id`=? AND `sw_id`=? LIMIT 1');
					$req2->execute(array($_GET['tr'], $data['id']));
					header('Location: ?type=article&id='.$data['id']);
					exit();
				}
				elseif(($_GET['a'] == 'pub' or $_GET['a'] == 'priv') and isset($_GET['tr'])) {
					$req2 = $bdd->prepare('UPDATE `softwares_tr` SET `published`=? WHERE `id`=? AND `sw_id`=? LIMIT 1');
					$req2->execute(array(intval($_GET['a']=='pub'), $_GET['tr'], $data['id']));
					header('Location: ?type=article&id='.$data['id']);
					exit();
				}
				elseif($_GET['a'] == 'new2') {
					$tr_lang = '';
					if(isset($_POST['lang']) and in_array($_POST['lang'], $langs_prio))
						$tr_lang = $_POST['lang'];
					$tr_name = '';
					if(isset($_POST['tr_name']) and strlen($_POST['tr_name']) <= 255)
						$tr_name = $_POST['tr_name'];
					$tr_text = '';
					if(isset($_POST['tr_text']) and strlen($_POST['tr_text']) <= 65535)
						$tr_text = $_POST['tr_text'];
					$tr_tags = '';
					if(isset($_POST['tr_tags']) and strlen($_POST['tr_tags']) <= 512)
						$tr_tags = $_POST['tr_tags'];
					$tr_description = '';
					if(isset($_POST['tr_description']) and strlen($_POST['tr_description']) <= 512)
						$tr_description = $_POST['tr_description'];
					$tr_website = '';
					if(isset($_POST['tr_website']) and strlen($_POST['tr_website']) <= 255)
						$tr_website = $_POST['tr_website'];
					$published = intval(isset($_POST['ref']));
					$todo_level = isset($_POST['ref']) ? 0 : 2;
					$req2 = $bdd->prepare('INSERT INTO `softwares_tr` (`sw_id`, `lang`, `date`, `name`, `text`, `keywords`, `description`, `website`, `author`, `published`, `todo_level`) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
					$req2->execute(array($data['id'], $tr_lang, time(), $tr_name, $tr_text, $tr_tags, $tr_description, $tr_website, $nom, $published, $todo_level));
					if(isset($_POST['update_article_date'])) {
						$req2 = $bdd->prepare('UPDATE `softwares` SET `date`=?, `author`=? WHERE `id`=? LIMIT 1');
						$req2->execute(array(time(), $nom, $data['id']));
					}
					header('Location: ?type=article&id='.$data['id']);
					exit();
				}
				elseif($_GET['a'] == 'edit2' and isset($_GET['tr'])) {
					$tr_lang = '';
					if(isset($_POST['lang']) and in_array($_POST['lang'], $langs_prio))
						$tr_lang = $_POST['lang'];
					$tr_name = '';
					if(isset($_POST['tr_name']) and strlen($_POST['tr_name']) <= 255)
						$tr_name = $_POST['tr_name'];
					$tr_text = '';
					if(isset($_POST['tr_text']) and strlen($_POST['tr_text']) <= 65535)
						$tr_text = $_POST['tr_text'];
					$tr_tags = '';
					if(isset($_POST['tr_tags']) and strlen($_POST['tr_tags']) <= 512)
						$tr_tags = $_POST['tr_tags'];
					$tr_description = '';
					if(isset($_POST['tr_description']) and strlen($_POST['tr_description']) <= 512)
						$tr_description = $_POST['tr_description'];
					$tr_website = '';
					if(isset($_POST['tr_website']) and strlen($_POST['tr_website']) <= 255)
						$tr_website = $_POST['tr_website'];
					$req2 = $bdd->prepare('UPDATE `softwares_tr` SET `lang`=?, `date`=?, `name`=?, `text`=?, `keywords`=?, `description`=?, `website`=?, `author`=? WHERE `id`=? AND `sw_id`=? LIMIT 1');
					$req2->execute(array($tr_lang, time(), $tr_name, $tr_text, $tr_tags, $tr_description, $tr_website, $nom, $_GET['tr'], $data['id']));
					if(isset($_POST['update_article_date'])) {
						$req2 = $bdd->prepare('UPDATE `softwares` SET `date`=?, `author`=? WHERE `id`=? LIMIT 1');
						$req2->execute(array(time(), $nom, $data['id']));
					}
					header('Location: ?type=article&id='.$data['id']);
					exit();
				}
				elseif($_GET['a'] == 'todo' and isset($_GET['tr_todo']) and isset($_GET['s'])) {
					foreach($_GET['s'] as &$i) {
						$req2 = $bdd->prepare('UPDATE `softwares_tr` SET `todo_level`=? WHERE `id`=? LIMIT 1');
						$req2->execute(array($_GET['tr_todo'], $i));
					}
					header('Location: ?type=article&id='.$data['id']);
					exit();
				}
			}
			
			echo '<p><strong>Article</strong>&nbsp;: <a href="sw_mod.php?id='.$data['id'].'">'.htmlentities($data['name']).'</a><br>Catégorie&nbsp;: <em>'.htmlentities($data['category_name']).'</em><br>Dernier auteur&nbsp;: '.htmlentities($data['author']).'</p>';
			
			// form: front
			if(isset($_GET['a']) and $_GET['a'] == 'new') {
				$model = false;
				if(isset($_GET['model']) and !empty($_GET['model'])) {
					$req2 = $bdd->prepare('SELECT * FROM `softwares_tr` WHERE `id`=? AND `sw_id`=? LIMIT 1');
					$req2->execute(array($_GET['model'], $data['id']));
					$model = $req2->fetch();
				} ?>
		<h2>Nouvelle traduction</h2>
		<form method="post" action="?type=article&id=<?php echo $data['id']; ?>&a=new2">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<?php if(isset($_GET['ref'])) echo '<input type="hidden" name="ref" value="1">'; ?>
			<label for="tr_sw_new_lang">Langue&nbsp;:</label>
			<select id="tr_sw_new_lang" name="lang" autocomplete="off"><?php echo $langs_html_opts; ?></select>
			<table class="trtable">
				<thead><tr><?php echo ($model?'<th>Modèle</th>':''); ?><th>Nouveau</th></tr></thead>
				<tbody>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_new_model_name">Titre modèle&nbsp;:</label><br><input type="text" id="tr_sw_new_model_name" readonly value="<?php echo htmlentities($model['name']); ?>"></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_new_name">Titre nouveau&nbsp;:</label><br><input type="text" id="tr_sw_new_name" name="tr_name" maxlength="255" autocomplete="off"></td>
					</tr>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_new_model_text">Texte modèle&nbsp;:</label><br><textarea id="tr_sw_new_model_text" readonly><?php echo htmlentities($model['text']); ?></textarea></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_new_text">Texte nouveau&nbsp;:</label><br><textarea id="tr_sw_new_text" name="tr_text" maxlength="35535" autocomplete="off" onkeyup="close_confirm=true"></textarea></td>
					</tr>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_new_model_tags">Mots-clefs modèle&nbsp;:</label><br><textarea id="tr_sw_new_model_tags" readonly><?php echo htmlentities($model['keywords']); ?></textarea></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_new_tags">Mots-clefs nouveau&nbsp;:</label><br><textarea id="tr_sw_new_tags" name="tr_tags" maxlength="512" autocomplete="off"></textarea></td>
					</tr>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_new_model_description">Description modèle&nbsp;:</label><br><textarea id="tr_sw_new_model_description" readonly><?php echo htmlentities($model['description']); ?></textarea></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_new_description">Description nouveau&nbsp;:</label><br><textarea id="tr_sw_new_description" name="tr_description" maxlength="512" autocomplete="off"></textarea></td>
					</tr>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_new_model_website">Site officiel modèle&nbsp;:</label><br><input type="text" id="tr_sw_new_model_website" value="<?php echo htmlentities($model['website']); ?>" readonly></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_new_website">Site officiel nouveau&nbsp;:</label><br><input type="text" id="tr_sw_new_website" name="tr_website" maxlength="255" autocomplete="off"></td>
					</tr>
				</tbody>
			</table>
			<label for="tr_sw_new_uad">Mettre à jour la date de l'article</label>
			<input type="checkbox" id="tr_sw_new_uad" name="update_article_date" autocomplete="off"<?php if(isset($_GET['ref'])) echo 'checked'; ?>><br>
			<input type="submit" value="Envoyer">
		</form>
		<script type="text/javascript">init_close_confirm();</script>
		<hr>
		<?php
			}
			if(isset($_GET['edit'])) {
				$req2 = $bdd->prepare('SELECT * FROM `softwares_tr` WHERE `id`=? AND `sw_id`=? LIMIT 1');
				$req2->execute(array($_GET['edit'], $data['id']));
				$tr_mod = $req2->fetch();
				$model = false;
				if(isset($_GET['model']) and !empty($_GET['model'])) {
					$req2 = $bdd->prepare('SELECT * FROM `softwares_tr` WHERE `id`=? AND `sw_id`=? LIMIT 1');
					$req2->execute(array($_GET['model'], $data['id']));
					$model = $req2->fetch();
				} ?>
		<h2>Modifier une traduction</h2>
		<form method="post" action="?type=article&id=<?php echo $data['id']; ?>&a=edit2&tr=<?php echo $tr_mod['id']; ?>">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<label for="tr_sw_edit_lang">Langue&nbsp;:</label>
			<select id="tr_sw_edit_lang" name="lang" autocomplete="off"><?php echo langs_html_opts($tr_mod['lang']); ?></select>
			<table class="trtable">
				<thead><tr><?php echo ($model?'<th>Modèle</th>':''); ?><th>En modification</th></tr></thead>
				<tbody>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_edit_model_name">Titre modèle&nbsp;:</label><br><input type="text" id="tr_sw_edit_model_name" readonly value="<?php echo htmlentities($model['name']); ?>"></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_edit_name">Titre en modification&nbsp;:</label><br><input type="text" id="tr_sw_edit_name" name="tr_name" maxlength="255" autocomplete="off" value="<?php echo htmlentities($tr_mod['name']); ?>"></td>
					</tr>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_edit_model_text">Texte modèle&nbsp;:</label><br><textarea id="tr_sw_edit_model_text" readonly><?php echo htmlentities($model['text']); ?></textarea></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_edit_text">Texte en modification&nbsp;:</label><br><textarea id="tr_sw_edit_text" name="tr_text" autocomplete="off" maxlength="35535" onkeyup="close_confirm=true"><?php echo htmlentities($tr_mod['text']); ?></textarea></td>
					</tr>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_edit_model_tags">Mots-clefs modèle&nbsp;:</label><br><textarea id="tr_sw_edit_model_tags" readonly><?php echo htmlentities($model['keywords']); ?></textarea></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_edit_tags">Mots-clefs en modification&nbsp;:</label><br><textarea id="tr_sw_edit_tags" name="tr_tags" maxlength="512" autocomplete="off"><?php echo htmlentities($tr_mod['keywords']); ?></textarea></td>
					</tr>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_edit_model_description">Description modèle&nbsp;:</label><br><textarea id="tr_sw_edit_model_description" readonly><?php echo htmlentities($model['description']); ?></textarea></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_edit_description">Description en modification&nbsp;:</label><br><textarea id="tr_sw_edit_description" name="tr_description" maxlength="512" autocomplete="off"><?php echo htmlentities($tr_mod['description']); ?></textarea></td>
					</tr>
					<tr>
						<?php if($model) { ?>
						<td class="trform2"><label for="tr_sw_edit_model_website">Site officiel modèle&nbsp;:</label><br><input type="text" id="tr_sw_edit_model_website" value="<?php echo htmlentities($model['website']); ?>" readonly></td>
						<?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>"><label for="tr_sw_edit_website">Site officiel en modification&nbsp;:</label><br><input type="text" id="tr_sw_edit_website" name="tr_website" value="<?php echo htmlentities($tr_mod['website']); ?>" maxlength="255" autocomplete="off"></td>
					</tr>
				</tbody>
			</table>
			<label for="tr_sw_edit_uad">Mettre à jour la date de l'article</label>
			<input type="checkbox" id="tr_sw_edit_uad" name="update_article_date" autocomplete="off"<?php if($tr_mod['todo_level']==0) echo 'checked'; ?>><br>
			<input type="submit" value="Envoyer">
		</form>
		<script type="text/javascript">init_close_confirm();</script>
		<hr>
		<?php
			} ?>
		<h2>Traductions</h2>
		<form action="translate.php" method="get">
			<input type="hidden" name="type" value="article">
			<input type="hidden" name="id" value="<?php echo $data['id']; ?>">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<table border="1">
				<thead><tr><th></th><th>Langue</th><th>Dernier auteur</th><th>Dernière modif</th><th>État</th><th>Publiée</th><th>Actions</th></tr></thead>
				<tbody><?php
			$req2 = $bdd->prepare('SELECT `softwares_tr`.*, `languages`.`name` AS `language` FROM `softwares_tr` 
LEFT JOIN `languages` ON `languages`.`lang`=`softwares_tr`.`lang`
WHERE `sw_id`=?');
			$req2->execute(array($data['id']));
			while($data2 = $req2->fetch()) {
				echo '<tr>
						<td><input type="checkbox" name="s[]" value="'.$data2['id'].'" aria-label="Sélectionner '.$data2['language'].' (pour suppression)" title="Sélectionner"></td>
						<td title="'.$data2['lang'].'">'.$data2['language'].'</td>
						<td>'.htmlentities($data2['author']).'</td>
						<td>'.date('d/m/Y H:i', $data2['date']).'</td>
						<td class="tr_todo'.$data2['todo_level'].'">'.$tr_todo[$data2['todo_level']].'</td>
						<td class="tr_published'.$data2['published'].'">'.($data2['published']?'Public':'Privé').'</td>
						<td>
							<input type="radio" title="Modèle" aria-label="Sélectionner '.$data2['language'].' (comme modèle)" name="model" value="'.$data2['id'].'">
							<a href="?type=article&id='.$data['id'].'&tr='.$data2['id'].'&token='.$login['token'].'&a='.($data2['published']?'priv">Fermer':'pub">Publier').'</a>
							<button type="submit" name="edit" value="'.$data2['id'].'" aria-label="Modifier avec le modèle sélectionné" title="Modifié avec le modèle sélectionné">Modifier</button>
							<a href="?type=article&id='.$data['id'].'&tr='.$data2['id'].'&a=rm&token='.$login['token'].'">Supprimer</a>
						</td>
					</tr>';
			} ?>
				</tbody>
			</table>
			<fieldset><legend>Pour le modèle sélectionné</legend>
				<label for="f_sw_nomodel">Pas de modèle</label> <input id="f_sw_nomodel" type="radio" name="model" value="" checked>
				<button type="submit" name="a" value="new">Nouvelle traduction</button>
			</fieldset>
			<fieldset><legend>Pour les items sélectionnés</legend>
				<label for="tr_sw_new_todo">Changer l'état&nbsp;:</label> <select id="tr_sw_new_todo" name="tr_todo"><option value="0">Référence</option><option value="1">OK</option><option value="2">À vérifier</option><option value="3">À modifier</option></select>
				<button type="submit" name="a" value="todo">Changer l'état</button>
			</fieldset>
		</form><?php
		}
	}
	
	
	if($_GET['type'] == 'trsfiles' and isset($_GET['trsfiles']) and preg_match('#^[a-zA-Z0-9_]+$#',$_GET['trsfiles'])) {
		require_once($_SERVER['DOCUMENT_ROOT'].'/cache/langs_index.php');
		
		if(isset($_GET['a']) and ((isset($_GET['token']) and $_GET['token'] == $login['token']) or (isset($_POST['token']) and $_POST['token'] == $login['token']))) {
			if($_GET['a'] == 'edit2' and isset($_POST['lang']) and key_exists($_POST['lang'], $available_trs_index) and key_exists($_GET['trsfiles'], $available_trs_index[$_POST['lang']])) {
				include($_SERVER['DOCUMENT_ROOT'].'/locales/'.$_POST['lang'].'/'.$_GET['trsfiles'].'.tr.php');
				$tr_e = $tr;
				$new_tr_e = array('_'=>$tr_e['_']);
				$new_tr_e['_todo_level'] = (isset($_POST['todo']) and key_exists($_POST['todo'], $tr_todo)) ? intval($_POST['todo']) : 2;
				$new_tr_e['_last_author'] = $nom;
				$new_tr_e['_last_modif'] = time();
				
				foreach($_POST as $key => $val) {
					if(substr($key, 0, 4) === 'tr0_' and $val == 'on' and isset($_POST['tr_'.substr($key, 4)]))
						$new_tr_e[substr($key, 4)] = $_POST['tr_'.substr($key, 4)];
				}
				$f = fopen($_SERVER['DOCUMENT_ROOT'].'/locales/'.$_POST['lang'].'/'.$_GET['trsfiles'].'.tr.php', 'w');
				fwrite($f, '<?php $tr='.var_export($new_tr_e, true)."; ?>\n");
				fclose($f);
				include($_SERVER['DOCUMENT_ROOT'].'/tasks/langs_cache.php');
				header('Location: ?type=trsfiles&trsfiles='.$_GET['trsfiles']);
				exit();
			}
			elseif($_GET['a'] == 'new' and isset($_GET['tr_new_lang']) and key_exists($_GET['tr_new_lang'], $available_trs_index)) {
				$new_tr_e = array('_'=>$_GET['trsfiles']);
				$new_tr_e['_todo_level'] = 4;
				$new_tr_e['_last_author'] = $nom;
				$new_tr_e['_last_modif'] = time();
				$f = fopen($_SERVER['DOCUMENT_ROOT'].'/locales/'.$_GET['tr_new_lang'].'/'.$_GET['trsfiles'].'.tr.php', 'w');
				fwrite($f, '<?php $tr='.var_export($new_tr_e, true)."; ?>\n");
				fclose($f);
				include($_SERVER['DOCUMENT_ROOT'].'/tasks/langs_cache.php');
				header('Location: ?type=trsfiles&trsfiles='.$_GET['trsfiles']);
				exit();
			}
		}
		
		if(isset($_GET['edit']) and key_exists($_GET['edit'], $available_trs_index) and key_exists($_GET['trsfiles'], $available_trs_index[$_GET['edit']])) {
			$model = false;
			if(isset($_GET['model']) and key_exists($_GET['model'], $available_trs_index) and key_exists($_GET['trsfiles'], $available_trs_index[$_GET['model']]))
				$model = $_GET['model'];
			?>
		<h2>Modifier une traduction</h2>
		<form method="post" action="?type=trsfiles&trsfiles=<?php echo $_GET['trsfiles']; ?>&a=edit2">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<label for="tr_trsfiles_edit_lang">Langue&nbsp;:</label>
			<select id="tr_trsfiles_edit_lang" name="lang" autocomplete="off"><?php echo langs_html_opts($_GET['edit']); ?></select><br>
			<label for="tr_trsfiles_edit_todo">État&nbsp;:</label>
			<select id="tr_trsfiles_edit_todo" name="todo" autocomplete="off"><?php foreach($tr_todo as $key => $val) {echo '<option value="'.$key.'"'.($available_trs_index[$_GET['edit']][$_GET['trsfiles']]['todo_level']===$key? ' selected':'').'>'.$val.'</option>';} ?></select>
			<table id="tr_trsfiles_edit_t" class="trtable">
				<thead><tr><?php echo ($model?'<th title="'.$model.'">Modèle ('.$langs[$model].')</th>':''); ?><th title="<?php echo $_GET['edit']; ?>">En modification (<?php echo $langs[$_GET['edit']]; ?>)</th></tr></thead>
				<tbody>
					<?php
			if($model) {
				include($_SERVER['DOCUMENT_ROOT'].'/locales/'.$model.'/'.$_GET['trsfiles'].'.tr.php');
				$tr_m = $tr;
			}
			include($_SERVER['DOCUMENT_ROOT'].'/locales/'.$_GET['edit'].'/'.$_GET['trsfiles'].'.tr.php');
			$tr_e = $tr;
			foreach($tr_e as $key => $text) {
				if(strpos($key, '_') === 0)
					continue;
				?>
					<tr>
						<?php if($model) { ?><td class="trform2"><?php if(key_exists($key, $tr_m)) { ?>
							<label for="tr_trsfiles_edit_m_<?php echo htmlentities($key); ?>">Modèle <em><?php echo htmlentities($key); ?></em></label><br>
							<textarea id="tr_trsfiles_edit_m_<?php echo htmlentities($key); ?>" readonly><?php echo htmlentities($tr_m[$key]); ?></textarea>
						<?php } ?></td><?php } ?>
						<td class="trform<?php echo ($model?'2':'1'); ?>">
							<input type="checkbox" id="tr_trsfiles_edit_e0_<?php echo htmlentities($key); ?>" name="tr0_<?php echo htmlentities($key); ?>" aria-label="Activer" checked autocomplete="off"/>
							<label for="tr_trsfiles_edit_e_<?php echo htmlentities($key); ?>"><em><?php echo htmlentities($key); ?></em></label><br>
							<textarea id="tr_trsfiles_edit_e_<?php echo htmlentities($key); ?>" name="tr_<?php echo htmlentities($key); ?>" autocomplete="off" onkeyup="close_confirm=true"><?php echo htmlentities($text); ?></textarea></td>
					</tr>
					<?php
			}
			if($model) {
				foreach($tr_m as $key => $text) {
					if(strpos($key, '_') === 0 or key_exists($key, $tr_e))
						continue;
					?>
					<tr>
						<td class="trform2"><label for="tr_trsfiles_edit_m_<?php echo htmlentities($key); ?>">Modèle <em><?php echo htmlentities($key); ?></em></label><br>
							<textarea id="tr_trsfiles_edit_m_<?php echo htmlentities($key); ?>" readonly><?php echo htmlentities($text); ?></textarea></td>
						<td class="trform<?php echo ($model?'2':'1'); ?>">
							<input type="checkbox" id="tr_trsfiles_edit_e0_<?php echo htmlentities($key); ?>" name="tr0_<?php echo htmlentities($key); ?>" aria-label="Activer" unchecked autocomplete="off">
							<label for="tr_trsfiles_edit_e_<?php echo htmlentities($key); ?>"><em><?php echo htmlentities($key); ?></em></label><br>
							<textarea id="tr_trsfiles_edit_e_<?php echo htmlentities($key); ?>" name="tr_<?php echo htmlentities($key); ?>" autocomplete="off" onkeyup="close_confirm=true"></textarea></td>
					</tr>
					<?php
				}
			}	
			?>
				</tbody>
			</table>
			<label for="tr_trsfiles_edit_add">Ajouter une traduction&nbsp;:</label>
			<input type="text" id="tr_trsfiles_edit_add">
			<input type="button" value="Ajouter" onclick="trsfiles_add_tr(<?php echo $model ? 'true' : 'false'; ?>);"><br>
			<input type="submit" value="Envoyer">
		</form>
		<script type="text/javascript">init_close_confirm();</script>
		<hr>
		<?php
		}
		?>
		<h2>Traductions</h2>
		<p>Fichier&nbsp;: <strong><?php echo htmlentities($_GET['trsfiles']); ?></strong></p>
		<form action="translate.php" method="get">
			<input type="hidden" name="type" value="trsfiles">
			<input type="hidden" name="trsfiles" value="<?php echo htmlentities($_GET['trsfiles']); ?>">
			<input type="hidden" name="token" value="<?php echo $login['token']; ?>">
			<table border="1">
				<thead><tr><th></th><th>Langue</th><th>Dernier auteur</th><th>Dernière modif</th><th>État</th><th>Actions</th></tr></thead>
				<tbody><?php
		foreach($available_trs_index as $trsdir => $trsfiles) {
			foreach($trsfiles as $trsfile => $data) {
				if($trsfile != $_GET['trsfiles'])
					continue;
				echo '
					<tr>
						<td><input type="checkbox" name="s[]" value="'.$trsdir.'" aria-label="Sélection" title="Sélectionner"></td>
						<td title="'.$trsdir.'">'.$langs[$trsdir].'</td>
						<td>'.htmlentities($data['last_author']).'</td>
						<td>'.date('d/m/Y H:i', $data['last_modif']).'</td>
						<td class="tr_todo'.$data['todo_level'].'">'.$tr_todo[$data['todo_level']].'</td>
						<td>
							<input type="radio" title="Modèle" aria-label="Modèle" name="model" value="'.$trsdir.'">
							<button type="submit" name="edit" value="'.$trsdir.'" aria-label="Modifier avec le modèle sélectionné" title="Modifié avec le modèle sélectionné">Modifier</button>
							<a href="?type=trsfiles&trsfiles='.$trsfile.'&trsdir='.$trsdir.'&a=rm&token='.$login['token'].'">Supprimer</a>
						</td>
					</tr>';
			}
		} ?>
				</tbody>
			</table>
			<fieldset><legend>Pour le modèle sélectionné</legend>
				<label for="f_trsfiles_nomodel">Pas de modèle</label> <input id="f_trsfiles_nomodel" type="radio" name="model" value="" checked>
			</fieldset>
			<fieldset><legend>Pour les items sélectionnés</legend>
				<label for="f_tr_trsfiles_todo">Changer l'état&nbsp;:</label> <select id="f_tr_trsfiles_todo" name="tr_todo"><?php foreach($tr_todo as $key => $val) {echo '<option value="'.$key.'">'.$val.'</option>';} ?></select>
				<button type="submit" name="a" value="todo">Changer l'état</button>
			</fieldset>
			<fieldset><legend>Nouveau fichier de traduction</legend>
				<label for="f_tr_trsfiles_new_lang">Langue&nbsp;:</label>
				<select id="f_tr_trsfiles_new_lang" name="tr_new_lang"><?php echo $langs_html_opts; ?></select><br>
				<button type="submit" name="a" value="new">Créer</button>
			</fieldset>
		</form>
		<?php
	}
}
?>
		<hr>
		<h3>Licence</h3>
		<p>Les données de traduction envoyées et gérées par cette page sont sous licence <a href="https://creativecommons.org/licenses/by-sa/4.0/">CC BY-SA 4.0</a> au nom de "L'équipe <?php echo $nomdusite; ?>". Le contenu du site et ses traductions sont une œuvre collaborative et libre.</p>
	</body>
</html>
