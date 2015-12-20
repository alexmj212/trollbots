<?php

class TipBot {

	private $botName = 'Tip Bot';
	private $botIcon = ':heavy_dollar_sign:';
	private $payload;
	private $user;
	
	public function __construct($data){
		$this->payload = new ProcessPayload($data);

		switch(true){
			case $this->payload->isUserName() :
				$this->user = $this->payload->getPayloadText();
				if($this->payload->getUserName() == $this->user){
					$responder = new Responder($this->botName,$this->botIcon,"You can't tip yourself!",$this->payload->getChannelName(),0);
					return;
				} else {
					$this->logTip();
					$text = '*'.$this->payload->getUserName().'* has tipped *'.$this->user.'*'.' bringing their total to '.$this->retrieveTips($this->user).' tips';
					$responder = new Responder($this->botName,$this->botIcon,$text,$this->payload->getChannelName(),1);
				}
			break;
			case $this->payload->getPayloadText() == 'total' :
				$total = $this->retrieveTotal($this->payload->getUserName());
				$responder = new Responder($this->botName,$this->botIcon,"You've been tipped ".$total." time(s)",$this->payload->getChannelName(),0);

			break;
			default :
				$responder = new Responder($this->botName,$this->botIcon,"Invalid command",$this->payload->getChannelName(),0);
			break;
		}
	}


	private function logTip(){

                date_default_timezone_set('UTC');
                $database = new dataSource();
                $collection = $database->getCollection("tipbot");

                //Does this team exist?
                if($document = $collection->findOne(array("team_id"=>$this->payload->getTeamId()))){
                        //Yes this team exists
                        $users = $document["users"];
                        //Does this user exist?
                        if(array_key_exists($this->user,$users)){
                                //Yes this user exists
                                $users[$this->user]['received'] += 1;
                                $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
                        } else {
                                //No, add this user, start them at 500
                                $users[$this->user]['received'] = 1;
                                $users[$this->user]['created'] = date('Y-m-d H:i:s');
                                $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
                        }
                        //Save the new information
                        $document["users"] = $users;
                        $collection->update(array("team_id"=>$this->payload->getTeamId()),$document);
                } else {
                        //No, this team doesn't exist
                        $team = array(
                                "team_id"=>$this->payload->getTeamId(),
                                "users"=>array(
                                        $this->user => array(
                                                "received" => 1,
                                                "created" => date('Y-m-d H:i:s'),
                                                "last_received_date" => date('Y-m-d H:i:s')
                                        )
                                )
                        );
                        $collection->insert($team);
                }

	}

	private function retrieveTips($userName){
                $database = new dataSource();
                $collection = $database->getCollection("tipbot");

                if($document = $collection->findOne(array("team_id"=>$this->payload->getTeamId()))){
                        if(array_key_exists($this->user,$document["users"])){
                                return $document["users"][$this->user]['received'];
                        } else return 0;
                } else return 0;
	}

}

?>
