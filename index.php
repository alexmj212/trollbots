<?php

/**
 * Index
 *
 * PHP version 5
 *
 * @category Index
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

require 'vendor/autoload.php';
require 'includes/Bot.php';
require 'includes/Post.php';
require 'includes/Payload.php';
require 'includes/Responder.php';
require 'includes/DataSource.php';
require 'includes/OAuth_Slack.php';
foreach (glob('scripts/*.php') as $filename) {
    include $filename;
}

// Initialize Slim Framework.
$app = new \Slim\Slim();

/*
    * GET Requests
*/

// Redirect the root web page to repo page.
$app->get(
    '/',
    function () {
        header('Location: http://alexmj212.github.io/trollbots/');
        exit();
    }
);

$app->get(
    '/dkpbot-auth/',
    function () use ($app) {
        $dkpbotauth = new OAuth_Slack($app->request->get(), 'DKP Bot');
        $dkpbotauth->requestSlackAuth();
    }
);

/*
    * POST Requests
*/

// Define 'tip' endpoint.
$app->post(
    '/tipbot/',
    function () use ($app) {
        $tipbot = new TipBot(new Payload($app->request->post()));
    }
);

// Define 'triggered' endpoint.
$app->post(
    '/triggerbot/',
    function () use ($app) {
        $triggerbot = new TriggerBot(new Payload($app->request->post()));
    }
);

// Define 'channelpolice' endpoint.
$app->post(
    '/channelpolicebot/',
    function () use ($app) {
        $channelpolicebot = new ChannelPoliceBot(new Payload($app->request->post()));
    }
);

// Define 'punbot' endpoint.
$app->post(
    '/punbot/',
    function () use ($app) {
        $punbot = new PunBot(new Payload($app->request->post()));
    }
);

// Define 'dkpbot' endpoint.
$app->post(
    '/dkpbot/',
    function () use ($app) {
        $dkpbot = new DKPBot(new Payload($app->request->post()));
    }
);

// Define 'sarcasmbot' endpoint.
$app->post(
    '/sarcasmbot/',
    function () use ($app) {
        $sarcasmbot = new SarcasmBot(new Payload($app->request->post()));
    }
);

// Run the app.
$app->run();
