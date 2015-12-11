<?php

class TriggerBot {

	private $botName = 'Trigger Bot';

	private $botIcon = ':bangbang:';

	public function __construct($data){

		$payload = new ProcessPayload($data);

		if($payload->isUserName()){
			$payload->setResponseText('*'.$payload->getUserName()."* has been triggered by *".$payload->getPayloadText()."*!");
		} else {
			$payload->setResponseText('*'.$payload->getUserName()."* has been triggered!");
		}
		$responder = new Responder($this->botName, $this->botIcon, $payload->getResponseText(), $payload->getChannelName(), 1);

	}
}
?>
