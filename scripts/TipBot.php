<?php

/**
 * TipBot.php
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
 * Class TipBot
 *
 * @category Bot
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

class TipBot
{

    /**
     * The name of the Bot
     *
     * @var string
     */
    private $_name = 'Tip Bot';

    /**
     * The icon to represent the bot
     *
     * @var string
     */
    private $_icon = ':heavy_dollar_sign:';

    /**
     * The user receiving or giving the tip
     *
     * @var string
     */
    private $_user;

    /**
     * The ID of the team
     *
     * @var mixed
     */
    private $_teamId;

    /**
     * The name of the bots collection
     *
     * @var string
     */
    private $_collectionName = 'tipbot';


    /**
     * TipBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        $post = null;

        $this->_teamId = $payload->getTeamId();

        // Determine the type of request and form the appropriate response.
        if ($payload->isUserName() === true) {
            // Verify a valid username was provided and encrypt it.
            $this->_user = $payload->getText();
            if ($payload->getUserName() === $this->_user) {
                // Stop fools from trying to tip themselves.
                $post = new Post(
                    $this->_name,
                    $this->_icon,
                    'You can\'t tip yourself!',
                    $payload->getChannelName(),
                    false
                );
            } else {
                // Log the new Tip.
                $this->_logTip();
                // Build Response.
                $response  = '*'.$payload->getUserName().'* has tipped *'.$this->_user.'*';
                $response .= ' bringing their total to '.$this->_retrieveTips($this->_user).' tips';
                // Generate new Post.
                $post = new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), true);
            }
        } else if ($payload->getText() === 'total') {
            // Return the total number of tips of the requester.
            $this->_user = $payload->getUserName();
            // Retrieve the total amount.
            $total = $this->_retrieveTips();
            // Build response.
            $response = 'You\'ve been tipped '.$total.' time(s)';
            // Generate new Post.
            $post = new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), false);
        } else {
            // No matching commands, return invalid.
            $post = new Post($this->_name, $this->_icon, 'Invalid command', $payload->getChannelName(), false);
        }//end if

        // Submit the response.
        $responder = new Responder($post);
        $responder->respond();

    }//end __construct()


    /**
     * Log the new tip.
     *
     * @return void
     */
    private function _logTip()
    {

        date_default_timezone_set('UTC');
        // Retrieve the database collection.
        $database = new DataSource($this->_collectionName);
        // Get the database collection.
        $collection = $database->getCollection();
        // Retrieve the tipbot document.
        $document = $database->retrieveDocument($this->_teamId);

        if (is_array($document) === true && array_key_exists('users', $document) === true) {
            // Yes this team exists.
            $users = $document['users'];
            // Does this user exist?
            if (array_key_exists($this->_user, $users) === true) {
                // Yes this user exists.
                $users[$this->_user]['received']          += 1;
                $users[$this->_user]['last_received_date'] = date('Y-m-d H:i:s');
            } else {
                // No, add this user.
                $users[$this->_user]['received']           = 1;
                $users[$this->_user]['created']            = date('Y-m-d H:i:s');
                $users[$this->_user]['last_received_date'] = date('Y-m-d H:i:s');
            }

            // Save the new information.
            $document['users'] = $users;

            try {
                $collection->update(array('team_id' => $this->_teamId), $document);
                throw new MongoException('Unable to update user '.$this->_user.' with team '.$this->_teamId);
            } catch (MongoException $e){
                echo 'Mongo Update Exception: '.$e->getMessage();
            }
        } else {
            // No, this team doesn't exist.
            $team = array(
                     'team_id' => $this->_teamId,
                     'users'   => array(
                                   $this->_user => array(
                                                    'received'           => 1,
                                                    'created'            => date('Y-m-d H:i:s'),
                                                    'last_received_date' => date('Y-m-d H:i:s'),
                                                   ),
                                  ),
                    );

            try {
                $collection->insert($team);
                throw new MongoException('Unable to insert team '.$this->_teamId);
            } catch (MongoException $e){
                echo 'Mongo Update Exception: '.$e->getMessage();
            }
        }//end if

    }//end _logTip()


    /**
     * Retrieve the total number of tips the given user has received
     *
     * @param string $user the given users tips we seek
     *
     * @return int
     */
    private function _retrieveTips($user = null)
    {

        if ($user === null) {
            // Verify user was supplied, otherwise use command user.
            $user = $this->_user;
        }

        // Retrieve the database collection.
        $database = new DataSource($this->_collectionName);
        // Retrieve the tipbot document.
        $document = $database->retrieveDocument($this->_teamId);

        if (is_array($document) === true && array_key_exists($user, $document['users']) === true) {
            return (int) $document['users'][$user]['received'];
        } else {
            return 0;
        }

    }//end _retrieveTips()


}//end class
