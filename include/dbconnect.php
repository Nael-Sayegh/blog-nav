<?php

require_once 'config.local.php';

try
{
    $bdd = new PDO(DB_STRING, DB_USER, DB_PSW);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    print 'Erreur de connexion à la base de données';
    error_log('DB connect error: '.$e->getMessage());
}

// Connexion à la base de données nav pour les tables d'authentification
try
{
    $nav_bdd = new PDO('pgsql:host=pgsql;dbname=nav;', DB_USER, DB_PSW);
    $nav_bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    print 'Erreur de connexion à la base de données nav';
    error_log('Nav DB connect error: '.$e->getMessage());
}
