class API_Session
{
    constructor(url)
    {
        this.session = null;
        this.connectid = null;
        this.token = null;
        this.url = url;
    }
}

function api_subscribe_comments(api_session, article, callback)
{
    return $.post(api_session.url+"account.php?subscribe_comments="+article, {session:api_session.session, connectid:api_session.connectid, token:api_session.token}, callback);
}

function api_unsubscribe_comments(api_session, article, callback)
{
    return $.post(api_session.url+"account.php?unsubscribe_comments="+article, {session:api_session.session, connectid:api_session.connectid, token:api_session.token}, callback);
}

function api_read_all_notifs(api_session, callback)
{
    return $.post(api_session.url+"account.php?read_all_notifs", {session:api_session.session, connectid:api_session.connectid, token:api_session.token}, callback);
}

function api_read_notif(api_session, notif, callback)
{
    return $.post(api_session.url+"account.php?read_notif="+notif, {session:api_session.session, connectid:api_session.connectid, token:api_session.token}, callback);
}

function api_unread_notif(api_session, notif, callback)
{
    return $.post(api_session.url+"account.php?unread_notif="+notif, {session:api_session.session, connectid:api_session.connectid, token:api_session.token}, callback);
}
