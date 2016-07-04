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

namespace TrollBots;

// Set Environment. Options: {test,prod}.
putenv('env=test');

if (file_exists(__DIR__.'/environment/environment.php') === true) {
    include __DIR__.'/environment/environment.php';
}

// DKP Bot Credentials.
$conf['bots']['DKP Bot']['slack_client_id']     = getenv('dkpbot_slack_client_id') ?: null;
$conf['bots']['DKP Bot']['slack_client_secret'] = getenv('dkpbot_slack_client_secret') ?: null;

// Pay Respects Bot Credentials.
$conf['bots']['Pay Respects Bot']['slack_client_id']     = getenv('payrespectsbot_slack_client_id') ?: null;
$conf['bots']['Pay Respects Bot']['slack_client_secret'] = getenv('payrespectsbot_slack_client_secret') ?: null;

// Pun Bot Credentials.
$conf['bots']['Pun Bot']['slack_client_id']     = getenv('punbot_slack_client_id') ?: null;
$conf['bots']['Pun Bot']['slack_client_secret'] = getenv('punbot_slack_client_secret') ?: null;

// Mongo Credentials.
$conf['datasource']['mongo_username']      = getenv('mongo_username') ?: null;
$conf['datasource']['mongo_pw']            = getenv('mongo_pw') ?: null;
$conf['datasource']['mongo_database_name'] = getenv('mongo_database_name') ?: null;
$conf['datasource']['mongo_domain']        = getenv('mongo_domain') ?: null;
$conf['datasource']['mongo_port']          = getenv('mongo_port') ?: null;
