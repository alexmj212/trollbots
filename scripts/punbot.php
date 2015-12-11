<?php

class PunBot {

	private $botName = 'Pun Bot';

	private $botIcon = ':unamused:';

	public function __construct($data){

		$payload = new ProcessPayload($data);
		if(is_numeric($payload->getPayloadText()) && $payload->getPayloadText() >= 0 &&  $payload->getPayloadText() <= 10){
			$payload->setResponseText('*'.$payload->getUserName().'* has rated that pun '.$payload->getPayloadText().'/10');
			$responder = new Responder($this->botName, $this->botIcon, $payload->getResponseText(), $payload->getChannelName(), 1);
		} else {
			$responder = new Responder($this->botName, $this->botIcon, "Invalid command", $payload->getChannelName(), 0);
		}
	}
}
?>
