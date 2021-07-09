<?php
$ulmenu = function() {
	global $lang, $args, $tr0; ?>
<ul class="ulmenu">
	<li><form method="get"><?php echo args_html_form($args); ?><select aria-label="<?php echo tr($tr0,'menu_changelang'); ?>" title="<?php echo tr($tr0,'menu_changelang'); ?>" name="lang" autocomplete="off"><?php echo langs_html_opts($lang); ?></select><input type="submit" value="OK" /></form></li>
	<li><a href="/"><?php echo tr($tr0,'menu_homepage'); ?></a></li>
	<li><a href="https://forum.progaccess.net"><?php echo tr($tr0,'menu_forum'); ?></a></li>
	<li class="menutitle"><?php echo tr($tr0,'menu_articles'); ?></li>
	<?php include($_SERVER['DOCUMENT_ROOT'].'/cache/menu_ulli.html'); ?>
	<li class="menutitle"><?php echo tr($tr0,'menu_news'); ?></li>
	<li><a href="/newsletter.php"><?php echo tr($tr0,'menu_nl'); ?></a></li>
	<li><a href="/journal_modif.xml"><?php echo tr($tr0,'menu_rss'); ?></a></li>
	<li><a href="/journal_modif.php"><?php echo tr($tr0,'menu_journal'); ?></a></li>
	<li class="menutitle"><?php echo tr($tr0,'menu_usefull'); ?></li>
	<li><a href="/param.php"><?php echo tr($tr0,'menu_sets'); ?></a></li>
	<li><a href="/gadgets.php"><?php echo tr($tr0,'menu_gadgets'); ?></a></li>
	<li><a href="/contact.php"><?php echo tr($tr0,'menu_infos'); ?></a></li>
	<li><a href="/contacter.php"><?php echo tr($tr0,'menu_contact'); ?></a></li>
	<li><a href="/confidentialite.php"><?php echo tr($tr0,'menu_privacy'); ?></a></li>
	<li class="menusep">&nbsp;</li>
</ul>
<?php }; ?>
<div id="nav" role="navigation" style="display: block;" onload="showjs('boutonjs')">
<h2 id="menusite"><?php echo tr($tr0,'menu_menutitle'); ?></h2>
<?php
if(isset($_COOKIE['menu']) && $_COOKIE['menu'] == '1') { ?>
<form method="get"><?php echo args_html_form($args); ?><select aria-label="<?php echo tr($tr0,'menu_changelang'); ?>" title="<?php echo tr($tr0,'menu_changelang'); ?>" name="lang" autocomplete="off"><?php echo langs_html_opts($lang); ?></select><input type="submit" value="OK" /></form>
<form method="get" action="/redirection_navigation.php">
<label for="menu_menu"><?php echo tr($tr0,'menu_linklistlabel'); ?></label>
<select name="d" id="menu_menu" onKeyPress="redirect(event,this);">
<option value="/"><?php echo tr($tr0,'menu_homepage'); ?></option>
<option value="https://forum.progaccess.net"><?php echo tr($tr0,'menu_forum'); ?></option>
<option disabled>── <?php echo tr($tr0,'menu_articles'); ?> ──</option>
<?php include($_SERVER['DOCUMENT_ROOT'].'/cache/menu_select.html'); ?>
<option disabled>── <?php echo tr($tr0,'menu_news'); ?> ──</option>
<option value="/newsletter.php"><?php echo tr($tr0,'menu_nl'); ?></option>
<option value="/journal_modif.xml"><?php echo tr($tr0,'menu_rss'); ?></option>
<option value="/journal_modif.php"><?php echo tr($tr0,'menu_journal'); ?></option>
<option disabled>── <?php echo tr($tr0,'menu_usefull'); ?> ──</option>
<option value="/param.php"><?php echo tr($tr0,'menu_sets'); ?></option>
<option value="/gadgets.php"><?php echo tr($tr0,'menu_gadgets'); ?></option>
<option value="/contact.php"><?php echo tr($tr0,'menu_infos'); ?></option>
<option value="/contacter.php"><?php echo tr($tr0,'menu_contact'); ?></option>
<option value="/confidentialite.php"><?php echo tr($tr0,'menu_privary'); ?></option>
</select>
<br />
<input type="submit" value="<?php echo tr($tr0,'menu_linklistlabelbutton'); ?>" />
</form>
<?php } else { ?>
<div id="boutonjs" style="display:none;">
<input type="button" onclick="rdisp('ulli_menu')" value="<?php echo tr($tr0,'menu_switchmenu'); ?>" />
<div id="ulli_menu" style="display: block;">
<?php $ulmenu(); ?>
</div>
</div>
<script>document.getElementById("boutonjs").style.display="block";
if(820 >= window.innerWidth) rdisp("ulli_menu");</script>
<noscript>
<details open>
<summary><?php echo tr($tr0,'menu_switchmenu'); ?></summary>
<div id="ulli_menu2" style="display: block;">
<?php $ulmenu(); ?>
</div>
</details>
</noscript>
<?php
}
unset($ulmenu);
?>
<a href="#hautpage" accesskey="h"><?php echo tr($tr0,'menu_toplink'); ?></a>
</div>