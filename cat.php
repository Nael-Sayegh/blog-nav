<?php
if (!isset($_GET['id']) || !ctype_digit((string) $_GET['id']))
{
    header('Location: /');
    exit();
}
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once(__DIR__ . '/include/log.php');
require_once(__DIR__ . '/include/consts.php');
$SQL = <<<SQL
    SELECT * FROM softwares_categories WHERE id=:id
    SQL;
$req = $bdd->prepare($SQL);
$req->execute([':id' => $_GET['id']]);
$data = $req->fetch();
if (!$data)
{
    header('Location: /');
    exit();
}
$tr = load_tr($lang, 'cat');
$cat_id = $data['id'];
$title = str_replace('{{site}}', $site_name, $data['name']);
$cat_text = $data['text'];

$args['id'] = $cat_id;
$stats_page = 'cat'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<?php require_once(__DIR__ . '/include/header.php'); ?>
<body>
<?php require_once(__DIR__ . '/include/banner.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<?= str_replace('{{site}}', $site_name, $cat_text) ?>
<div id="js-sort-container" hidden style="margin:1em 0;">
  <label for="js_sort"><?= tr($tr, 'sort_label') ?></label>
  <select id="js_sort">
    <option value="date"><?= tr($tr, 'sort_date') ?></option>
    <option value="hits"><?= tr($tr, 'sort_hits') ?></option>
    <option value="name"><?= tr($tr, 'sort_alpha_order') ?></option>
  </select>
</div>
<noscript>
  <p><?= tr($tr, 'js_to_sort') ?></p>
</noscript>
<div id="software-list">
<?php
$entries = [];
$SQL = <<<SQL
    SELECT softwares_tr.id, softwares_tr.lang, softwares_tr.name, softwares_tr.description, softwares_tr.sw_id, softwares.hits, softwares.downloads, softwares.date
    FROM softwares
    LEFT JOIN softwares_tr ON softwares.id=softwares_tr.sw_id
    WHERE softwares.category=:sw_cat AND softwares_tr.published=true AND softwares_tr.lang=:lang
    ORDER BY softwares.date DESC
    SQL;
$req = $bdd->prepare($SQL);
$req->execute([':sw_cat' => $cat_id, ':lang' => $lang]);
while ($data = $req->fetch())
{
    $entries[$data['sw_id']] = [
        'hits' => $data['hits'],
        'dl' => $data['downloads'],
        'date' => $data['date'],
        'id' => $data['id'],
        'title' => $data['name'],
        'desc' => $data['description'],
    ];
}

foreach ($entries as $sw_id => $entry)
{
    printf(
        '<div class="software" data-date="%d" data-hits="%d" data-name="%s">
        <span role="heading" aria-level="2">
        <a class="software_title" href="a%d">%s</a>
        </span>
        <p>%s<br>
        <span class="software_hits">%s</span>
        <span class="software_date">(%s)</span>
        </p></div>',
        $entry['date'],
        $entry['hits'],
        htmlspecialchars(strtolower(str_replace('{{site}}', $site_name, $entry['title']))),
        $sw_id,
        str_replace('{{site}}', $site_name, $entry['title']),
        str_replace('{{site}}', $site_name, $entry['desc']),
        tr($tr, 'hits', ['hits' => $entry['hits']]),
        tr($tr, 'date', ['date' => getFormattedDate($entry['date'], tr($tr0, 'fndatetime'))]),
    );
}
?>
</div>
</main>
<?php require_once(__DIR__ . '/include/footer.php'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function()
    {
        const sortContainer = document.getElementById('js-sort-container');
        if (sortContainer) sortContainer.hidden = false;
        const select = document.getElementById('js_sort');
        const list   = document.getElementById('software-list');
        const items  = Array.from(list.children);
        function sortSoftware(by)
        {
            const key = by;
            const sorted = items.slice().sort((a, b) =>
            {
                let va = a.dataset[key], vb = b.dataset[key];
                if (['date','hits'].includes(key))
                {
                    va = parseInt(va,10) || 0;
                    vb = parseInt(vb,10) || 0;
                }
                else
                {
                    va = va.toLowerCase();
                    vb = vb.toLowerCase();
                }
                if (va < vb) return (['date','hits'].includes(key) ? 1 : -1);
                if (va > vb) return (['date','hits'].includes(key) ? -1 : 1);
                return 0;
            });
            list.innerHTML = '';
            sorted.forEach(el => list.appendChild(el));
        }
        if (select)
        {
            sortSoftware(select.value);
            select.addEventListener('change', () => sortSoftware(select.value));
        }
    });
</script>
</body>
</html>
