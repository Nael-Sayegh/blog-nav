<?php
$url = 'https://discordapp.com/api/webhooks/713798453959852152/9cLXkapSBG5S6US_gysIZwZjjvDlZqBfISRnWA-wVJcSKWESlKoMtf5kfLyM6Sh-D0rV';
$data = array(
'content' => $nom." vient de publier ".$nomdusite." version ".substr($data['name'],1).". Retrouvez tous les détails sur : https://www.progaccess.net/u?id=".$data['id']",
'username' => 'ProgAccess',
/* 'avatar_url' => '',
'embeds' => array(
array(
'title' => "cette page", // Intitulé du lien
'url' => "https://www.progaccess.net/u?id=".$data['id'], // Adresse du lien
'description' => "Liste des changements de ".$nomdusite." ".substr($data['name'],1), // Texte affiché après le titre*/
/*'image' => array(
'url' => '', // (jaune) Adresse de l'image
'width' => 0, // Largeur de l'image
'height' => 0 // Hauteur de l'image
),
'thumbnail' => array(
'url' => '', // (vert) Adresse de l'image
'width' => 0, // Largeur de l'image
'height' => 0 // Hauteur de l'image
),
'author' => array(
'name' => '', // Nom de l'auteur
'url' => '', // Adresse de l'auteur
'icon_url' => '' // (bleu foncé) Avatar de l'ateur
),
'footer' => array(
'text' => '', // Texte à afficher
'icon_url' => '' // (bleu clair) URL de l'image
)*/
)
),
);
$context = array(
'http' => array(
'method' => 'POST',
'header' => "Content-type: application/json\r\n",
'content' => json_encode($data),
)
);
$context  = stream_context_create($context);
$result = @file_get_contents($url, false, $context);
if($result === false) {
return false;
}
return true;
?>