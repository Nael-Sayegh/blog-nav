<?php

/*
* Si le fichier où l'on stock,
* les données n'existe pas encore
* on le crée.
*/

$year = date("y");

$fichier = $_SERVER['DOCUMENT_ROOT'].'/.htcompteur';
$fichier = $fichier . $year;

if( !file_exists($fichier) ) {
  $fp = fopen($fichier, "w");
  fwrite($fp, serialize(array()));
  fclose($fp);
}

/*
* Définition de variables
* nécessaire au compteur :
* - deux termes constants,
* - l'ip du visiteur,
* - la date et l'heure.
*/

$argument_visites = 'visites';
$argument_requetes = 'requêtes';
$ip = $_SERVER['REMOTE_ADDR'];
$time = date('YmdGis');

/*
* Récupération des données du
* compteur précédemment stockées.
*/

$lignes = file($fichier) or die('Un problème est survenu, le compteur ne peut pas s\'afficher');
$donnees = unserialize($lignes[0]);

/*
* Pour chaque clé du tableau de données
* qui ne soit pas attribuée aux visites et aux requêtes
* si la valeur correspond à une date antérieure
* au même jour, on supprime l'ip du visiteur.
*/

foreach( $donnees as $cle => $valeur )
{
  if( substr($valeur, 0, 8) != substr($time, 0, 8) &&
  $cle != $argument_visites &&
  $cle != $argument_requetes ) {
    unset($donnees[$cle]);
  }
}

/*
* On incrémente ( ajoute +1 ) la valeur
* du nombre de requêtes.
* Si l'ip n'est pas encore enregistrée,
* on incrémente la valeur du nombre de visites
* et on ajoute l'ip dans le tableau accompagné
* de la date et de l'heure de l'exécution.
*/

$donnees[$argument_requetes]++;
if( !$donnees[$ip] ) {
  $donnees[$argument_visites]++;
  $donnees[$ip] = $time;
}

/*
* On effectue un petit report de variable
* pour une utilisation ultérieur plus aisée.
*/

$nb_visiteurs = $donnees[$argument_visites];
$nb_aujourdhui = count($donnees)-2;
$nb_requetes = $donnees[$argument_requetes];

/*
* On stock le tableau dans le fichier de données
* en écrasant sa valeur précédente.
*/

$fp = fopen($fichier,"wb");
fwrite($fp, serialize($donnees));
/*
* On affiche les résultats du compteur.
*/
if ($nb_visiteurs < '2') {
print $nb_visiteurs. " visiteur depuis le 01/01/20";
}
else {
print $nb_visiteurs. " visiteurs depuis le 01/01/20";
}
print $year." dont ";
print $nb_aujourdhui. " aujourd'hui, ";
if ($nb_requetes < '2') {
print $nb_requetes. " page chargée.<br />";
}
else {
print $nb_requetes. " pages chargées ";
} ?>