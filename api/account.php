<?php

require_once 'api_inc.php';

$rep = ['api_version' => $api_version];

# Login
if (isset($_POST['login_name']) && isset($_POST['login_psw']))
{
    $SQL = <<<SQL
        SELECT * FROM accounts WHERE username=:username
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':username' => $_POST['login_name']]);

    if ($data = $req->fetch())
    {
        if (password_verify((string) $_POST['login_psw'], (string) $data['password']))
        {
            $session = hash('sha512', time().random_int(100000, 999999).sha1(random_int(100000, 999999).$_POST['login_psw']));
            $connectid = hash('sha256', time().random_int(100000, 999999).sha1(random_int(100000, 999999).$data['id']));
            $token = base_convert(md5(strval(random_int(100000, 999999)).$connectid), 16, 36);
            $created = time();
            $expire = $created + 31557600;
            $SQL2 = <<<SQL
                INSERT INTO sessions (account, session, connectid, expire, created, token) VALUES (:acc,:session,:connectid,:exp,:create,:token)
                SQL;
            $req2 = $bdd->prepare($SQL2);
            $req2->execute([':acc' => $data['id'], ':session' => password_hash($session, PASSWORD_DEFAULT), ':connectid' => $connectid, ':exp' => $expire, ':create' => $created, ':token' => $token]);
            $rep['login'] = ['session' => $session, 'connectid' => $connectid, 'expire' => $expire];
        }
    }
}

# Check login
$logged = false;
if (isset($_POST['session']) && isset($_POST['connectid']))
{
    $SQL = <<<SQL
        SELECT sessions.id AS session_id, sessions.session, sessions.connectid, sessions.expire, sessions.token, accounts.id, accounts.id64, accounts.email, accounts.username, accounts.signup_date, accounts.password, accounts.settings, accounts.confirmed, accounts.subscribed_comments, accounts.rank, accounts.rights, accounts.twofa_enabled, accounts.twofa_secret, team.id AS team_id, team.works AS works, team.short_name AS short_name, team.rights AS admin_rights
        FROM sessions 
        LEFT JOIN accounts ON accounts.id = sessions.account 
        LEFT JOIN team ON team.account_id = sessions.account 
        WHERE sessions.connectid=:connectid AND sessions.expire>:exp LIMIT 1
        SQL;
    $req = $bdd->prepare($SQL);
    $req->execute([':connectid' => $_POST['connectid'], ':exp' => time()]);
    if ($login = $req->fetch())
    {
        if (password_verify((string) $_POST['session'], (string) $login['session']))
        {
            $logged = true;
            $SQL2 = <<<SQL
                UPDATE sessions SET expire=:exp WHERE id=:id
                SQL;
            $req2 = $bdd->prepare($SQL2);
            $req2->execute([':exp' => time() + 31557600, ':id' => $login['session_id']]);
            $settings = json_decode((string) $login['settings'], true);

            $rep['login'] = ['connectid' => $login['connectid'], 'expire' => $login['expire'], 'token' => $login['token']];
        }
    }
}

if ($logged)
{
    if (isset($_GET['myinfo']))
    {
        $rep['myinfo'] = [];
        $rep['myinfo']['id'] = $login['id'];
        $rep['myinfo']['name'] = $login['username'];
        $rep['myinfo']['mail'] = $login['email'];
        $rep['myinfo']['settings'] = $settings;
        $rep['myinfo']['rank'] = $login['rank'];
        $rep['myinfo']['rights'] = $login['member_rights'];
        $rep['myinfo']['twofa_enabled'] = $login['twofa_enabled'];
        $rep['myinfo']['twofa_secret'] = $login['twofa_secret'];
        $rep['myinfo']['signup_date'] = $login['signup_date'];
        $rep['myinfo']['subscribed_comments'] = $login['subscribed_comments'];
        $rep['myinfo']['admin_name'] = $login['short_name'];
        $rep['myinfo']['works'] = $login['works'];
        $rep['myinfo']['admin_rights'] = $login['admin_rights'];
    }
    if (isset($_POST['token']) && $_POST['token'] === $login['token'])
    {
        if (isset($_GET['subscribe_comments']))
        {
            $SQL = <<<SQL
                SELECT id FROM softwares WHERE id=:id LIMIT 1
                SQL;
            $req = $bdd->prepare($SQL);
            $req->execute([':id' => $_GET['subscribe_comments']]);
            if ($req->fetch())
            {
                $SQL = <<<SQL
                    SELECT id FROM subscriptions_comments WHERE account=:acc AND article=:art LIMIT 1
                    SQL;
                $req = $bdd->prepare($SQL);
                $req->execute([':acc' => $login['id'], ':art' => $_GET['subscribe_comments']]);
                if (!$req->fetch())
                {
                    $SQL = <<<SQL
                        INSERT INTO subscriptions_comments (account,article) VALUES (:acc,:art)
                        SQL;
                    $req = $bdd->prepare($SQL);
                    $req->execute([':acc' => $login['id'], ':art' => $_GET['subscribe_comments']]);
                    $rep['subscribed'] = ['comments' => [$_GET['subscribe_comments']]];
                }
            }
        }
        if (isset($_GET['unsubscribe_comments']))
        {
            $SQL = <<<SQL
                DELETE FROM subscriptions_comments WHERE account=:acc AND article=:art
                SQL;
            $req = $bdd->prepare($SQL);
            $req->execute([':acc' => $login['id'], ':art' => $_GET['unsubscribe_comments']]);
            $rep['unsubscribed'] = ['comments' => [$_GET['unsubscribe_comments']]];
        }
        if (isset($_GET['read_all_notifs']))
        {
            $SQL = <<<SQL
                UPDATE notifs SET unread=false WHERE account=:acc AND unread=true
                SQL;
            $req = $bdd->prepare($SQL);
            $req->execute([':acc' => $login['id']]);
            $rep['read_all_notifs'] = true;
        }
        if (isset($_GET['read_notif']))
        {
            $SQL = <<<SQL
                UPDATE notifs SET unread=false WHERE id=:id AND account=:acc
                SQL;
            $req = $bdd->prepare($SQL);
            $req->execute([':id' => $_GET['read_notif'], ':acc' => $login['id']]);
            $rep['read_notif'] = $_GET['read_notif'];
        }
        if (isset($_GET['unread_notif']))
        {
            $SQL = <<<SQL
                UPDATE notifs SET unread=true WHERE id=:id AND account=:acc
                SQL;
            $req = $bdd->prepare($SQL);
            $req->execute([':id' => $_GET['unread_notif'], ':acc' => $login['id']]);
            $rep['unread_notif'] = $_GET['unread_notif'];
        }
    }
}

echo json_encode($rep);
