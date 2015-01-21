<?php

/**
 * 
 * Alex Johnson
 * 
 */

require 'vendor/autoload.php';
include 'config.php';

$app = new \Slim\Slim();

$vars = $app->request->get();

$app->get( '/tip/', tip());

$app->run();

function tip (){

    global $vars;
    global $webhook_url;

    $flag = 1;

    $channel = "#tipbottest";

    $username = "tipbot";

    $poster = $vars['user_name'];

    $reciever = $vars['text'];

    $text = '@'.$poster.' has tipped '.$reciever.'.';

    if (strpos($reciever,'@') === false) {
        echo "That's not a valid username";
        $flag = 0;
    }

    if('@'.$poster == $reciever){
        echo "You can't tip yourself!";
        $flag = 0;
    }

    if($flag){
        $data = '{"channel": "'.$channel.'", "username": "'.$username.'", "text": "'.$text.'", "icon_emoji": ":heavy_dollar_sign:"}';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$webhook_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);

        //echo "Debugging: ".$server_output;
        //
    }
}

?>