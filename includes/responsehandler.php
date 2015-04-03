<?php

include 'config.php';

class Responder {

    private $botUserName = "Tip Bot";

    public function __construct(&$data){

        switch ($data->responseType) {
            case 'private':
                echo $data->responseText;
                break;

            case 'channel':
                $this->post($data);
                break;
            
            default:
                echo "Not a valid channel";
                break;
        }

    }

    private function post(&$data){

        global $webhookUrl;

        $post = '{"channel": "'.$data->channelName.'", ';
        $post = $post.'"username": "'.$this->botUserName.'", ';
        $post = $post.'"text": "'.$data->responseText.'", ';
        $post = $post.'"icon_emoji": ":heavy_dollar_sign:"}';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$webhookUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

        // receive server response ...
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec ($ch);

        curl_close ($ch);

    }

}

?>