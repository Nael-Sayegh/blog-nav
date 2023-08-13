<?php
$permalink = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$titlemodifie = str_replace(' ',' ',$title);
?>
<footer id="footer">
<a style="position:absolute; top:-999px; left:-9999px;" href="#hautpage" accesskey="H"><?php echo tr($tr0,'footer_toplink'); ?></a>
<div id="social_share" role="complementary">
<details open>
<summary><?php echo tr($tr0,'footer_share'); ?></summary>
<ul>
<li><a href="https://www.facebook.com/sharer.php?u=<?php print $permalink; ?>&t=<?php print $titlemodifie; ?>" target="_blank" title="<?php echo tr($tr0,'footer_fb'); ?>"><img src="/image/facebook.png" alt="<?php echo tr($tr0,'footer_fb'); ?>"></a></li>
<li><a href="https://twitter.com/share?url=<?php print $permalink; ?>&text=<?php print $titlemodifie; ?>&via=<?php print $site_name; ?>" target="_blank" title="<?php echo tr($tr0,'footer_tw'); ?>"><img src="/image/twitter.png" alt="<?php echo tr($tr0,'footer_tw'); ?>"></a></li>
<li><a href="/Diaspora/selectpod.php?url=<?php print $permalink; ?>&title=<?php print $titlemodifie; ?>" target="_blank" title="<?php echo tr($tr0,'footer_d*'); ?>"><img src="/image/diaspora_white.svg" alt="<?php echo tr($tr0,'footer_d*'); ?>" style="width:32px;height:32px;"></a></li>
<li><div class="mastodon-share-button" data-target="<?php print $permalink; ?>" data-name="<?php print $titlemodifie; ?>" data-buttonstyle="btn btn-secondary" data-text="<?php echo tr($tr0,'footer_mastodon'); ?>"></div></li>
</ul>
</details>
</div>
<?php
include('include/stats.php'); ?>
<details open>
<summary><?php echo tr($tr0,'footer_sociallinks'); ?></summary>
<a target="_blank" href="<?php echo CESIUM_URL; ?>" title="<?php echo tr($tr0,'footer_link_g1'); ?>"><img id="g1" alt="<?php echo tr($tr0,'footer_link_g1'); ?>" src="/image/gbreve-simple.svg" style="width:32px;height:32px;"></a>
<a target="_blank" href="<?php echo FB_URL; ?>" title="<?php echo tr($tr0,'footer_link_fb'); ?>"><img id="facebook" alt="<?php echo tr($tr0,'footer_link_fb'); ?>" src="/image/facebook.png"></a>
<a target="_blank" rel="me" href="<?php echo MASTO_URL; ?>" title="<?php echo tr($tr0,'footer_link_masto'); ?>"><img id="mastodon" alt="<?php echo tr($tr0,'footer_link_masto'); ?>" src="/image/mastodon-purple.svg" style="width:32px;height:32px;"></a>
</details>
Copyleft 2015-<?php print date('Y'); ?> <?php echo tr($tr0,'footer_copyright',array('site'=>$site_name)); ?><br>
<?php echo tr($tr0,'footer_license',array('{{site}}'=>$site_name,'license'=>'<a href="https://www.gnu.org/licenses/licenses.html#AGPL" title="GNU Affero General Public License v3">GNU AGPL v3</a>','trlicense'=>'<a href="http://creativecommons.org/licenses/by-sa/4.0/" title="Creative Commons Attribution-ShareAlike 4.0 International License">CC BY-SA 4.0</a>')); ?><br>
<?php getLastGitCommit(); ?>
<?php if((isset($_COOKIE['date']) and $_COOKIE['date'] == '1')) { ?>
<noscript>
<?php echo tr($tr0,'date',array('date'=>getFormattedDate(time(), tr($tr0,'ftdate')),'time'=>getFormattedDate(time(), tr($tr0,'ftime')))); ?>
</noscript>
<div style="display:none;" id="ag003030">
<span id="date_heure"></span>
</div>
<script>document.getElementById("ag003030").style.display="block";date_heure("date_heure");</script>
<?php } ?>
</footer>
<script src="include/lib/mastodon-share-button/dist/mastodon.js"></script>