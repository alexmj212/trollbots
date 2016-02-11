<?php
/**
 * DKPBot.php
 *
 * PHP version 5
 *
 * @category Script
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

/**
 * Class DKPBot
 *
 * @category Bot
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */
class DKPBot
{

    /**
     * The name of the bot
     *
     * @var string
     */
    private $_name = 'DKP Bot';

    /**
     * The icon to represent the bot
     *
     * @var string
     */
    private $_icon = ':dragon_face:';

    /**
     * The user receiving or giving the dkp
     *
     * @var
     */
    private $_user;

    /**
     * The ID of the team
     *
     * @var int
     */
    private $_points;

    /**
     * The name of the bots collection
     *
     * @var string
     */
    private $_collectionName = 'dkpbot';


    /**
     * DKPBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        $post = null;

        $this->_teamId = $payload->getTeamId();

        $userPoints = explode(' ', $payload->getText());
        if (count($userPoints) === 2) {
            $this->_user   = $userPoints[0];
            $this->_points = (int) $userPoints[1];
        }

        if (is_numeric($this->_points) === true
            && $this->_points >= -10
            && $this->_points !== 0
            && $this->_points <= 10
        ) {
            if ($this->_user === $payload->getUserName()) {
                $this->_points = (0 - $this->_points);
                $this->_logDKP();
                $response  = '*'.$payload->getUserName().'* has attempted to grant themselves DKP ';
                $response .= 'but instead receives -'.abs($this->_points).'DKP';
                $response .= '\n'.$this->_user.' now has '.$this->_retrieveDKP($this->_user).'DKP';

                $post = new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), true);
            } else {
                $this->_logDKP();
                $response  = '*'.$payload->getUserName().'* has given *'.$this->_user.'* '.$this->_points.'DKP';
                $response .= '\n'.$this->_user.' now has '.$this->_retrieveDKP($this->_user).'DKP';

                $post = new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), true);
            }
        } else if ($payload->getText() === 'score') {
            $response = 'You have '.$this->_retrieveDKP($payload->getUserName()).'DKP';

            $post = new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), false);
        } else if ($payload->getText() === 'rank') {
            $post = new Post($this->_name, $this->_icon, $this->_ranking(), $payload->getChannelName(), false);
        } else {
            $post = new Post($this->_name, $this->_icon, 'Invalid command', $payload->getChannelName(), false);
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
        $database = new DataSource($this->_collectionName);
        // Get the database collection.
        $collection = $database->getCollection();
        // Retrieve the punbot document.
        $document = $database->retrieveDocument($this->_teamId);

        // Does this team exist?
        if (is_array($document) === true && array_key_exists('users', $document) === true) {
            // Yes this team exists.
            $users = $document['users'];
            // Does this user exist?
            if (array_key_exists($this->_user, $users) === true) {
                // Yes this user exists.
                $users[$this->_user]['dkp'] += $this->_points;
                $users[$this->_user]['last_received_date'] = date('Y-m-d H:i:s');
            } else {
                // No, add this user, start them at 500.
                $users[$this->_user]['dkp']     = (500 + $this->_points);
                $users[$this->_user]['created'] = date('Y-m-d H:i:s');
                $users[$this->_user]['last_received_date'] = date('Y-m-d H:i:s');
            }

            // Save the user document.
            $document['users'] = $users;
            // Publish update to datasource.
            try {
                $collection->update(array('team_id' => $this->_teamId), $document);
            } catch (MongoCursorException $e){
                echo '\'Unable to update user \'.$this->_user.\' with team \'.$this->_teamId: '.$e->getMessage();
            }
        } else {
            // No, this team doesn't exist.
            $team = array(
                     'team_id' => $this->_teamId,
                     'users'   => array(
                                   $this->_user => array(
                                                    'rating'             => 500 + $this->_points,
                                                    'created'            => date('Y-m-d H:i:s'),
                                                    'last_received_date' => date('Y-m-d H:i:s'),
                                                   ),
                                  ),
                    );
            try {
                $collection->insert($team);
            } catch (MongoException $e){
                echo '\'Unable to insert team '.$this->_teamId.': '.$e->getMessage();
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
        $database = new DataSource($this->_collectionName);
        // Retrieve the dkpbot document.
        $document = $database->retrieveDocument($this->_teamId);

        // Preserve array keys for sorting.
        foreach ($document['users'] as $username => $user) {
            $document['users'][$username]['username'] = $username;
        }

        // Sort in desc by DKP.
        usort(
            $document['users'],
            function ($a, $b) {
                return ($b['dkp'] - $a['dkp']);
            }
        );

        $leaderBoard = '*DKP Leaderboard*\n';
        foreach ($document['users'] as $user) {
            $leaderBoard .= $user['dkp'].' DKP\t\t';
            $leaderBoard .= '\n';
        }

        $leaderBoard .= 'If you\'re not listed, you\'ve not received DKP';
        return $leaderBoard;

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
            $user = $this->_user;
        }

        date_default_timezone_set('UTC');
        // Retrieve the database collection.
        $database = new DataSource($this->_collectionName);
        // Retrieve the dkpbot document.
        $document = $database->retrieveDocument($this->_teamId);

        if (is_array($document) === true && array_key_exists($this->_user, $document['users']) === true) {
            return $document['users'][$user]['dkp'];
        } else {
            return 500;
        }

    }//end _retrieveDKP()


}//end class
