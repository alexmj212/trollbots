<?php

class DKPBot {

	private $botName = 'DKP Bot';

	private $botIcon = ':dragon_face:';

	private $fileName = 'dkp.json';

	private $user;

	private $points;

	public function __construct($data){

		$payload = new ProcessPayload($data);
		$userpoints = explode(" ",$payload->getPayloadText());
		if(sizeof($userpoints) == 2){
			$this->user = $userpoints[0];
			$this->points = intval($userpoints[1]);
		} else {
			echo "Invalid Command";
			exit;
		}

		if($payload->isUserName($this->user) &&  is_numeric($this->points) && $this->points != 0 && $this->points <= 50){
			if($this->user == $payload->getUserName()){
				$this->points = 0 - $this->points;
				$this->logDKP($this->user,$this->points);
				$text = '*'.$payload->getUserName().'* has attempted to grant themselves DKP but instead receives -'.abs($this->points).'DKP';
				$text = $text."\n".$this->user." now has ".$this->retrieveDKP($this->user)."DKP";
				$payload->setResponseText($text);
			} else {
				$this->logDKP($this->user,$this->points);
				$text = '*'.$payload->getUserName().'* has given *'.$this->user.'* '.$this->points.'DKP';
				$text = $text."\n".$this->user." now has ".$this->retrieveDKP($this->user)."DKP";
				$payload->setResponseText($text);
			}
			$responder = new Responder($this->botName, $this->botIcon, $payload->getResponseText(), $payload->getChannelName(), 1);
		} else {
			$responder = new Responder($this->botName, $this->botIcon, "Invalid command", $payload->getChannelName(), 0);
		}
	}

        private function logDKP(&$payload){

                date_default_timezone_set('UTC');

                $data = json_decode(file_get_contents($this->fileName), true);

                if(!$data){
                        $data = array();
                }

                $this->rateLimit = strtotime('now');

                if(!array_key_exists($this->user, $data)){
                        $data[$this->user]['dkp'] = 500 + $this->points;
                        $data[$this->user]['created'] = date('Y-m-d H:i:s');
                        $data[$this->user]['last_received_date'] = 0;
                } else {
                        $data[$this->user]['dkp'] += $this->points;
                        $data[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
                }
                file_put_contents($this->fileName, json_encode($data));

        }

        private function retrieveDKP($userName){
                $data = json_decode(file_get_contents($this->fileName), true);

                if($data[$userName]['dkp']){
                        return round($data[$userName]['dkp'],2);
                } else return 0;

        }

}
?>
