<?php

/**
 * Class ChannelPoliceBot
 */
class ChannelPoliceBot {

	private $botName = 'Channel Police';

	private $botIcon = ':warning:';

	public function __construct($data){

		$payload = new Payload($data);

		if($payload->isChannel()){
			$payload->setResponseText('*'.$payload->getUserName().'* has requested this discussion be moved to the *'.$payload->getText().'* channel!');
		} else {
			$payload->setResponseText('*'.$payload->getUserName().'* has requested this discussion be moved to the appropriate channel.');
		}
		$responder = new Responder($this->botName, $this->botIcon, $payload->getResponseText(), $payload->getChannelName(), 1);

	}
}
