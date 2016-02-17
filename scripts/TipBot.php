<?php

/**
 * TipBot.php
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
 * Class TipBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class TipBot extends Bot
{


    /**
     * TipBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        $this->name           = 'Tip Bot';
        $this->icon           = ':heavy_dollar_sign:';
        $this->collectionName = 'tipbot';

        $post = null;

        $this->teamId = $payload->getTeamId();

        // Determine the type of request and form the appropriate response.
        if (Payload::isUserName($payload->getText()) === true) {
            // Verify a valid username was provided.
            $this->user = $payload->getText();
            if ($payload->getUserName() === $this->user) {
                // Stop fools from trying to tip themselves.
                $post = new Post(
                    $this->name,
                    $this->icon,
                    'You can\'t tip yourself!',
                    $payload->getChannelName(),
                    false
                );
            } else {
                // Log the new Tip.
                $this->_logTip();
                // Build Response.
                $response  = '*'.$payload->getUserName().'* has tipped *'.$this->user.'*';
                $response .= ' bringing their total to '.$this->_retrieveTips($this->user).' tips';
                // Generate new Post.
                $post = new Post($this->name, $this->icon, $response, $payload->getChannelName(), true);
            }
        } else if ($payload->getText() === 'total') {
            // Return the total number of tips of the requester.
            $this->user = $payload->getUserName();
            // Retrieve the total amount.
            $total = $this->_retrieveTips();
            // Build response.
            $response = 'You\'ve been tipped '.$total.' time(s)';
            // Generate new Post.
            $post = new Post($this->name, $this->icon, $response, $payload->getChannelName(), false);
        } else {
            // No matching commands, return invalid.
            $post = new Post($this->name, $this->icon, Post::INVALID_COMMAND, $payload->getChannelName(), false);
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
        $database = new DataSource($this->collectionName);
        // Get the database collection.
        $collection = $database->getCollection();
        // Retrieve the tipbot document.
        $document = $database->retrieveDocument($this->teamId);

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
                $users[$this->user]['received']          += 1;
                $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
            } else {
                // No, add this user.
                $users[$this->user]['received']           = 1;
                $users[$this->user]['created']            = date('Y-m-d H:i:s');
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
                                                   'received'           => 1,
                                                   'created'            => date('Y-m-d H:i:s'),
                                                   'last_received_date' => date('Y-m-d H:i:s'),
                                                  ),
                                  ),
                    );

            try {
                $collection->insertOne($team);
            } catch (MongoException $e){
                echo 'Unable to insert team '.$this->teamId.': '.$e->getMessage();
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
            $user = $this->user;
        }

        // Retrieve the database collection.
        $database = new DataSource($this->collectionName);
        // Retrieve the tipbot document.
        $document = $database->retrieveDocument($this->teamId);

        if (property_exists($document, 'team_id') === true
            && $document->team_id === $this->teamId
            && property_exists($document, 'users') === true
        ) {
            return (int) $document->users[$user]['received'];
        } else {
            return 0;
        }

    }//end _retrieveTips()


}//end class
