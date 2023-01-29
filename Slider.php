<?php
$req = $bdd->prepare('SELECT * FROM `slides` WHERE `lang` =? AND `published` =? ORDER BY `date` DESC');
$req->execute(array($lang,'1'));
echo '<ul>';
while($data = $req->fetch()) {
	echo str_replace('{{site}}', $site_name, '<li><details><summary><h3 style='.$data['title_style'].'>'.date('d/m/Y H:i', $data['date']).'&nbsp;: '.$data['title'].'</h3></summary><div id="contain1" style='.$contain_style.'>'.$data['contain'].'</div></details></li>');
}
echo '</ul>';
?>