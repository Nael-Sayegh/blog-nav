<header id="hautpage">
<h1><a href="/" title="<?php echo tr($tr0,'banner_homelink'); ?>"><?php print $site_name; ?></a></h1>
<dialog open>
<p><?php echo $site_name; ?> aura 10 ans demain. Pour l'occasion, un évènement est organisé sur <a href="a189">TeamTalk</a> à 15h. Cet évènement spécial a pour but de retracer l'histoire du site, de discuter de son avenir, de partager les éventuelles anecdotes de tout un chacun sur le projet, et de lancer en direct la mise à jour anniversaire des 10 ans.<br>
Pour participer, rien de plus simple, installez l'application TeamTalk puis utilisez le lien suivant&nbsp;: <a href="tt://cobc.me?tcpport=10333&udpport=10333&encrypted=1&channel=%2F">tt://cobc.me?tcpport=10333&udpport=10333&encrypted=1&channel=%2F</a>.</p>
</dialog>
<script>
function showModal() { 
      const dialog = document.querySelector('dialog'); 
      dialog.showModal(); 
}
showModal();
</script>
<?php
if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== FALSE) include 'include/trident.php';
include 'include/loginbox.php';
include 'include/searchtool.php'; ?>
</header>
<?php include 'include/menu.php'; ?>