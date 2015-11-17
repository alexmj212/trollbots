<?php

/**  
 * Tip Bot for Slack
 * Alex Johnson
 * Payload Processor
 */

include 'fileHandler.php';
include 'responseHandler.php';

/**
 * Description:
 * * Construct the object to retain event data
 * * Parse the recieved text and determine it's validity
 * * Test to see if the text is a username
 */

class ProcessPayload {

	public $token;
	public $teamId;
	public $channelId;
	public $channelName;
	public $userId;
	public $userName;
	public $text;
	public $recipient;
	public $payloadType;
	public $responseText;
	public $responseType;

	public function __construct ($data){

		$this->token = $data['token'];
		$this->teamId = $data['team_id'];
		$this->channelId = $data['channel_id'];
		$this->channelName = '#'.$data['channel_name'];
		$this->userId = $data['user_id'];
		$this->userName = '@'.strtolower($data['user_name']);
		$this->text = strtolower($data['text']);
		//$this->payloadType = $this->parseCommand();

	}

	public function parseCommand(){

		$file = new Handler();

		switch(true){

			case $this->isUserName() :

				$this->recipient = $this->text;
			    if($this->userName == $this->recipient){
                    $this->response("You can't tip yourself!",'private');
                    return;
                }
                $this->response($this->userName.' has tipped '.$this->recipient,'channel');
                $file->logTip($this);
            	break;

			case $this->text == 'total' :

           		$total = $file->retrieveTotal($this->userName);
           		$this->response("You've been tipped ".$total." time(s)", 'private');
           		break;

           	default :

           		$this->response("Invalid Command",'private');
           		break;

		}

	}

	public function response($text, $type){
		$this->responseText = $text;
		$this->responseType = $type;

		$responder = new Responder($this);
	}

	public function isUserName(){
		if(strpos($this->text,'@') > 0 || strpos($this->text,'@') === false || !ctype_alnum(substr($this->text, 1))){
			return false;
		}
		return true;
	}

        public function isChannel(){
                if(strpos($this->text,'#') > 0 || strpos($this->text,'#') === false || !ctype_alnum(substr($this->text, 1))){
                        return false;
                }
                return true;
        }


}

?>
