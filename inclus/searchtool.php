<ul style="position:absolute; top:-999px; left:-9999px;">
<li><a href="#contenu" accesskey="C"><?php echo tr($tr0,'menu_ctnlink'); ?></a></li>
<li><a href="#menusite" accesskey="M"><?php echo tr($tr0,'footer_menulink'); ?></a></li>
<li><a href="#footer" accesskey="B"><?php echo tr($tr0,'menu_bottomlink'); ?></a></li>
<li hidden><a href="#searchtool" accesskey="r"><?php echo tr($tr0,'menu_searchlink'); ?></a></li>
</ul>
<div id="searchtool" role="search">
	<form action="/search.php" method="get" aria-label="<?php echo tr($tr0,'searchtool_label'); ?>">
	<label for="searchtool_text" style="position:absolute; top:-999px; left:-9999px;"><?php echo tr($tr0,'searchtool_text'); ?></label>
		<input id="searchtool_text" type="search" name="q" placeholder="<?php echo tr($tr0,'searchtool_text'); ?>" aria-label="<?php echo tr($tr0,'searchtool_text'); ?>"><br>
		<select id="searchtool_cat" title="<?php echo tr($tr0,'searchtool_cat'); ?>" name="c[]" aria-label="<?php echo tr($tr0,'searchtool_cat'); ?>" multiple="multiple" size="1"><option value="" selected><?php echo tr($tr0,'searchtool_all'); ?></option><?php include('cache/menu_search.html'); ?></select>
		<input id="searchtool_go" type="submit" value="<?php echo tr($tr0,'searchtool_label'); ?>">
	</form>
</div>
