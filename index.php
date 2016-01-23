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
include 'includes/dataSource.php';
include 'includes/oauth.php';
include 'includes/reddit-oauth.php';
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
//$app->get('/tipbot-auth/', function() use ($app) {$tipbotauth = new OAuth($app->request->get(),'tipbot');});

//Define 'triggered' endpoint
$app->post('/triggerbot/', function() use ($app) {$triggerbot = new TriggerBot($app->request->post());});
//$app->get('/triggerbot-auth/', function() use ($app) {$triggerbotauth = new OAuth($app->request->get(),'triggerbot');});

//Define 'channelpolice' endpoint
$app->post('/channelpolicebot/', function() use ($app) {$channelpolicebot = new ChannelPoliceBot($app->request->post());});
//$app->get('/channelpolicebot-auth/', function() use ($app) {$channelpolicebotauth = new OAuth($app->request->get(),'channelpolicebot');});

//Define 'punbot' endpoint
$app->post('/punbot/', function() use ($app) {$punbot = new PunBot($app->request->post());});
//$app->get('/punbot-auth/', function() use ($app) {$punbotauth = new OAuth($app->request->get(),'punbot');});

//Define 'dkpbot' endpoint
$app->post('/dkpbot/', function() use ($app) {$dkpbot = new DKPBot($app->request->post());});
$app->get('/dkpbot-auth/', function() use ($app) {$dkpbotauth = new OAuth($app->request->get(),'DKP Bot');});

//Define 'subredditbot' endpoint
$app->post('/subredditbot/', function() use ($app) {$subredditbot = new SubredditBot($app->request->post());});
$app->get('/subredditbot-redditauth/', function() use ($app) {$subredditbotredditoauth = new RedditOAuth($app->request->get());});
//Run the app
$app->run();

//Redirect main page (optional)
	function main (){
		echo "This is the test bot. You shouldn't be here!";
		//header('Location: http://alexmj212.github.io/slackphpbot/');
		die();
	}

