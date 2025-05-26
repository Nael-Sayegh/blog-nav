<?php
$logonly = true;
require_once('include/log.php');
requireMemberRight('view_members');
$stats_page = 'liste_comptes';
set_include_path($_SERVER['DOCUMENT_ROOT']);
require_once('include/consts.php');
$sound_path = '/audio/page_sounds/member.mp3';
$css_path .= '<style>#member-list tr:nth-child(odd){background-color:#E0E0E0;}</style>';
$title = 'Liste des membres '.$site_name;
?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/header.php'); ?>
<body>
<?php require_once('include/banner.php');
require_once('include/load_sound.php'); ?>
<main id="container">
<h1 id="contenu"><?php print $title; ?></h1>
<div id="js-sort-container" hidden style="margin:1em 0;">
  <label for="js_sort">Trier par :</label>
  <select id="js_sort">
    <option value="signup_date">Date d’inscription</option>
    <option value="username">Ordre alphabétique</option>
  </select>
</div>
<noscript>
  <p>Activez JavaScript pour trier les membres</p>
</noscript>
<table style="width:100%;">
<thead><tr><th>Numéro de membre</th><th>Nom</th><th>Inscription</th><th>Rang</th><?php /*<th>Actions</th>*/ ?></tr></thead>
<tbody id="member-list">
<?php
include_once('include/user_rank.php');
$SQL = <<<SQL
    SELECT accounts.id AS account_id, accounts.username AS account_name, accounts.signup_date AS account_signup_date, accounts.rank AS account_rank, accounts.settings AS settings, team.id AS team_id 
    FROM accounts 
    LEFT JOIN team ON team.account_id = accounts.id 
    ORDER BY accounts.signup_date DESC
    SQL;
$n = 0;
foreach ($bdd->query($SQL) as $data)
{
    $sets = json_decode((string) $data['settings'], true);
    printf(
        '<tr data-signup_date="%d" data-username="%s">
         <td>M%d%s</td>
         <td>%s%s</td>
         <td>%s</td>
         <td>%s</td>
       </tr>',
        $data['account_signup_date'],
        strtolower(htmlentities((string) $data['account_name'], ENT_QUOTES)),
        $data['account_id'],
        $data['account_rank'] === 'a' ? '/E'.$data['team_id'] : '',
        urank($data['account_rank'], strip_tags((string) $data['account_name']), false),
        (
            isset($sets['bd_m'], $sets['bd_d'])
        && ($sets['bd_m'] == date('n') && $sets['bd_d'] == date('j')
          || ($sets['bd_m'] == 2 && date('n') == 3 && $sets['bd_d'] == 29 && date('L')))
        ) ? ' 🎂' : '',
        date('d/m/Y', $data['account_signup_date']),
        urank($data['account_rank'])
    );
    $n++;
}
?>
</tbody>
</table>
<p><b><?= $n ?></b> membres trouvés</p>
</main>
<?php require_once('include/footer.php'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function()
    {
        const ctr = document.getElementById('js-sort-container');
        if (ctr) ctr.hidden = false;
        const select = document.getElementById('js_sort');
        const tbody  = document.getElementById('member-list');
        const rows   = Array.from(tbody.querySelectorAll('tr'));
        function sortRows(key)
        {
            const sorted = rows.slice().sort((a,b)=>
            {
                let va = a.dataset[key], vb = b.dataset[key];
                if (key === 'signup_date')
                {
                    va = parseInt(va,10) || 0;
                    vb = parseInt(vb,10) || 0;
                }
                else
                {
                    va = va.toLowerCase();
                    vb = vb.toLowerCase();
                }
                if (va < vb) return key==='signup_date' ? 1 : -1;
                if (va > vb) return key==='signup_date' ? -1 : 1;
                return 0;
            });
            tbody.innerHTML = '';
            sorted.forEach(tr => tbody.appendChild(tr));
        }
        if (select)
        {
            sortRows(select.value);
            select.addEventListener('change', () => sortRows(select.value));
        }
    });
</script>
</body>
</html>