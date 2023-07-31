<?php
$document_root = __DIR__.'/..';
require_once($document_root.'/include/consts.php');

foreach($langs_prio as &$lang_i) {
	$tr = load_tr($lang_i, 'slider');
	
	$file = fopen($document_root.'/cache/slider_'.$lang_i.'.html', 'w');
	fwrite($file, '<div id="debutslide" role="complementary" aria-label="'.tr($tr,'label').'"><div id="slider" style="display:none;"><div id="slidershow" aria-live="assertive">');

	$slides = 0;

	$req = $bdd->prepare('SELECT * FROM `slides` WHERE `lang`=? AND `published`=1');
	$req->execute(array($lang_i));

	while($data = $req->fetch()) {
		$slides ++;
		fwrite($file, '<div id="slide'.strval($slides).'" class="slide');
		if($slides == 1)
			fwrite($file, ' activeslide');
		else
			fwrite($file, ' noslide');
		fwrite($file, '" style="'.$data['style'].'"><h2 style="'.$data['title_style'].'">'.str_replace('{{site}}', $site_name, $data['title']).'</h2><div class="slidec" style="'.$data['contain_style'].'">'.str_replace('{{site}}', $site_name, $data['contain']).'</div></div>');
	}
	
	# --- most visited
	$slides ++;
	fwrite($file, '<div id="slide'.strval($slides).'" class="slide');
	if($slides == 1)
		fwrite($file, ' activeslide');
	else
		fwrite($file, ' noslide');
	fwrite($file, '" style="background-color:#fecc81;"><h2 style="color:#009d88;">'.tr($tr,'most_visited').'</h2><div class="slidec" style=""><ul>');
	$req = $bdd->prepare('SELECT `id`,`hits` FROM `softwares` ORDER BY `hits` DESC LIMIT 5');
	$req->execute();
	while($data = $req->fetch()) {
		$article_trs = get_article_prefered_tr($data['id'], $lang_i);
		$article_tr = $article_trs['trs'][$article_trs['prefered_tr']];
		fwrite($file, '<li><a href="/a'.$data['id'].'">'.str_replace('{{site}}', $site_name, $article_tr['title']).'</a> <span style="color:#04b404;">('.$data['hits'].')</span></li>');
	}
	fwrite($file, '</ul></div></div>');

	# --- last site update
	$slides ++;
	fwrite($file, '<div id="slide'.strval($slides).'" class="slide noslide" style="background-color: paleturquoise;"><h2 style="color: midnightblue;">'.tr($tr,'last_site_update').'</h2><div class="slidec">');
	$req = $bdd->prepare('SELECT * FROM `site_updates` ORDER BY `date` DESC LIMIT 1');
	$req->execute();
	while($data = $req->fetch()) {
		fwrite($file, '<p>'.tr($tr,'last_site_update_text', array('version'=>substr($data['name'],1), 'id'=>$data['id'], 'date'=>getFormattedDate(tr($data['date'], tr($tr0,'fndatetime')), 'link1'=>'<a href="/u'.$data['id'].'">', 'link2'=>'</a>')).'</p>');
		//fwrite($file, '<p>La version '.substr($data['name'],1).' (V'.$data['id'].') du site est sortie le '.date('d/m/Y',$data['date']).' à '.date('H:i',$data['date']).'&nbsp;:<br><a href="/u'.$data['id'].'">consultez ses changements</a>.</p>');
	}
	fwrite($file, '</div></div>');

	# --- last updates
	$slides ++;
	fwrite($file, '<div id="slide'.strval($slides).'" class="slide noslide" style="background-color: paleturquoise;"><h2 style="color: midnightblue;">'.tr($tr,'last_updates').'</h2><div class="slidec"><ul>');
	$req = $bdd->prepare('SELECT `id`,`author`,`date` FROM `softwares` ORDER BY `date` DESC LIMIT 5');
	$req->execute();
	while($data = $req->fetch()) {
		$article_trs = get_article_prefered_tr($data['id'], $lang_i);
		$article_tr = $article_trs['trs'][$article_trs['prefered_tr']];
		fwrite($file, '<li><a href="/a'.$data['id'].'">'.str_replace('{{site}}', $site_name, $article_tr['title']).'</a> ('.tr($tr,'last_updates_text',array('author'=>$data['author'], 'date'=>getFormattedDate($data['date'], tr($tr0,'fndatetime')))).')</li>');
	}
	fwrite($file, '</ul></div></div>');

	# --- most downloaded
	$slides ++;
	fwrite($file, '<div id="slide'.strval($slides).'" class="slide noslide" style="background-color:#fecc81;"><h2 style="color:#009d88;">'.tr($tr,'most_downloaded').'</h2><div class="slidec" style=""><ul>');
	$req = $bdd->prepare('SELECT `id`,`downloads` FROM softwares ORDER BY downloads DESC LIMIT 5');
	$req->execute();
	while($data = $req->fetch()) {
		$article_trs = get_article_prefered_tr($data['id'], $lang_i);
		$article_tr = $article_trs['trs'][$article_trs['prefered_tr']];
		fwrite($file, '<li><a href="/a'.$data['id'].'">'.str_replace('{{site}}', $site_name, $article_tr['title']).'</a> <span style="color:#04b404;">('.$data['downloads'].')</span></li>');
	}
	fwrite($file, '</ul></div></div>');

	# ---
	fwrite($file, '<a onclick="clickprev()" id="slideprev" class="slidebt" title="'.tr($tr,'previous').'"><img alt="'.tr($tr,'previous').'" src="/image/slide_left_arrow.png"></a><a onclick="clickpause()" id="slidepause" class="slidebt" title="'.tr($tr,'stop').'"><img alt="'.tr($tr,'stop').'" src="/image/slide_pause.png"></a><a onclick="clicknext()" id="slidenext" class="slidebt" title="'.tr($tr,'next').'"><img alt="'.tr($tr,'next').'" src="/image/slide_right_arrow.png"></a></div></div><script>var slides = '.strval($slides).';</script></div>');
	fclose($file);
}
?>
