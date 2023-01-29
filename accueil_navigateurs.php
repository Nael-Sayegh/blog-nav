<?php
http_response_code(301);
header("Status: 301 Moved Permanently", false, 301);
header('Location: browser_homepage.php');
exit();
?>