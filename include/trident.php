<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/include/consts.php');
http_response_code(503);
exit();
?>
<div id="ie" role="complementary">
<h2>Vous utilisez Internet Explorer ou un dérivé de ce navigateur&nbsp;:</h2>
<p>Nous n'assurons plus la compatibilité avec ce type de navigateurs sur <?php print $site_name; ?> car nous utilisons des composants propres aux dernières normes Web en vigueur.<br>
Veuillez utiliser un navigateur moderne pour accéder au site.</p>
</div>