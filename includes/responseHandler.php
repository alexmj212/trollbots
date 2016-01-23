<?php


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

		$response_type = 'ephemeral';

		if($this->botVisibility){
			$response_type = 'in_channel';
		}
		if(is_array($this->botText)){
			header('Content-Type: application/json');
			echo json_encode(array('response_type' => $response_type,'attachments'=>array($this->botText)));
		} else {
			header('Content-Type: application/json');
			echo json_encode(array('response_type' => $response_type, 'text' => $this->botText, 'unfurl_links' => true));
		}
		exit;
	}
}

