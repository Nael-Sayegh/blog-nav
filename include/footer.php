<?php
$permalink = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$titlemodifie = str_replace(' ', ' ', $title);
?>
<footer id="footer">
<a class="sr_only" href="#hautpage" accesskey="H"><?= tr($tr0, 'footer_toplink') ?></a>
<div id="social_share" role="complementary">
<details open>
<summary><?= tr($tr0, 'footer_share') ?></summary>
<ul>
<li><a href="https://www.facebook.com/sharer.php?u=<?php print $permalink; ?>&t=<?php print $titlemodifie; ?>" target="_blank" title="<?= tr($tr0, 'footer_fb') ?>"><img src="/images/facebook.png" alt="<?= tr($tr0, 'footer_fb') ?>"></a></li>
<li><a href="https://x.com/share?url=<?php print $permalink; ?>&text=<?php print $titlemodifie; ?>&via=<?php print $site_name; ?>" target="_blank" title="<?= tr($tr0, 'footer_x') ?>"><img src="/images/x.png" alt="<?= tr($tr0, 'footer_x') ?>"></a></li>
<li><button class="mastodon-share" data-title="<?php print $titlemodifie; ?>" data-href="<?php print $permalink; ?>" role="link"></button></li>
</ul>
</details>
</div>
<?php
include('include/stats.php');
if ((defined('FB_URL') && constant('FB_URL')) || (defined('MASTO_URL') && constant('MASTO_URL')) || (defined('CESIUM_URL') && constant('CESIUM_URL'))): ?>
<details open>
<summary><?= tr($tr0, 'footer_sociallinks') ?></summary>
<?php if (defined('FB_URL') && ($fbUrl = constant('FB_URL')))
{ ?>
<a target="_blank" href="<?= $fbUrl ?>" title="<?= tr($tr0, 'footer_link_fb', ['site' => $site_name]) ?>"><img id="facebook" alt="<?= tr($tr0, 'footer_link_fb', ['site' => $site_name]) ?>" src="/images/facebook.png"></a>
<?php }
if (defined('MASTO_URL') && ($mastoUrl = constant('MASTO_URL')))
{ ?>
<a target="_blank" rel="me" href="<?= $mastoUrl ?>" title="<?= tr($tr0, 'footer_link_masto', ['site' => $site_name]) ?>"><img id="mastodon" alt="<?= tr($tr0, 'footer_link_masto', ['site' => $site_name]) ?>" src="/images/mastodon-purple.svg" style="width:32px;height:32px;"></a>
<?php }
if (defined('DISCORD_URL') && ($discordUrl = constant('DISCORD_URL')))
{ ?>
<a target="_blank" href="<?= $discordUrl ?>" title="<?= tr($tr0, 'footer_link_discord', ['site' => $site_name]) ?>"><img id="discord" alt="<?= tr($tr0, 'footer_link_discord', ['site' => $site_name]) ?>" src="/images/discord.svg" style="width:32px;height:32px;"></a><br>
<?php }
if (defined('CESIUM_URL') && ($cesiumUrl = constant('CESIUM_URL')))
{ ?>
<a target="_blank" href="<?= $cesiumUrl ?>" title="<?= tr($tr0, 'footer_link_g1', ['site' => $site_name]) ?>"><img id="g1" alt="<?= tr($tr0, 'footer_link_g1', ['site' => $site_name]) ?>" src="/images/gbreve-simple.svg" style="width:32px;height:32px;"></a>
<?php } ?>
</details>
<?php endif; ?>
Copyleft 2015-<?php print date('Y'); ?> <?= tr($tr0, 'footer_copyright', ['site' => $site_name]) ?><br>
<?= tr($tr0, 'footer_license', ['site' => $site_name,'license' => '<a href="https://www.gnu.org/licenses/licenses.html#AGPL" title="GNU Affero General Public License v3">GNU AGPL v3</a>','trlicense' => '<a href="http://creativecommons.org/licenses/by-sa/4.0/" title="Creative Commons Attribution-ShareAlike 4.0 International License">CC BY-SA 4.0</a>']) ?><br>
<p><?php getContentLastModif(); ?><br>
<?php getVersionFromGit(); ?></p>
</footer>