<?php

class PunBot {

	private $botName = 'Pun Bot';

	private $botIcon = ':unamused:';

	private $fileName = 'punbot.json';

	public function __construct($data){

                $payload = new ProcessPayload($data);
                $userpoints = explode(" ",$payload->getPayloadText());

                if($payload->isUserName($userpoints[0]) && is_numeric($userpoints[1]) && $userpoints[1] >= 0 &&  $userpoints[1] <= 10 && $userpoints[0] != $payload->getUserName()){
			$this->logPun($payload);
			$text = '*'.$payload->getUserName().'* has rated *'.$userpoints[0].'\'s* pun '.$userpoints[1].'/10';
			$text = $text.' bringing their average to '.$this->retrieveRating($userpoints[0]).'/10';
			$payload->setResponseText($text);
			$responder = new Responder($this->botName, $this->botIcon, $payload->getResponseText(), $payload->getChannelName(), 1);
		} if($payload->getPayloadText() == 'total'){
			$payload->setResponseText("You've been rated ".$this->retrieveRatingCount($payload->getUserName())." times for an average of ".$this->retrieveRating($payload->getUserName())."/10");
			$responder = new Responder($this->botName, $this->botIcon, $payload->getResponseText(), $payload->getChannelName(), 0);
		} else {
			$responder = new Responder($this->botName, $this->botIcon, "Invalid Command", $payload->getChannelName(), 0);
		}
	}

        private function logPun(&$payload){

                date_default_timezone_set('UTC');

                $data = json_decode(file_get_contents($this->fileName), true);

                if(!$data){
                        $data = array();
                }

                $this->rateLimit = strtotime('now');

                $userpoints = explode(" ",$payload->getPayloadText());

                if(!array_key_exists($userpoints[0], $data)){
                        $data[$userpoints[0]]['rating'] = 5 + (($userpoints[1] - 5) / 1);
                        $data[$userpoints[0]]['ratings_received'] = 2;
                        $data[$userpoints[0]]['created'] = date('Y-m-d H:i:s');
                        $data[$userpoints[0]]['last_received_date'] = 0;
                } else {
			$currentRating = $this->retrieveRating($userpoints[0]);
			$currentRatingCount = $this->retrieveRatingCount($userpoints[0]);

                        $data[$userpoints[0]]['rating'] = $currentRating + (($userpoints[1] - $currentRating) / $currentRatingCount);
                        $data[$userpoints[0]]['ratings_received'] += 1;
                        $data[$userpoints[0]]['last_received_date'] = date('Y-m-d H:i:s');
		}
                file_put_contents($this->fileName, json_encode($data));

        }

        private function retrieveRating($userName){
                $data = json_decode(file_get_contents($this->fileName), true);

                if($data[$userName]['rating']){
                        return round($data[$userName]['rating'],2);
                } else return 0;

        }

        private function retrieveRatingCount($userName){
                $data = json_decode(file_get_contents($this->fileName), true);

                if($data[$userName]['ratings_received']){
                        return $data[$userName]['ratings_received'];
                } else return 0;

        }


}
?>
