<?php

/**  
 * Tip Bot for Slack
 * Alex Johnson
 * Payload Processor
 */

include 'filehandler.php';
include 'responsehandler.php';

/**
 * Description:
 * * Construct the object to retain event data
 * * Parse the recieved text and determine it's validity
 * * Test to see if the text is a username
 */

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
	public $response_text;
	public $response_type;

	public function __construct ($data){

		$this->token = $data['token'];
		$this->team_id = $data['team_id'];
		$this->channel_id = $data['channel_id'];
		$this->channel_name = '#'.$data['channel_name'];
		$this->user_id = $data['user_id'];
		$this->user_name = '@'.strtolower($data['user_name']);
		$this->text = strtolower($data['text']);
		$this->payload_type = $this->parse_command();

	}

	private function parse_command(){

		$file = new Handler();

		switch(true){

			case $this->is_user_name() :

				$this->recipient = $this->text;
			    if($this->user_name == $this->recipient){
                    $this->response("You can't tip yourself!",'private');
                    return;
                }
                $this->response($this->user_name.' has tipped '.$this->recipient,'channel');
                $file->log_tip($this);
            	break;

			case $this->text == 'total' :

           		$total = $file->retrieve_total($this->user_name);
           		$this->response("You've been tipped ".$total." time(s)", 'private');
           		break;

           	default :

           		$this->response("Invalid Command",'private');
           		break;

		}

	}

	private function response($text, $type){
		$this->response_text = $text;
		$this->response_type = $type;

		$responder = new Responder($this);
	}

	private function is_user_name(){
		if(strpos($this->text,'@') > 0 || strpos($this->text,'@') === false){
			return false;
		}
		return true;
	}

}

?>