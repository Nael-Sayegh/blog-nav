<?php

require_once 'api_inc.php';

$rdata = ['api_version' => $api_version];

if (isset($_GET['g']))
{
    $domain = '';
    if (!isset($stats_page))
    {
        $stats_page = '';
    }
    if (isDev())
    {
        $domain = 'dev';
    }
    elseif (!isDev())
    {
        $domain = 'prod';
    }
    elseif (isDev() && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], (string) ONION_DOMAIN))
    {
        $domain = 'onion_dev';
    }
    elseif (!isDev() && strstr($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], (string) ONION_DOMAIN))
    {
        $domain = 'onion';
    }

    $date = date('Y-m-d');
    $xvisits = 0;
    $xvisitstoday = 0;
    $SQL = <<<SQL
        SELECT page,date,visits FROM count_visits WHERE domain=:domain AND date>:date
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':domain' => $domain, ':date' => date('Y-m-d', time() - 31557600)]);
    while ($data = $req->fetch())
    {
        $xvisits += $data['visits'];
        if ($data['date'] === $date)
        {
            $xvisitstoday += $data['visits'];
        }
    }

    $xvisitors = 0;
    $xconn = 0;
    $xtoday = 0;
    $SQL = <<<SQL
        SELECT lastvisit FROM count_visitors WHERE domain=:domain
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':domain' => $domain]);
    while ($data = $req->fetch())
    {
        if ($data['lastvisit'] > strtotime('midnight'))
        {
            $xtoday++;
        }
        if ($data['lastvisit'] > time() - 600)
        {
            $xconn++;
        }
        $xvisitors++;
    }

    $langs = [];
    $SQL = <<<SQL
        SELECT * FROM languages
        SQL;
    foreach ($bdd->query($SQL) as $data)
    {
        $langs[] = [$data['id'], $data['lang'], $data['name'], $data['priority']];
    }

    $rdata['general'] = [
        'name' => $site_name,
        'slogan' => $slogan,
        'lang' => $lang,
        'version_id' => $lastVersion,
        'version_name' => $versionName,
        'version_time' => $versionDate,
        'maintenance' => (isset($modemaintenance) && $modemaintenance),
        'domain' => $domain,
        'visits_year' => $xvisits,
        'visits_day' => $xvisitstoday,
        'visitors_week' => $xvisitors,
        'visitors_day' => $xtoday,
        'languages' => $langs
    ];
}

if (isset($_GET['slides']))
{
    $slides = [];
    $SQL = <<<SQL
        SELECT * FROM slides WHERE published=true
        SQL;
    foreach ($bdd->query($SQL) as $data)
    {
        $slides[] = [$data['id'], $data['lang'], $data['label'], $data['style'], str_replace('{{site}}', $site_name, $data['title']), $data['title_style'], str_replace('{{site}}', $site_name, $data['contain']), $data['contain_style'], $data['date']];
    }
    $rdata['slides'] = $slides;
}

if (isset($_GET['c']))
{
    $categories = [];
    $SQL = <<<SQL
        SELECT * FROM softwares_categories
        SQL;
    foreach ($bdd->query($SQL) as $data)
    {
        $categories[] = [$data['id'], $data['name'], $data['text']];
    }
    $rdata['articles_categories'] = $categories;
}

if (isset($_GET['ca']) && !empty($_GET['ca']))
{
    $category_articles = [];
    $SQL = <<<SQL
        SELECT * FROM softwares WHERE category=:cat
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':cat' => $_GET['ca']]);
    while ($data = $req->fetch())
    {
        $category_articles[] = [$data['id'], $data['name'], $data['date'], $data['hits'], $data['downloads'], $data['author'], $data['archive_after']];
    }
    $rdata['category_articles'] = $category_articles;
}

if (isset($_GET['a']))
{
    $articles = [];
    if (empty($_GET['a']))
    {
        $SQL = <<<SQL
            SELECT * FROM softwares
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute();
    }
    else
    {
        $SQL = <<<SQL
            SELECT * FROM softwares WHERE id=:id LIMIT 1
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':id' => $_GET['a']]);
    }
    while ($data = $req->fetch())
    {
        $articles[] = [$data['id'], $data['name'], $data['category'], $data['date'], $data['hits'], $data['downloads'], $data['author'], $data['archive_after']];
    }
    $rdata['articles'] = $articles;
}

if (isset($_GET['at']))
{
    $articles_tr = [];
    if (empty($_GET['at']))
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_tr WHERE published=true
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute();
    }
    else
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_tr WHERE published=true AND id=:id LIMIT 1
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':id' => $_GET['at']]);
    }
    while ($data = $req->fetch())
    {
        $articles_tr[] = [$data['id'], $data['lang'], $data['sw_id'], $data['name'], $data['date'], $data['keywords'], $data['description'], $data['website'], $data['author']];
    }
    $rdata['articles_tr'] = $articles_tr;
}

if (isset($_GET['cat']) && !empty($_GET['cat']))
{
    $category_articles_tr = [];
    $SQLSFT = <<<SQL
        SELECT id FROM softwares WHERE category=:cat
        SQL;
    $req_softwares = $bdd->prepare($SQLSFT);
    $req_softwares->execute([':cat' => $_GET['cat']]);
    while ($software = $req_softwares->fetch())
    {
        $sw_id = $software['id'];
        $SQL = <<<SQL
            SELECT * FROM softwares_tr WHERE published=true AND sw_id=:swid
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':swid' => $sw_id]);
        while ($data = $req->fetch())
        {
            $category_articles_tr[] = [$data['id'], $data['lang'], $data['sw_id'], $data['name'], $data['date'], $data['keywords'], $data['description'], $data['website'], $data['author']];
        }
    }
    $rdata['category_articles_tr'] = $category_articles_tr;
}

if (isset($_GET['att']))
{
    $articles_tr_text = [];
    if (empty($_GET['att']))
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_tr WHERE published=true
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute();
    }
    else
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_tr WHERE published=true AND id=:id LIMIT 1
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':id' => $_GET['att']]);
    }
    while ($data = $req->fetch())
    {
        $articles_tr_text[] = [$data['id'], $data['lang'], $data['sw_id'], $data['name'], $data['date'], $data['keywords'], $data['description'], $data['website'], $data['author'], $data['text']];
    }
    $rdata['articles_tr_text'] = $articles_tr_text;
}

if (isset($_GET['catt']) && !empty($_GET['catt']))
{
    $category_articles_tr_text = [];
    $SQLSFTS = <<<SQL
        SELECT id FROM softwares WHERE category=:cat
        SQL;
    $req_softwares = $bdd->prepare($SQLSFTS);
    $req_softwares->execute([':cat' => $_GET['catt']]);
    while ($software = $req_softwares->fetch())
    {
        $sw_id = $software['id'];
        $SQL = <<<SQL
            SELECT * FROM softwares_tr WHERE published=true AND sw_id=:swid
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':swid' => $sw_id]);
        while ($data = $req->fetch())
        {
            $category_articles_tr_text[] = [$data['id'], $data['lang'], $data['sw_id'], $data['name'], $data['date'], $data['keywords'], $data['description'], $data['website'], $data['author'], $data['text']];
        }
    }
    $rdata['category_articles_tr_text'] = $category_articles_tr_text;
}

if (isset($_GET['su']))
{
    $site_updates = [];
    $SQL = <<<SQL
        SELECT * FROM site_updates
        SQL;
    foreach ($bdd->query($SQL) as $data)
    {
        $site_updates[] = [$data['id'], $data['name'], $data['text'], $data['date'], $data['authors'], json_decode((string) $data['codestat'])];
    }
    $rdata['site_updates'] = $site_updates;
}

if (isset($_GET['af']))
{
    $articles_files = [];
    if (empty($_GET['af']))
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_files
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute();
    }
    else
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_files WHERE id=:id LIMIT 1
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':id' => $_GET['af']]);
    }
    while ($data = $req->fetch())
    {
        $articles_files[] = [$data['id'], $data['sw_id'], $data['name'], $data['filetype'], $data['title'], $data['date'], $data['filesize'], $data['hits'], $data['label'], $data['md5'], $data['sha1'], $data['arch'], $data['platform']];
    }
    $rdata['articles_files'] = $articles_files;
}

if (isset($_GET['aaf']) && !empty($_GET['aaf']))
{
    $article_files = [];
    $SQL = <<<SQL
        SELECT * FROM softwares_files WHERE sw_id=:swid
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':swid' => $_GET['aaf']]);
    while ($data = $req->fetch())
    {
        $article_files[] = [$data['id'], $data['name'], $data['filetype'], $data['title'], $data['date'], $data['filesize'], $data['hits'], $data['label'], $data['md5'], $data['sha1'], $data['arch'], $data['platform']];
    }
    $rdata['article_files'] = $article_files;
}

if (isset($_GET['afl']))
{
    $articles_files_by_label = [];
    if (empty($_GET['afl']))
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_files
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute();
    }
    else
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_files WHERE label=:lbl LIMIT 1
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':lbl' => $_GET['afl']]);
    }
    while ($data = $req->fetch())
    {
        $articles_files_by_label[] = [$data['id'], $data['sw_id'], $data['name'], $data['filetype'], $data['title'], $data['date'], $data['filesize'], $data['hits'], $data['label'], $data['md5'], $data['sha1'], $data['arch'], $data['platform']];
    }
    $rdata['articles_files_by_label'] = $articles_files_by_label;
}

if (isset($_GET['am']))
{
    $articles_mirrors = [];
    if (empty($_GET['am']))
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_mirrors
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute();
    }
    else
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_mirrors WHERE id=:id LIMIT 1
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':id' => $_GET['am']]);
    }
    while ($data = $req->fetch())
    {
        $articles_mirrors[] = [$data['id'], $data['sw_id'], json_decode((string) $data['links']), $data['title'], $data['date'], $data['hits'], $data['label']];
    }
    $rdata['articles_mirrors'] = $articles_mirrors;
}

if (isset($_GET['aam']) && !empty($_GET['aam']))
{
    $article_mirrors = [];
    $SQL = <<<SQL
        SELECT * FROM softwares_mirrors WHERE sw_id=:swid
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':swid' => $_GET['aam']]);
    while ($data = $req->fetch())
    {
        $article_mirrors[] = [$data['id'], json_decode((string) $data['links']), $data['title'], $data['date'], $data['hits'], $data['label']];
    }
    $rdata['article_mirrors'] = $article_mirrors;
}

if (isset($_GET['aml']))
{
    $articles_mirrors_by_label = [];
    if (empty($_GET['aml']))
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_mirrors
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute();
    }
    else
    {
        $SQL = <<<SQL
            SELECT * FROM softwares_mirrors WHERE label=:lbl LIMIT 1
            SQL;
        $req = $bdd->prepare($SQL);
        $req->execute([':lbl' => $_GET['aml']]);
    }
    while ($data = $req->fetch())
    {
        $articles_mirrors_by_label[] = [$data['id'], $data['sw_id'], json_decode((string) $data['links']), $data['title'], $data['date'], $data['hits'], $data['label']];
    }
    $rdata['articles_mirrors_by_label'] = $articles_mirrors_by_label;
}

print(json_encode($rdata));
