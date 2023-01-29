<?php
require_once 'config.local.php';

date_default_timezone_set('Europe/Paris'); 
setlocale(LC_TIME,'fr_FR.UTF8');
try {
	$bdd = new PDO(DB_STRING, DB_USER, DB_PSW);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	print 'Erreur de connexion à la base de données';
	error_log('DB connect error: '.$e->getMessage());
}
?>
