<?php
date_default_timezone_set('Europe/Paris'); 
setlocale(LC_TIME,'fr_FR.UTF8');
try {$bdd = new PDO('mysql:host=localhost:3306;dbname=NVDAFR;charset=utf8mb4', 'nvdafr1', 'Urxx38?1');
array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'");}
catch(Exception $e) {die('Error !');}
try {$bdd2 = new PDO('mysql:host=localhost:3306;dbname=apfr;charset=utf8mb4', 'uap', 'Urxx38?1');
array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'");}
catch(Exception $e) {die('Error !');}
?>