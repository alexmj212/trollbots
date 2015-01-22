<?php

/**
 * 
 * Alex Johnson
 * 
 */

require 'vendor/autoload.php';
include 'config.php';
include 'includes/sender.php';

$app = new \Slim\Slim();

$sender = new ProcessPayload($app->request->post());

$app->post( '/tip/', 'tip');

$app->run();

    function tip (){

        global $vars;
        global $webhook_url;
        global $sender;

        $text = process_response($sender);

        $flag = 1;

        $bot_username = "Tip Bot";

        if($text){
            $data = '{"channel": "'.$sender->channel_name.'", ';
            $data = $data.'"username": "'.$bot_username.'", ';
            $data = $data.'"text": "'.$sender->text.'", ';
            $data = $data.'"icon_emoji": ":heavy_dollar_sign:"}';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,$webhook_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

            // receive server response ...
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec ($ch);

            curl_close ($ch);

        }
    }

    function process_response($payload){

        switch($payload->payload_type){
            case 'user_name' :
                if($payload->user_name == $payload->recipient){
                    return "You can't tip yourself!";
                }
                return $payload->user_name.' has tipped '.$payload->recipient.' in the channel '.$payload->channel_name;
                break;
            case 'total' :
                return "Just the tip.";
                break;
            default :
                return "Invalid command!";
                break;
        }
    }

?>
