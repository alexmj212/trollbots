<?php
/**
 * DKPBot.php
 *
 * PHP version 5
 *
 * @category Script
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

/**
 * Class DKPBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class DKPBot extends Bot
{

    /**
     * The ID of the team
     *
     * @var int
     */
    private $_points;


    /**
     * DKPBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        $this->name           = 'DKP Bot';
        $this->icon           = ':dragon_face:';
        $this->collectionName = 'dkpbot';

        $post = null;

        $this->teamId = $payload->getTeamId();

        $userPoints = explode(' ', $payload->getText());
        if (count($userPoints) === 2) {
            $this->user    = $userPoints[0];
            $this->_points = (int) $userPoints[1];
        }

        if (is_numeric($this->_points) === true
            && $this->_points >= -10
            && $this->_points !== 0
            && $this->_points <= 10
            && Payload::isUserName($this->user) === true
        ) {
            if ($this->user === $payload->getUserName()) {
                $this->_points = (0 - $this->_points);
                $this->_logDKP();
                $response  = '*'.$payload->getUserName().'* has attempted to grant themselves DKP ';
                $response .= 'but instead receives -'.abs($this->_points).'DKP';
                $response .= '\n'.$this->user.' now has '.$this->_retrieveDKP($this->user).'DKP';

                $post = new Post($this->name, $this->icon, $response, $payload->getChannelName(), true);
            } else {
                $this->_logDKP();
                $response  = '*'.$payload->getUserName().'* has given *'.$this->user.'* '.$this->_points.'DKP';
                $response .= '\n'.$this->user.' now has '.$this->_retrieveDKP($this->user).'DKP';

                $post = new Post($this->name, $this->icon, $response, $payload->getChannelName(), true);
            }
        } else if ($payload->getText() === 'score') {
            $response = 'You have '.$this->_retrieveDKP($payload->getUserName()).'DKP';

            $post = new Post($this->name, $this->icon, $response, $payload->getChannelName(), false);
        } else if ($payload->getText() === 'rank') {
            $post = new Post($this->name, $this->icon, '', $payload->getChannelName(), false);
            $post->addAttachment($this->_ranking());
        } else {
            $post = new Post($this->name, $this->icon, 'Invalid command', $payload->getChannelName(), false);
        }//end if

        $responder = new Responder($post);
        $responder->respond();

    }//end __construct()


    /**
     * Log the DKP sent by the user
     *
     * @return void
     */
    private function _logDKP()
    {

        date_default_timezone_set('UTC');
        // Retrieve the database collection.
        $database = new DataSource($this->collectionName);
        // Get the database collection.
        $collection = $database->getCollection();
        // Retrieve the punbot document.
        $document = $database->retrieveDocument($this->teamId);

        // Does this team exist?
        if ($document !== null
            && property_exists($document, 'team_id') === true
            && $document->team_id === $this->teamId
            && property_exists($document, 'users') === true
        ) {
            // Yes this team exists.
            $users = $document->users;
            // Does this user exist?
            if (array_key_exists($this->user, $users) === true) {
                // Yes this user exists.
                $users[$this->user]['dkp'] += $this->_points;
                $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
            } else {
                // No, add this user, start them at 500.
                $users[$this->user]['dkp']     = (500 + $this->_points);
                $users[$this->user]['created'] = date('Y-m-d H:i:s');
                $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
            }

            // Save the new information.
            $document->users = $users;
            // Publish update to datasource.
            try {
                $collection->updateOne(array('team_id' => $this->teamId), array('$set' => $document));
            } catch (MongoCursorException $e){
                echo 'Unable to update user '.$this->user.' with team '.$this->teamId.': '.$e->getMessage();
            }
        } else {
            // No, this team doesn't exist.
            $team = array(
                     'team_id' => $this->teamId,
                     'users'   => array(
                                   $this->user => array(
                                                   'dkp   '             => 500 + $this->_points,
                                                   'created'            => date('Y-m-d H:i:s'),
                                                   'last_received_date' => date('Y-m-d H:i:s'),
                                                  ),
                                  ),
                    );
            try {
                $collection->insertOne($team);
            } catch (MongoException $e){
                echo '\'Unable to insert team '.$this->teamId.': '.$e->getMessage();
            }
        }//end if

    }//end _logDKP()


    /**
     * Build the ranking table of all users
     *
     * @return string
     */
    private function _ranking()
    {
        date_default_timezone_set('UTC');
        // Retrieve the database collection.
        $database = new DataSource($this->collectionName);
        // Retrieve the dkpbot document.
        $document = $database->retrieveDocument($this->teamId);

        $document->users = Bot::sortUserList($document->users, 'dkp', SORT_DESC);

        $attachment = array(
                       'title' => 'DKP Leaderboard',
                       'text'  => '',
                      );

        foreach ($document->users as $user => $data) {
            $attachment['text'] .= $user.' - ';
            $attachment['text'] .= $data['dkp'].' DKP'.PHP_EOL;
        }

        $attachment['pretext'] = 'If you\'re not listed, you\'ve not received DKP';
        return $attachment;

    }//end _ranking()


    /**
     * Retrieve the DKP of the given user
     *
     * @param string $user the given user
     *
     * @return int
     */
    private function _retrieveDKP($user = null)
    {
        if ($user === null) {
            // Verify user was supplied, otherwise use command user.
            $user = $this->user;
        }

        date_default_timezone_set('UTC');
        // Retrieve the database collection.
        $database = new DataSource($this->collectionName);
        // Retrieve the dkpbot document.
        $document = $database->retrieveDocument($this->teamId);

        // Does this team exist?
        if ($document !== null
            && property_exists($document, 'team_id') === true
            && $document->team_id === $this->teamId
            && property_exists($document, 'users') === true
        ) {
            return $document->users[$user]['dkp'];
        } else {
            return 500;
        }

    }//end _retrieveDKP()


}//end class
