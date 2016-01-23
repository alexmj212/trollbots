<?php

/**
 * Class PunBot
 */
class PunBot {

	private $botName = 'Pun Bot';

	private $botIcon = ':unamused:';

	private $payload;

	private $user;
	
	private $rating;

	public function __construct($data){

        $this->payload = new ProcessPayload($data);
        $userRating = explode(' ',$this->payload->getPayloadText());
        if(count($userRating) === 2){
            $this->user = $userRating[0];
            $this->rating = $userRating[1];;
        }

        if($this->payload->isUserName($this->user) && is_numeric($this->rating) && $this->rating >= 0 &&  $this->rating <= 10 && $this->user !== $this->payload->getUserName()){
			$this->logPunRating();
			$text = '*'.$this->payload->getUserName().'* has rated *'.$this->user.'\'s* pun '.$this->rating.'/10';
			$text = $text.' bringing their average to '.$this->retrieveRating($this->user).'/10';
			$this->payload->setResponseText($text);
			$responder = new Responder($this->botName, $this->botIcon, $this->payload->getResponseText(), $this->payload->getChannelName(), 1);
		} if($this->payload->getPayloadText() === 'total'){
			$this->payload->setResponseText('You\'ve been rated '.$this->retrieveRatingCount($this->payload->getUserName()).' times for an average of '.$this->retrieveRating($this->payload->getUserName()).'/10');
			$responder = new Responder($this->botName, $this->botIcon, $this->payload->getResponseText(), $this->payload->getChannelName(), 0);
		} else {
			$responder = new Responder($this->botName, $this->botIcon, 'Invalid Command', $this->payload->getChannelName(), 0);
		}
	}

    /**
     *
     */
    private function logPunRating(){

        date_default_timezone_set('UTC');
        $database = new dataSource();
        $collection = $database->getCollection('punbot');

        //Does this team exist?
        if($document = $collection->findOne(array('team_id'=>$this->payload->getTeamId()))){
            //Yes this team exists
            $users = $document['users'];
            //Does this user exist?
            if(array_key_exists($this->user,$users)){
                //Yes this user exists
                $users[$this->user]['ratings_received'] += 1;
                $users[$this->user]['rating'] += (($this->rating - $users[$this->user]['rating']) / $users[$this->user]['ratings_received']);
                    $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
            } else {
                //No, add this user, create them
                $users[$this->user]['ratings_received'] = 1;
                $users[$this->user]['rating'] = $this->rating;
                $users[$this->user]['created'] = date('Y-m-d H:i:s');
                $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
            }
            //Save the new information
            $document['users'] = $users;
            $collection->update(array('team_id'=>$this->payload->getTeamId()),$document);
        } else {
            //No, this team doesn't exist
            $team = array(
                'team_id'=>$this->payload->getTeamId(),
                'users'=>array(
                    $this->user => array(
                        'ratings_received' => 1,
                        'rating' => $this->rating,
                        'created' => date('Y-m-d H:i:s'),
                        'last_received_date' => date('Y-m-d H:i:s')
                    )
                )
            );
            $collection->insert($team);
        }


    }

    /**
     * @param $userName
     * @return int
     */
    private function retrieveRating($userName){
        $database = new dataSource();
        $collection = $database->getCollection('punbot');

        if($document = $collection->findOne(array('team_id'=>$this->payload->getTeamId()))){
            if(array_key_exists($this->user,$document['users'])){
                return $document['users'][$userName]['rating'];
            } else { return 0; }
        } else { return 0; }
    }

    /**
     * @param $userName
     * @return int
     */
    private function retrieveRatingCount($userName){
        $database = new dataSource();
        $collection = $database->getCollection('punbot');

        if($document = $collection->findOne(array('team_id'=>$this->payload->getTeamId()))){
            if(array_key_exists($this->user,$document['users'])){
                return $document['users'][$userName]['ratings_received'];
            } else { return 0; }
        } else { return 0; }
    }
}


