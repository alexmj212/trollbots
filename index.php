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
include 'config.php';
include 'includes/payload_processor.php';
include 'includes/file_handler.php';

//Initialize Slim Framework
$app = new \Slim\Slim();

$filehandler = new Handler();

//Define 'tip' endpoint and associated controller
$app->get( '/tip/', 'tip');

//Execute Slim framework processing
$app->run();

    /**
     * tip function
     * Controller for the tip endpoint
     */

    function tip (){

        global $app;  
        global $payload;
        global $filehandler;

        $payload = new ProcessPayload($app->request->get());
        $payload->response_text = tip_process_response($payload);

    }

    function tip_process_response(&$payload){

        global $filehandler;

        switch($payload->payload_type){
            case 'user_name' :
                if($payload->user_name == $payload->recipient){
                    echo "You can't tip yourself!";
                    return;
                }
                $filehandler->log_tip($payload);
                $payload->response_text = $payload->user_name.' has tipped '.$payload->recipient;
                send_response($payload);
                return;
                break;
            case 'total' :
                echo "You've been tipped ".$filehandler->retrieve_total($payload->user_name)." time(s)";
                return;
                break;
            default :
                echo "Invalid command!";
                return;
                break;
        }
    }

    function send_response(&$payload){

        global $webhook_url;

        $bot_user_name = "Tip Bot";

        $data = '{"channel": "'.$payload->channel_name.'", ';
        $data = $data.'"username": "'.$bot_user_name.'", ';
        $data = $data.'"text": "'.$payload->response_text.'", ';
        $data = $data.'"icon_emoji": ":heavy_dollar_sign:"}';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$webhook_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

        // receive server response ...
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

    }

?>
