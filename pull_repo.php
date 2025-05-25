<?php

require_once('include/config.local.php');
if (getallheaders()['X-Gitlab-Token'] === GIT_WEBHOOK_TOKEN)
{
    shell_exec('git --git-dir="'.GIT_DIR.'" pull');
}
