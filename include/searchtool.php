<?php
$cats = get_categories();
?>
<ul class="sr_only">
<li><a href="#contenu" accesskey="C"><?= tr($tr0, 'menu_ctnlink') ?></a></li>
<li><a href="#menusite" accesskey="M"><?= tr($tr0, 'footer_menulink') ?></a></li>
<li><a href="#footer" accesskey="B"><?= tr($tr0, 'menu_bottomlink') ?></a></li>
<li hidden><a href="#searchtool" accesskey="r"><?= tr($tr0, 'menu_searchlink') ?></a></li>
</ul>
<div id="searchtool" role="search">
<form action="/search.php" method="get" aria-label="<?= tr($tr0, 'searchtool_label') ?>">
<input id="searchtool_text" type="search" name="q" placeholder="<?= tr($tr0, 'searchtool_text') ?>" aria-label="<?= tr($tr0, 'searchtool_text') ?>"><br>
<select id="searchtool_cat" title="<?= tr($tr0, 'searchtool_cat') ?>" name="c[]" aria-label="<?= tr($tr0, 'searchtool_cat') ?>" multiple="multiple" size="1"><option value="" selected><?= tr($tr0, 'searchtool_all') ?></option>
<?php foreach ($cats as $cat): ?>
<option value="<?= (int)$cat['id'] ?>" title="<?= htmlspecialchars((string) $cat['title']) ?>"><?= htmlspecialchars((string) $cat['name']) ?></option>
<?php endforeach; ?>
</select>
<input id="searchtool_go" type="submit" value="<?= tr($tr0, 'searchtool_label') ?>">
</form>
</div>
