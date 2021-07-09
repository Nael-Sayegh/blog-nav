<?php
if(isset($_COOKIE['audio']) and ($_COOKIE['audio'] == '10' or preg_match('#[1-9]#', $_COOKIE['audio']))) {
	echo '<audio id="007t007x" src="'.$cheminaudio.'" autoplay></audio>';
	echo '<script type="text/javascript">document.getElementById("007t007x").volume = ';
	if($_COOKIE['audio'] == '10') echo '1';
	else echo '0.'.$_COOKIE['audio'];
	echo ';</script>';
} ?>