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

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/includes/Bot.php';
require __DIR__.'/includes/Post.php';
require __DIR__.'/includes/Payload.php';
require __DIR__.'/includes/Responder.php';
require __DIR__.'/includes/DataSource.php';
require __DIR__.'/includes/OAuth_Slack.php';
foreach (glob(__DIR__.'/scripts/*.php') as $filename) {
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
        $payload = new Payload($app->request->post());
        if (Bot::verifyToken('tipbot', $payload->getToken()) === true) {
            $tipbot = new TipBot($payload);
        } else {
            echo Post::INVALID_TOKEN;
        }
    }
);

// Define 'triggered' endpoint.
$app->post(
    '/triggerbot/',
    function () use ($app) {
        $payload = new Payload($app->request->post());
        if (Bot::verifyToken('triggerbot', $payload->getToken()) === true) {
            $triggerbot = new TriggerBot($payload);
        } else {
            echo Post::INVALID_TOKEN;
        }
    }
);

// Define 'channelpolice' endpoint.
$app->post(
    '/channelpolicebot/',
    function () use ($app) {
        $payload = new Payload($app->request->post());
        if (Bot::verifyToken('channelpolicebot', $payload->getToken()) === true) {
            $channelpolicebot = new ChannelPoliceBot($payload);
        } else {
            echo Post::INVALID_TOKEN;
        }
    }
);

// Define 'punbot' endpoint.
$app->post(
    '/punbot/',
    function () use ($app) {
        $payload = new Payload($app->request->post());
        if (Bot::verifyToken('punbot', $payload->getToken()) === true) {
            $punbot = new PunBot($payload);
        } else {
            echo Post::INVALID_TOKEN;
        }
    }
);

// Define 'dkpbot' endpoint.
$app->post(
    '/dkpbot/',
    function () use ($app) {
        $payload = new Payload($app->request->post());
        if (Bot::verifyToken('dkpbot', $payload->getToken()) === true) {
            $dkpbot = new DKPBot($payload);
        } else {
            echo Post::INVALID_TOKEN;
        }
    }
);

// Define 'sarcasmbot' endpoint.
$app->post(
    '/sarcasmbot/',
    function () use ($app) {
        $payload = new Payload($app->request->post());
        if (Bot::verifyToken('sarcasmbot', $payload->getToken()) === true) {
            $sarcasmbot = new SarcasmBot($payload);
        } else {
            echo Post::INVALID_TOKEN;
        }
    }
);

// Run the app.
$app->run();
