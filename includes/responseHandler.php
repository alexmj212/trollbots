<?php

include 'config.php';

class Responder {

	private $botName;
	private $botIcon;
	private $botText;
	private $botChannel;
	private $botVisibility;

	public function __construct($botName, $botIcon, $botText, $botChannel, $botVisibility){
                $this->botName = $botName;
		$this->botIcon = $botIcon;
		$this->botText = $botText;
		$this->botChannel = $botChannel;
		$this->botVisibility = $botVisibility;
		$this->post();
	}

	public function post(){
		if($this->botVisibility){
			global $webhookUrl;
			$post = '{"channel": "'.$this->botChannel.'", ';
			$post = $post.'"username": "'.$this->botName.'", ';
			$post = $post.'"text": "'.$this->botText.'", ';
			$post = $post.'"icon_emoji": "'.$this->botIcon.'"}';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$webhookUrl);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
			curl_exec ($ch);
			curl_close ($ch);
			die();
		} else {
			echo $this->botText;
		}
	}
}

?>
