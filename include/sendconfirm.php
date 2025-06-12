<?php

require_once 'consts.php';
require_once 'sendMail.php';

function send_confirm($account, $email, $mhash, $username)
{
    global $lang;
    $tr1 = load_tr($lang, 'sendconfirm');

    $link = SITE_URL.'/confirm.php?id='.$account.'&h='.$mhash;
    $subject = tr($tr1, 'subject');
    $helloText = tr($tr1, 'hello', ['name' => $username]);
    $mainText = tr($tr1, 'text');
    $confirmText = tr($tr1, 'confirm');
    $body = <<<HTML
        <div id="content">
        <h2>{$helloText}</h2>
        <p>{$mainText}<br>
        <a id="link" href="{$link}">{$confirmText}</a></p>
        </div>
        HTML;
    $plainText = tr($tr1, 'plaintext', ['name' => $username,'link' => $link]);
    $altBody = <<<TEXT
        {$plainText}
        TEXT;
    sendMail($email, $subject, $body, $altBody);
}
