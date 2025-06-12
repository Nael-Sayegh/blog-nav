<?php

if ($_SERVER['PHP_SELF'] !== '/Slider.php')
{
    $SQL = <<<SQL
        SELECT * FROM slides WHERE lang=:lng AND published=true ORDER BY date DESC
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':lng' => $lang]);
    echo '<ul>';
    while ($data = $req->fetch())
    {
        echo str_replace('{{site}}', $site_name, '<li><details><summary><h3 style='.$data['title_style'].'>'.date('d/m/Y H:i', $data['date']).'&nbsp;: '.$data['title'].'</h3></summary><div id="contain1" style='.$data['contain_style'].'>'.$data['contain'].'</div></details></li>');
    }
    echo '</ul>';
}
else
{
    http_response_code(404);
    header('Location: /');
    exit();
}
