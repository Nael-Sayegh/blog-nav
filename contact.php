<?php set_include_path($_SERVER['DOCUMENT_ROOT']);
require('include/log.php');
require_once('include/consts.php');
$tr = load_tr($lang, 'contact');
$title = tr($tr, 'title');
$sound_path = '/audio/page_sounds/contact.mp3';
$stats_page = 'contact'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?php
$teamlist = '';
$SQL = <<<SQL
    SELECT * FROM team WHERE works = '1' OR works = '2'
    SQL;
foreach ($bdd->query($SQL) as $data)
{
    $teamlist .= '<li style="margin-top:8px;margin-bottom:8px;"><span class="rk rk_a">'.$data['name'].'</span>, '.$data['status'].'<br><span style="color:#047d04;padding-left:16px;">'.tr($tr, 'teamlist_item', ['age' => intval((time() - $data['age']) / 31557600), 'date' => date('d/m/Y', $data['date'])]).'</span><p style="margin-top:0;padding-left:16px;color:#202000;">'.$data['bio'].'</p></li>';
}
echo tr($tr, 'maintext', ['teamlist' => $teamlist,'lastv' => $versionName,'lastvdate' => $versionDate,'lastvid' => $lastVersion,'lastvopensource' => $lastosv,'lastvu' => $versionId]);
?>
</main>
<?php require_once('include/footer.php'); ?>
</body>
</html>