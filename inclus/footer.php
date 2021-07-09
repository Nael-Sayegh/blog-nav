<?php
$permalink = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$titremodifie = str_replace(' ',' ',$titre);
?>
<div id="footer" role="contentinfo">
<a style="position:absolute; top:-999px; left:-9999px;" href="#hautpage" accesskey="H"><?php echo tr($tr0,'footer_toplink'); ?></a>
<div id="social_share" role="complementary">
<details open>
<summary><?php echo tr($tr0,'footer_share'); ?></summary>
<ul>
<li><a href="https://www.facebook.com/sharer.php?u=<?php print $permalink; ?>&t=<?php print $titremodifie; ?>" target="_blank" title="<?php echo tr($tr0,'footer_fb'); ?>"><img src="/image/facebook.png" alt="<?php echo tr($tr0,'footer_fb'); ?>" /></a></li>
<li><a href="https://twitter.com/share?url=<?php print $permalink; ?>&text=<?php print $titremodifie; ?>&via=<?php print $nomdusite; ?>" target="_blank" title="<?php echo tr($tr0,'footer_tw'); ?>"><img src="/image/twitter.png" alt="<?php echo tr($tr0,'footer_tw'); ?>" /></a></li>
<li><a href="/Diaspora/selectpod.php?url=<?php print $permalink; ?>&title=<?php print $titremodifie; ?>" target="_blank" title="<?php echo tr($tr0,'footer_d*'); ?>"><img src="/image/diaspora_white.svg" alt="<?php echo tr($tr0,'footer_d*'); ?>" style="width:32px;height:32px;" /></a></li>
</ul>
</details>
</div>
<?php
include('inclus/stats.php'); ?>
<details open>
<summary><?php echo tr($tr0,'footer_sociallinks'); ?></summary>
<a target="_blank" href="https://demo.cesium.app/#/app/wot/EEGevmgQcgzXou2ucaf1S9pCMvwKfu56ukRRLPn4D3y9/" title="<?php echo tr($tr0,'footer_link_g1'); ?>"><img id="g1" alt="<?php echo tr($tr0,'footer_link_g1'); ?>" src="/image/gbreve-simple.svg" style="width:32px;height:32px;" /></a>
<a target="_blank" href="https://framasphere.org/people/f5342a7058e60136500c2a0000053625" title="<?php echo tr($tr0,'footer_link_d*'); ?>"><img id="diaspora" alt="<?php echo tr($tr0,'footer_link_d*'); ?>" src="/image/diaspora_white.svg" style="width:32px;height:32px;" /></a>
<a target="_blank" href="https://www.facebook.com/ProgAccess" title="<?php echo tr($tr0,'footer_link_fb'); ?>"><img id="facebook" alt="<?php echo tr($tr0,'footer_link_fb'); ?>" src="/image/facebook.png" /></a>
<a target="_blank" href="https://twitter.com/ProgAccess" title="<?php echo tr($tr0,'footer_link_tw'); ?>"><img id="twitter" alt="<?php echo tr($tr0,'footer_link_tw'); ?>" src="/image/twitter.png" /></a>
</details>
Copyright &copy; 2015 - <?php print date('Y'); ?> (<?php echo tr($tr0,'footer_copyright',array('site'=>$nomdusite)); ?>)<br />
<?php echo tr($tr0,'footer_license',array('{{site}}'=>$nomdusite,'license'=>'<a href="https://www.gnu.org/licenses/licenses.html#AGPL" title="GNU Affero General Public License v3">GNU AGPL v3</a>','trlicense'=>'<a href="http://creativecommons.org/licenses/by-sa/4.0/" title="Creative Commons Attribution-ShareAlike 4.0 International License">CC BY-SA 4.0</a>')); ?><br />
<?php if(DEV) echo '<a href="https://www.progaccess.net/">'.tr($tr0,'footer_stablelink').'</a>';
else echo '<a href="https://dev.progaccess.net/">'.tr($tr0,'footer_devlink').'</a>'; ?>
<?php if((isset($_COOKIE['date']) and $_COOKIE['date'] == '1')) { ?>
<noscript>
<?php echo tr($tr0,'date',array('date'=>strftime('%A %e %B %Y'),'time'=>strftime('%k:%M:%S'))); ?>
<br />
</noscript>
<div style="display:none;" id="ag003030">
<span id="date_heure"></span>
</div>
<script>document.getElementById("ag003030").style.display="block";date_heure("date_heure");</script>
<?php } ?>
</div>
