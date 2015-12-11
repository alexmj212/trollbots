<?php

class ChannelPoliceBot {

	private $botName = 'Channel Police';

	private $botIcon = ':warning:';

	public function __construct($data){

		$payload = new ProcessPayload($data);

		if($payload->isChannel()){
			$payload->setResponseText('*'.$payload->getUserName()."* has requested this discussion be moved to the *".$payload->getPayloadText()."* channel!");
		} else {
			$payload->setResponseText('*'.$payload->getUserName()."* has requested this discussion be moved to the appropriate channel.");
		}
		$responder = new Responder($this->botName, $this->botIcon, $payload->getResponseText(), $payload->getChannelName(), 1);

	}
}
?>
