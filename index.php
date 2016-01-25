<?php

/**
 * Index
 *
 * PHP version 5
 *
 * @category Index
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

require 'vendor/autoload.php';
require 'includes/Payload.php';
require 'includes/Responder.php';
require 'includes/DataSource.php';
require 'includes/OAuth.php';
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
        header('Location: http://alexmj212.github.io/slackphpbot/');
        die();
    }
);

$app->get(
    '/dkpbot-auth/',
    function () use ($app) {
        $dkpbotauth = new OAuth($app->request->get(), 'DKP Bot');
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
        $channelpolicebot = new ChannelPoliceBot($app->request->post());
    }
);

// Define 'punbot' endpoint.
$app->post(
    '/punbot/',
    function () use ($app) {
        $punbot = new PunBot($app->request->post());
    }
);

// Define 'dkpbot' endpoint.
$app->post(
    '/dkpbot/',
    function () use ($app) {
        $dkpbot = new DKPBot($app->request->post());
    }
);

// Run the app.
$app->run();
