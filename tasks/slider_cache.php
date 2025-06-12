<?php

$document_root = __DIR__.'/..';
require_once($document_root.'/include/consts.php');

foreach ($langs_prio as &$lang_i)
{
    $tr = load_tr($lang_i, 'slider');

    $file = fopen($document_root.'/cache/slider_'.$lang_i.'.html', 'w');
    fwrite($file, '<div id="debutslide" role="complementary" aria-label="'.tr($tr, 'label').'"><div id="slider" style="display:none;"><div id="slidershow" aria-live="assertive">');

    $slides = 0;
    $SQL = <<<SQL
        SELECT * FROM slides WHERE lang=:lng AND published=true
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':lng' => $lang_i]);

    while ($data = $req->fetch())
    {
        $slides++;
        fwrite($file, '<div id="slide'.strval($slides).'" class="slide');
        if ($slides === 1)
        {
            fwrite($file, ' activeslide');
        }
        else
        {
            fwrite($file, ' noslide');
        }
        fwrite($file, '" style="'.$data['style'].'"><h2 style="'.$data['title_style'].'">'.str_replace('{{site}}', $site_name, $data['title']).'</h2><div class="slidec" style="'.$data['contain_style'].'">'.str_replace('{{site}}', $site_name, $data['contain']).'</div></div>');
    }

    # ---
    fwrite($file, '<a onclick="clickprev()" id="slideprev" class="slidebt" title="'.tr($tr, 'previous').'"><img alt="'.tr($tr, 'previous').'" src="/images/slide_left_arrow.png"></a><a onclick="clickpause()" id="slidepause" class="slidebt" title="'.tr($tr, 'stop').'"><img alt="'.tr($tr, 'stop').'" src="/images/slide_pause.png"></a><a onclick="clicknext()" id="slidenext" class="slidebt" title="'.tr($tr, 'next').'"><img alt="'.tr($tr, 'next').'" src="/images/slide_right_arrow.png"></a></div></div><script>var slides = '.strval($slides).';</script></div>');
    fclose($file);
}
