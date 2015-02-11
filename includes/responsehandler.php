<?php

include 'config.php';

class Responder {

    private $bot_user_name = "Tip Bot";

    public function __construct(&$data){

        switch ($data->response_type) {
            case 'private':
                echo $data->response_text;
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

        global $webhook_url;

        $post = '{"channel": "'.$data->channel_name.'", ';
        $post = $post.'"username": "'.$this->bot_user_name.'", ';
        $post = $post.'"text": "'.$data->response_text.'", ';
        $post = $post.'"icon_emoji": ":heavy_dollar_sign:"}';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$webhook_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);

        // receive server response ...
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //curl_exec ($ch);
        echo $post;


        curl_close ($ch);

    }

}

?>