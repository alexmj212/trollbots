<?php

/**
 * Slack PHP Bot
 * Alex Johnson
 */

/**
 * Description:
 * * Driver for the application
 * * Set endpoints for bot scripts
 */
require 'vendor/autoload.php';
include 'includes/processPayload.php';
include 'includes/responseHandler.php';
foreach (glob('scripts/*.php') as $filename)
{
    include_once $filename;
}

//Initialize Slim Framework
$app = new \Slim\Slim();

//Redirect the root web page to something
$app->get('/', 'main');

//Define 'tip' endpoint
$app->post('/tipbot/', function() use ($app) {$tipbot = new TipBot($app->request->post());});

//Define 'triggered' endpoint
$app->post('/triggerbot/', function() use ($app) {$triggerbot = new TriggerBot($app->request->post());});

//Define 'channelpolice' endpoint
$app->post('/channelpolicebot/', function() use ($app) {$channelpolicebot = new ChannelPoliceBot($app->request->post());});

//Define 'punbot' endpoint
$app->post('/punbot/', function() use ($app) {$punbot = new PunBot($app->request->post());});

//Define 'dkpbot' endpoint
$app->post('/dkpbot/', function() use ($app) {$dkpbot = new DKPBot($app->request->post());});


//Run the app
$app->run();

//Redirect main page (optional)
    function main (){
        header('Location: https://github.com/alexmj212/slackphpbot');
        die();
    }

?>
