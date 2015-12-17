<?php

class DKPBot {

	private $botName = 'DKP Bot';

	private $botIcon = ':dragon_face:';

	public function __construct($data){

		$payload = new ProcessPayload($data);
		$userpoints = explode(" ",$payload->getPayloadText());

		if($payload->isUserName($userpoints[0]) &&  is_numeric($userpoints[1])){
			$payload->setResponseText('*'.$payload->getUserName().'* has given *'.$userpoints[0].'* '.$userpoints[1].' DKP');
			$responder = new Responder($this->botName, $this->botIcon, $payload->getResponseText(), $payload->getChannelName(), 1);
		} else {
			$responder = new Responder($this->botName, $this->botIcon, "Invalid command", $payload->getChannelName(), 0);
		}
	}
}
?>
