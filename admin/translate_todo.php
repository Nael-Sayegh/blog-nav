<?php
$logonly = true;
$adminonly = true;
$justpa = true;
require $_SERVER['DOCUMENT_ROOT'].'/inclus/log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/inclus/consts.php';
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Traductions &#8211; <?php print $nomdusite; ?></title>
		<?php print $cssadmin; ?>
		<link rel="stylesheet" href="css/translate.css">
		<script type="text/javascript" src="/scripts/default.js"></script>
	</head>
	<body>
		<h1>Traduction&nbsp;: à faire &#8211; <a href="/"><?php print $nomdusite; ?></a></h1>
		<?php include $_SERVER['DOCUMENT_ROOT'].'/inclus/loginbox.php'; ?>
		<!--<a href="#trs-files">Traductions du site</a><br>-->
		<h2 id="tr-articles">Articles</h2>
		<form action="translate_todo.php#tr-articles" method="get">
			<fieldset>
				<legend>Trier les traductions d'articles</legend>
				<label for="f_sort_article_lang">Langue</label>
				<select id="f_sort_article_lang" name="article_lang" autocomplete="off">
					<option value="">Tout</option>
					<?php echo isset($_GET['article_lang']) ? langs_html_opts($_GET['article_lang']) : $langs_html_opts; ?>
				</select><br>
				<label for="f_sort_article_todo">État</label>
				<select id="f_sort_article_todo" name="article_todo" autocomplete="off">
					<option value="">Tout</option>
					<?php 
					$foo_todo = (isset($_GET['article_todo']) and $_GET['article_todo'] != '') ? intval($_GET['article_todo']) : 0;
					foreach($tr_todo as $key => $val) {
						echo '<option value="'.$key.'"'.($foo_todo===$key ? ' selected':'').'>'.$val.'</option>';
					}
					?>
				</select><br>
				<input type="submit" value="Trier">
			</fieldset>
		</form>
		<table border="1">
			<thead><tr><th>Article</th><th>Langue</th><th>Dernier auteur</th><th>Dernière modif</th><th>État</th><th>Publiée</th></tr></thead>
			<tbody><?php
				$where = '';
				$req_args = array();
				if(isset($_GET['article_lang']) and $_GET['article_lang'] != '') {
					$where .= '`softwares_tr`.`lang`=? ';
					$req_args[] = $_GET['article_lang'];
				}
				if((isset($_GET['article_todo']) and $_GET['article_todo'] != '')) {
					if(!empty($where))
						$where .= 'AND ';
					$where .= '`softwares_tr`.`todo_level`=? ';
					$req_args[] = $_GET['article_todo'];
				}
				elseif(!isset($_GET['article_todo'])) {
					if(!empty($where))
						$where .= 'AND ';
					$where .= '`softwares_tr`.`todo_level`=? ';
					$req_args[] = 0;
				}
				$req = $bdd->prepare('SELECT `softwares_tr`.`id` AS `id`, `softwares_tr`.`sw_id` AS `sw_id`, `softwares_tr`.`lang` AS `lang`, `softwares_tr`.`date` AS `date`, `softwares_tr`.`author` AS `author`, `softwares_tr`.`published` AS `published`, `softwares_tr`.`todo_level` AS `todo_level`, `softwares`.`name` AS `name` FROM `softwares_tr` 
LEFT JOIN `softwares` ON `softwares`.`id`=`softwares_tr`.`sw_id`'.(empty($where) ? '':' WHERE '.$where).' 
ORDER BY `todo_level` DESC');
				$req->execute($req_args);
				while($data = $req->fetch()) {
					echo '<tr>
						<td><a href="translate.php?type=article&id='.$data['sw_id'].'">'.$data['name'].'</a></td>
						<td title="'.$data['lang'].'">'.$langs[$data['lang']].'</td>
						<td>'.htmlentities($data['author']).'</td>
						<td>'.date('d/m/Y H:i', $data['date']).'</td>
						<td class="tr_todo'.$data['todo_level'].'">'.$tr_todo[$data['todo_level']].'</td>
						<td class="tr_published'.$data['published'].'">'.($data['published']?'Public':'Privé').'</td>
					</tr>';
				} ?>
			</tbody>
		</table>
		
<?php /*
		<h2 id="trs-files">Texte du site</h2>
		<form action="translate_todo.php#trs-files" method="get">
			<fieldset>
				<legend>Trier les traductions du site</legend>
				<label for="f_sort_tr_lang">Langue</label>
				<select id="f_sort_tr_lang" name="tr_lang" autocomplete="off">
					<option value="">Tout</option>
					<?php echo isset($_GET['tr_lang']) ? langs_html_opts($_GET['tr_lang']) : $langs_html_opts; ?>
				</select><br>
				<label for="f_sort_tr_todo">État</label>
				<select id="f_sort_tr_todo" name="tr_todo" autocomplete="off">
					<option value="">Tout</option>
					<?php 
					$foo_todo = (isset($_GET['tr_todo']) and $_GET['tr_todo'] != '') ? intval($_GET['tr_todo']) : 0;
					foreach($tr_todo as $key => $val) {
						echo '<option value="'.$key.'"'.($foo_todo===$key ? ' selected':'').'>'.$val.'</option>';
					}
					?>
				</select><br>
				<input type="submit" value="Trier">
			</fieldset>
		</form>
		<table border="1">
			<thead><tr><th>Fichier</th><th>Langue</th><th>Dernier auteur</th><th>Dernière modif</th><th>État</th></tr></thead>
			<tbody><?php
				require_once($_SERVER['DOCUMENT_ROOT'].'/cache/langs_index.php');
				foreach($available_trs_index as $trsdir => $trsfiles) {
					if(isset($_GET['tr_lang']) and $_GET['tr_lang'] != '' and $_GET['tr_lang'] != $trsdir)
						continue;
					foreach($trsfiles as $trsfile => $data) {
						if((isset($_GET['tr_todo']) and $_GET['tr_todo'] != '' and $_GET['tr_todo'] != $data['todo_level']) or (!isset($_GET['tr_todo']) and $data['todo_level'] != 0))
							continue;
						echo '<tr>
							<td><a href="translate.php?type=trsfiles&trsfiles='.$trsfile.'">'.$trsfile.'</a></td>
							<td title="'.$trsdir.'">'.$langs[$trsdir].'</td>
							<td>'.htmlentities($data['last_author']).'</td>
							<td>'.date('d/m/Y H:i', $data['last_modif']).'</td>
							<td class="tr_todo'.$data['todo_level'].'">'.$tr_todo[$data['todo_level']].'</td>
						</tr>';
					}
				}
				?>
			</tbody>
		</table>
*/ ?>
	</body>
</html>