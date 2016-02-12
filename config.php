<?php
/**
 * Config.php
 *
 * PHP version 5
 *
 * @category Configuration
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

if (file_exists(__DIR__.'/environment/environment.php') === true) {
    include __DIR__.'/environment/environment.php';
}

// DKP Bot Credentials.
$conf['bots']['DKP Bot']['slack_client_id']     = getenv('dkpbot_slack_client_id') ?: null;
$conf['bots']['DKP Bot']['slack_client_secret'] = getenv('dkpbot_slack_client_secret') ?: null;

// Test Bot Credentials.
$conf['bots']['Test Bot']['slack_client_id']     = getenv('testbot_slack_client_id') ?: null;
$conf['bots']['Test Bot']['slack_client_secret'] = getenv('testbot_slack_client_secret') ?: null;

// Subreddit Bot Credentials.
$conf['bots']['Subreddit Bot']['reddit_token_url']     = getenv('reddit_token_url') ?: null;
$conf['bots']['Subreddit Bot']['reddit_redirect_uri']  = getenv('reddit_redirect_uri') ?: null;
$conf['bots']['Subreddit Bot']['reddit_client_id']     = getenv('reddit_client_id') ?: null;
$conf['bots']['Subreddit Bot']['reddit_client_secret'] = getenv('reddit_client_secret') ?: null;
$conf['bots']['Subreddit Bot']['reddit_username']      = getenv('reddit_username') ?: null;
$conf['bots']['Subreddit Bot']['reddit_password']      = getenv('reddit_password') ?: null;

// Mongo Credentials.
$conf['datasource']['mongo_username']      = getenv('mongo_username') ?: null;
$conf['datasource']['mongo_pw']            = getenv('mongo_pw') ?: null;
$conf['datasource']['mongo_database_name'] = getenv('mongo_database_name') ?: null;
$conf['datasource']['mongo_domain']        = getenv('mongo_domain') ?: null;
$conf['datasource']['mongo_port']          = getenv('mongo_port') ?: null;
