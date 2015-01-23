<?php

class Handler {

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
	
	private $filename;

	private $rate_limit;

	public function __construct(){

		$this->filename = 'tips.json';

	}

	public function log_tip(&$payload){

		date_default_timezone_set('UTC');

		$data = json_decode(file_get_contents($this->filename), true);

		if(!$data){
			$data = array();
		}

		$this->rate_limit = strtotime('now');

		//TODO: CLEAN THIS UP
		//
		if(!array_key_exists($payload->user_name, $data)){
			$data[$payload->user_name]['sent'] = 0;
			$data[$payload->user_name]['created'] = date('Y-m-d H:i:s');
			$data[$payload->user_name]['last_sent_date'] = 0;
			$data[$payload->user_name]['last_sent_user'] = $payload->recipient;
			$data[$payload->user_name]['last_received_date'] = 0;
		}
		if(!array_key_exists($payload->recipient, $data)){
			$data[$payload->recipient]['received'] = 0;
			$data[$payload->recipient]['created'] = date('Y-m-d H:i:s');
			$data[$payload->recipient]['last_received_date'] = 0;
			$data[$payload->recipient]['last_received_user'] = $payload->user_name;
			$data[$payload->recipient]['last_sent_date'] = 0;
		}
		if(strtotime($data[$payload->user_name]['last_sent_date'].' + 10 seconds') <= $this->rate_limit){
			$data[$payload->user_name]['sent'] += 1;
			$data[$payload->user_name]['last_sent_date'] = date('Y-m-d H:i:s');
			$data[$payload->user_name]['last_sent_user'] = $payload->recipient;
			$data[$payload->recipient]['received'] += 1;
			$data[$payload->recipient]['last_received_date'] = date('Y-m-d H:i:s');
			$data[$payload->recipient]['last_received_user'] = $payload->user_name;
		}



		file_put_contents($this->filename, json_encode($data));

	}

	public function retrieve_total($user_name){
		$data = json_decode(file_get_contents($this->filename), true);

		if($data[$user_name]['received']){
			return $data[$user_name]['received'];
		} else return 0;

	}

}

?>