<?php

$document_root = __DIR__.'/../../..';
require_once $document_root.'/include/config.local.php';
require_once('vendor/autoload.php');

function send_facebook($message)
{
    if (!isDev() && (defined('FB_APP_ID') && constant('FB_APP_ID')) && (defined('FB_APP_SECRET') && constant('FB_APP_SECRET')) && (defined('FB_TOKEN') && constant('FB_TOKEN')))
    {
        $fb = new Facebook\Facebook([
     'app_id' => FB_APP_ID,
     'app_secret' => FB_APP_SECRET,
     'default_graph_version' => 'v2.10',
        ]);
        $linkData = [
     'message' => $message,
        ];
        try
        {
            $response = $fb->post('/me/feed', $linkData, FB_TOKEN);
        }
        catch (Facebook\Exceptions\FacebookResponseException $e)
        {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        }
        catch (Facebook\Exceptions\FacebookSDKException $e)
        {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphNode = $response->getGraphNode();
    }
}
