<?php

class TipBot {

	/*
		{
		    "@username": {
		        "received": 0,
		        "last_received_date": "2015-01-01 00:00:00",
		        "last_received_user": "@username2",
		        "sent": 0,
		        "last_sent_date": "2015-01-01 00:00:00",
		        "last_sent_user": "@username2"
		    },
		    "@username2": {
		        "received": 0,
		        "last_received_date": "2015-01-01 00:00:00",
		        "last_received_user": "@username",
		        "sent": 0,
		        "last_sent_date": "2015-01-01 00:00:00",
		        "last_sent_user": "@username"
		    }
		}
	 */
	
	private $filename = 'tips.json';
	private $botName = 'Tip Bot';
	private $botIcon = ':heavy_dollar_sign:';
	private $rateLimit;

	public function __construct($data){
		$payload = new ProcessPayload($data);
		$this->processTip($payload);
	}

	public function processTip(&$data){

		switch(true){
			case $data->isUserName() :
				$recipient = $data->getPayloadText();
				if($data->getUserName() == $recipient){
					$responder = new Responder($this->botName,$this->botIcon,"You can't tip yourself!",$data->getChannelName(),0);
					return;
				} else {
					$this->logTip($data);
					$text = '*'.$data->getUserName().'* has tipped *'.$recipient.'*'.' bringing their total to '.$this->retrieveTotal($recipient).' tips';
					$responder = new Responder($this->botName,$this->botIcon,$text,$data->getChannelName(),1);
				}
			break;
			case $data->getPayloadText() == 'total' :
				$total = $this->retrieveTotal($data->getUserName());
				$responder = new Responder($this->botName,$this->botIcon,"You've been tipped ".$total." time(s)",$data->getChannelName(),0);

			break;
			default :
				$responder = new Responder($this->botName,$this->botIcon,"Invalid command",$data->getChannelName(),0);
			break;
		}
	}


	private function logTip($payload){

		date_default_timezone_set('UTC');

		$data = json_decode(file_get_contents($this->filename), true);

		if(!$data){
			$data = array();
		}

		$this->rateLimit = strtotime('now');

		if(!array_key_exists($payload->getUserName(), $data)){
			$data[$payload->getUserName()]['sent'] = 0;
			$data[$payload->getUserName()]['received'] = 0;
			$data[$payload->getUserName()]['created'] = date('Y-m-d H:i:s');
			$data[$payload->getUserName()]['last_sent_date'] = 0;
			$data[$payload->getUserName()]['last_sent_user'] = $payload->getPayloadText();
			$data[$payload->getUserName()]['last_received_date'] = 0;
		}
		if(!array_key_exists($payload->getPayloadText(), $data)){
			$data[$payload->getPayloadText()]['sent'] = 0;
			$data[$payload->getPayloadText()]['received'] = 0;
			$data[$payload->getPayloadText()]['created'] = date('Y-m-d H:i:s');
			$data[$payload->getPayloadText()]['last_received_date'] = 0;
			$data[$payload->getPayloadText()]['last_received_user'] = $payload->getUserName();
			$data[$payload->getPayloadText()]['last_sent_date'] = 0;
		}
		if(strtotime($data[$payload->getUserName()]['last_sent_date'].' + 10 seconds') <= $this->rateLimit){
			$data[$payload->getUserName()]['sent'] += 1;
			$data[$payload->getUserName()]['last_sent_date'] = date('Y-m-d H:i:s');
			$data[$payload->getUserName()]['last_sent_user'] = $payload->getPayloadText();
			$data[$payload->getPayloadText()]['received'] += 1;
			$data[$payload->getPayloadText()]['last_received_date'] = date('Y-m-d H:i:s');
			$data[$payload->getPayloadText()]['last_received_user'] = $payload->getUserName();
		} else {
			echo "You can't tip that fast. Please wait 10 seconds";
			exit;
		}
		file_put_contents($this->filename, json_encode($data));

	}

	private function retrieveTotal($userName){
		$data = json_decode(file_get_contents($this->filename), true);

		if($data[$userName]['received']){
			return $data[$userName]['received'];
		} else return 0;

	}

}

?>
