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
				}
				$responder = new Responder($this->botName,$this->botIcon,'*'.$data->getUserName().'* has tipped *'.$recipient.'*',$data->getChannelName(),1);
				//$this->logTip($data);
			break;
			/*case $data->text == 'total' :
				$data = retrieveTotal($this->userName);
				$data->response("You've been tipped ".$total." time(s)",$this->botName,$this->botIcon,0);
			break;*/
			default :
				$responder = new Responder($this->botName,$this->botIcon,"Invalid command",$data->getChannelName(),0);
			break;
		}
	}


	public function logTip(&$payload){

		date_default_timezone_set('UTC');

		$data = json_decode(file_get_contents($this->filename), true);

		if(!$data){
			$data = array();
		}

		$this->rateLimit = strtotime('now');

		//TODO: CLEAN THIS UP
		//
		if(!array_key_exists($payload->userName, $data)){
			$data[$payload->userName]['sent'] = 0;
			$data[$payload->userName]['received'] = 0;
			$data[$payload->userName]['created'] = date('Y-m-d H:i:s');
			$data[$payload->userName]['last_sent_date'] = 0;
			$data[$payload->userName]['last_sent_user'] = $payload->recipient;
			$data[$payload->userName]['last_received_date'] = 0;
		}
		if(!array_key_exists($payload->recipient, $data)){
			$data[$payload->recipient]['sent'] = 0;
			$data[$payload->recipient]['received'] = 0;
			$data[$payload->recipient]['created'] = date('Y-m-d H:i:s');
			$data[$payload->recipient]['last_received_date'] = 0;
			$data[$payload->recipient]['last_received_user'] = $payload->userName;
			$data[$payload->recipient]['last_sent_date'] = 0;
		}
		if(strtotime($data[$payload->userName]['last_sent_date'].' + 10 seconds') <= $this->rateLimit){
			$data[$payload->userName]['sent'] += 1;
			$data[$payload->userName]['last_sent_date'] = date('Y-m-d H:i:s');
			$data[$payload->userName]['last_sent_user'] = $payload->recipient;
			$data[$payload->recipient]['received'] += 1;
			$data[$payload->recipient]['last_received_date'] = date('Y-m-d H:i:s');
			$data[$payload->recipient]['last_received_user'] = $payload->userName;
		}

		file_put_contents($this->filename, json_encode($data));

	}

	public function retrieveTotal($userName){
		$data = json_decode(file_get_contents($this->filename), true);

		if($data[$userName]['received']){
			return $data[$userName]['received'];
		} else return 0;

	}

}

?>
