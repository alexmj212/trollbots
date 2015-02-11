<?php

/**
 * Tip Bot for Slack
 * Alex Johnson
 * Main
 */

/**
 * Description:
 * * Driver for the application
 * * Recieve data, rocess and send responses
 */

require 'vendor/autoload.php';
include 'includes/processpayload.php';

//Initialize Slim Framework
$app = new \Slim\Slim();

//Define 'tip' endpoint and associated controller
$app->post( '/tip/', 'tip');

//Execute Slim framework processing
$app->run();

    /**
     * tip function
     * Controller for the tip endpoint
     */

    function tip (){

        global $app;

        $payload = new ProcessPayload($app->request->post());

    }





?>
