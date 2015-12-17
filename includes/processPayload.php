<?php

/**  
 * Tip Bot for Slack
 * Alex Johnson
 * Payload Processor
 */

/**
 * Description:
 * * Construct the object to retain event data
 * * Parse the recieved text and determine it's validity
 * * Test to see if the text is a username
 */

class ProcessPayload {

	private $token;
	private $teamId;
	private $channelId;
	private $channelName;
	private $userId;
	private $userName;
	private $text;
	private $responseText;

	public function __construct ($data){
		
		$this->token = $data['token'];
		$this->teamId = $data['team_id'];
		$this->channelId = $data['channel_id'];
		$this->channelName = '#'.$data['channel_name'];
		$this->userId = $data['user_id'];
		$this->userName = '@'.strtolower($data['user_name']);
		$this->text = strtolower($data['text']);

	}

	public function setResponseText($responseText){
		$this->responseText = $responseText;
	}

	public function getResponseText(){
		return $this->responseText;
	}

	public function getChannelName(){
		return $this->channelName;
	}

	public function getUserName(){
		return $this->userName;
	}

	public function getPayloadText(){
		return $this->text;
	}

	public function isUserName(){
		if(func_get_args()){
			$args = func_get_args();
			$text = $args[0];	
		} else {
			$text = $this->text;
		}
		
		if(strpos($text,'@') > 0 || strpos($text,'@') === false || !ctype_alnum(substr($text, 1))){
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
