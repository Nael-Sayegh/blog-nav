<?php
require_once('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang, 'browser_homepage');
$title = tr($tr,'title');
$sound_path = '/audio/page_sounds/homepage.mp3';
$log = '';
if(isset($_GET['act']) and $_GET['act'] == 'form') {
	if(isset($_POST['moteur']) and in_array($_POST['moteur'], array('ddg','ecos','bing','qwant','ixquick','goog','ask','yahoo','aol','millionshort','pa'))) {
		setcookie('moteur', $_POST['moteur'], time()+31536000, null, null, false, true);
		if(isset($logged) and $logged and isset($settings) and isset($login)) {
			$settings['startengine'] = $_POST['moteur'];
			$req = $bdd->prepare('UPDATE `accounts` SET `settings`=? WHERE `id`=? LIMIT 1');
			$req->execute(array(json_encode($settings), $login['id']));
		}
	}
	header('Location: /browser_homepage.php');
}

if(isset($_GET['mot']) and in_array($_GET['mot'], array('ddg','ecos','bing','qwant','ixquick','goog','ask','yahoo','aol','millionshort','pa'))) {
setcookie('moteur', $_GET['mot'], time()+31536000, null, null, false, true);
if(isset($logged) and $logged and isset($settings) and isset($login)) {
$settings['startengine'] = $_GET['mot'];
$req = $bdd->prepare('UPDATE `accounts` SET `settings`=? WHERE `id`=? LIMIT 1');
$req->execute(array(json_encode($settings), $login['id']));
}
header('Location: /browser_homepage.php');
}

if(isset($_GET['act']) and $_GET['act'] == 'ok')
	$log = 'ok';
$stats_page = 'start';
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<p><?php print tr($tr,'text_up'); ?><br><a href="<?php echo SITE_URL; ?>/browser_homepage.php"><?php echo SITE_URL; ?>/browser_homepage.php</a>.</p>
<p><?php print tr($tr,'search_change'); ?></p>
<ul>
<li>ddg&nbsp;: Duckduckgo</li>
<li>ecos&nbsp;: Écosia</li>
<li>bing&nbsp;: Microsoft Bing</li>
<li>qwant&nbsp;: Qwant</li>
<li>ixquick&nbsp;: Ixquick</li>
<li>goog&nbsp;: Google</li>
<li>ask&nbsp;: Ask</li>
<li>yahoo&nbsp;: Yahoo Search</li>
<li>aol&nbsp;: AOL Search</li>
<li>millionshort&nbsp;: MillionShort</li>
</ul>
<form action="?act=form" method="post">
<?php
$moteur = isset($_COOKIE['moteur']) ? $_COOKIE['moteur'] : 'ddg';
?>
<label for="choix_moteur"><?php print tr($tr,'motor_choice'); ?></label>
<select name="moteur" id="choix_moteur">
<option value="ddg" id="duck" <?php if($moteur == 'ddg') echo 'selected'; ?>>Duckduckgo (par défaut)</option>
<option value="ecos" id="ec" <?php if($moteur == 'ecos') echo 'selected'; ?>>Écosia</option>
<option value="bing" id="msbing" <?php if($moteur == 'bing') echo 'selected'; ?>>Microsoft Bing</option>
<option value="qwant" id="frqwant" <?php if($moteur == 'qwant') echo 'selected'; ?>>Qwant</option>
<option value="ixquick" id="spixquick" <?php if($moteur == 'ixquick') echo 'selected'; ?>>Ixquick</option>
<option value="goog" id="google" <?php if($moteur == 'goog') echo 'selected'; ?>>Google</option>
<option value="ask" id="frask" <?php if($moteur == 'ask') echo 'selected'; ?>>Ask</option>
<option value="yahoo" id="fryahoo" <?php if($moteur == 'yahoo') echo 'selected'; ?>>Yahoo Search</option>
<option value="aol" id="fraol" <?php if($moteur == 'aol') echo 'selected'; ?>>AOL Search</option>
<option value="millionshort" id="mshort" <?php if($moteur == 'millionshort') echo 'selected'; ?>>Million Short</option>
</select>
<p><?php print tr($tr,'text_down'); ?></p>
<input type="submit" value="<?php print tr($tr,'buton_confirm'); ?>">
</form>
<div id="wrap">
<div id="form" role="search">
<?php
if($moteur == 'ddg')
echo '<form action="https://duckduckgo.com" method="get">
<label for="searchinput">'.tr($tr,'text_search').' Duckduckgo</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>';
else if($moteur == 'ecos')
echo '<form action="https://www.ecosia.org/search" method="get">
<label for="searchinput">'.tr($tr,'text_search').' Écosia</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>';
else if($moteur == 'bing')
echo '<form action="https://www.bing.com/search" method="get">
<label for="searchinput">'.tr($tr,'text_search').' Microsoft Bing</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>';
else if($moteur == 'qwant')
echo '<form action="https://www.qwant.com" method="get">
<label for="searchinput">'.tr($tr,'text_search').' Qwant</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>';
else if($moteur == 'ixquick')
echo '<form action="https://www.ixquick.com/do/search" method="get">
<label for="searchinput">'.tr($tr,'text_search').' Ixquick</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>';
else if($moteur == 'goog')
echo '<form action="https://www.google.fr/search" method="get">
<label for="searchinput">'.tr($tr,'text_search').' Google</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>';
else if($moteur == 'ask')
echo '<form action="https://fr.ask.com/web" method="get">
<label for="searchinput">'.tr($tr,'text_search').' Ask</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>';
else if($moteur == 'yahoo')
echo '<form action="https://fr.search.yahoo.com/search" method="get">
<label for="searchinput">'.tr($tr,'text_search').' Yahoo Search</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>';
else if($moteur == 'aol')
echo '<form action="https://recherche.aol.fr/aol/search" method="get">
<label for="searchinput">'.tr($tr,'text_search').' AOL Search</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>';
else if($moteur == 'millionshort')
echo '<form action="https://millionshort.com/search" method="get">
<label for="searchinput">'.tr($tr,'text_search').' Million Short</label>
<input type="search" name="q" id="searchinput" style="width: 255px\;" maxlength="255" autofocus><br>
<input type="submit" value="'.tr($tr,'buton_search').'"><br>
</form>'; ?>
</div>
</div>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>