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

use TrollBots\Lib;
use TrollBots\Auth;
use TrollBots\Scripts as Bots;
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/config.php';

// Initialize Slim Framework.
$app = new Slim\Slim();

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

/*
    * POST Requests
*/

// Define 'triggered' endpoint.
$app->post(
    '/triggerbot/',
    function () use ($app) {
        $payload = new Lib\Payload($app->request->post());
        if (Lib\Bot::verifyToken('triggerbot', $payload->getToken()) === true) {
            $triggerbot = new Bots\TriggerBot($payload);
        } else {
            echo Lib\Post::INVALID_TOKEN;
        }
    }
);

// Define 'channelpolice' endpoint.
$app->post(
    '/channelpolicebot/',
    function () use ($app) {
        $payload = new Lib\Payload($app->request->post());
        if (Lib\Bot::verifyToken('channelpolicebot', $payload->getToken()) === true) {
            $channelpolicebot = new Bots\ChannelPoliceBot($payload);
        } else {
            echo Lib\Post::INVALID_TOKEN;
        }
    }
);

// Define 'punbot' endpoint.
$app->get(
    '/punbot-auth/',
    function () use ($app) {
        $punbotauth = new Auth\OAuth_Slack($app->request->get(), 'Pun Bot');
        $punbotauth->requestSlackAuth();
    }
);
$app->post(
    '/punbot/',
    function () use ($app) {
        $payload = new Lib\Payload($app->request->post());
        if (Lib\Bot::verifyToken('punbot', $payload->getToken()) === true) {
            $punbot = new Bots\PunBot($payload);
        } else {
            echo Lib\Post::INVALID_TOKEN;
        }
    }
);

// Define 'dkpbot' endpoint.
$app->get(
    '/dkpbot-auth/',
    function () use ($app) {
        $dkpbotauth = new Auth\OAuth_Slack($app->request->get(), 'DKP Bot');
        $dkpbotauth->requestSlackAuth();
    }
);
$app->post(
    '/dkpbot/',
    function () use ($app) {
        $payload = new Lib\Payload($app->request->post());
        if (Lib\Bot::verifyToken('dkpbot', $payload->getToken()) === true) {
            $dkpbot = new Bots\DKPBot($payload);
        } else {
            echo Lib\Post::INVALID_TOKEN;
        }
    }
);

// Define 'sarcasmbot' endpoint.
$app->post(
    '/sarcasmbot/',
    function () use ($app) {
        $payload = new Lib\Payload($app->request->post());
        if (Lib\Bot::verifyToken('sarcasmbot', $payload->getToken()) === true) {
            $sarcasmbot = new Bots\SarcasmBot($payload);
        } else {
            echo Lib\Post::INVALID_TOKEN;
        }
    }
);

// Define 'payrespectsbot' endpoint.
$app->get(
    '/payrespectsbot-auth/',
    function () use ($app) {
        $payrespectsbotauth = new Auth\OAuth_Slack($app->request->get(), 'Pay Respects Bot');
        $payrespectsbotauth->requestSlackAuth();
    }
);
$app->post(
    '/payrespectsbot/',
    function () use ($app) {
        $payload = new Lib\Payload($app->request->post());
        if (Lib\Bot::verifyToken('payrespectsbot', $payload->getToken()) === true) {
            $payrespectsbot = new Bots\PayRespectsBot($payload);
        } else {
            echo Lib\Post::INVALID_TOKEN;
        }
    }
);
$app->post(
    '/payrespectsbot-action/',
    function () use ($app) {
        $payload = new Lib\ActionPayload($app->request->post());
        if (Lib\Bot::verifyToken('payrespectsbot', $payload->getToken()) === true) {
            $payrespectsbot = new Bots\PayRespectsBot($payload);
        } else {
            echo Lib\Post::INVALID_TOKEN;
        }
    }
);

// Run the app.
$app->run();
