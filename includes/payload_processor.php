<?php

class ProcessPayload {

	public $token;

	public $team_id;

	public $channel_id;

	public $channel_name;

	public $user_id;

	public $user_name;

	public $text;

	public $recipient;

	public $payload_type;

	public function __construct ($data){

		$this->token = $data['token'];

		$this->team_id = $data['team_id'];

		$this->channel_id = $data['channel_id'];

		$this->channel_name = '#'.$data['channel_name'];

		$this->user_id = $data['user_id'];

		$this->user_name = '@'.strtolower($data['user_name']);

		$this->text = strtolower($data['text']);

		$this->payload_type = $this->parse_text();

	}

	public function parse_text(){

		if($this->is_user_name()){
			$this->recipient = $this->text;
			return 'user_name';

		} else if($this->text == 'total'){
			return 'total';
		} else "Invalid command";

	}

	public function is_user_name(){
		if(strpos($this->text,'@') > 0 || strpos($this->text,'@') === false){
			return false;
		}
		return true;
	}

}

?>