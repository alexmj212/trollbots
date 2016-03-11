<?php

/**
 * PunBot.php
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
 * Class PunBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class PunBot extends Bot
{

    /**
     * The Pun rating
     *
     * @var float
     */
    private $_rating;


    /**
     * PunBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        parent::__construct($payload);

        $this->name           = 'Pun Bot';
        $this->icon           = ':heavy_dollar_sign:';
        $this->collectionName = 'punbot';

        $post = null;

        $this->teamId = $payload->getTeamId();

        $userRating = explode(' ', $payload->getText());
        if (count($userRating) === 2) {
            $this->user    = $userRating[0];
            $this->_rating = $userRating[1];
        }

        if (is_numeric($this->_rating) === true
            // Pun value greater than 0.
            && $this->_rating >= 0
            // Pun value less than 10.
            &&  $this->_rating <= 10
            // Valid username check.
            && Payload::isUserName($this->user) === true
            // Self-rating check.
            && $this->user !== $payload->getUserName()
        ) {
            // Log the Pun Rating.
            $this->_logPunRating();
            // Build the response string.
            $response  = '*'.$payload->getUserName().'* has rated *'.$this->user.'\'s* pun '.$this->_rating.'/10. ';
            $response .= 'Their average is now '.$this->_retrieveRating($this->user).'/10';
            // Store the Post.
            $post = new Post($this->name, $this->icon, $response, $payload->getChannelName(), POST::RESPONSE_IN_CHANNEL);
        } else if ($payload->getText() === 'total') {
            // Build the requested "total" response string.
            $response  = 'You\'ve been rated '.$this->_retrieveRatingCount($payload->getUserName()).' times ';
            $response .= 'for an average of '.$this->_retrieveRating($payload->getUserName()).'/10';
            // Store the Post.
            $post = new Post($this->name, $this->icon, $response, $payload->getChannelName(), POST::RESPONSE_EPHEMERAL);
        } else {
            // Invalid command.
            $post = new Post($this->name, $this->icon, Post::INVALID_COMMAND, $payload->getChannelName(), POST::RESPONSE_EPHEMERAL);
        }

        $responder = new Responder($post);
        $responder->respond();

    }//end __construct()


    /**
     * Log the Pun sent by the user
     *
     * @return void
     */
    private function _logPunRating()
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
                // Increment the ratings count.
                $users[$this->user]['ratings_received'] += 1;
                // Recalculate average.
                $users[$this->user]['rating'] += (($this->_rating - $users[$this->user]['rating']) / $users[$this->user]['ratings_received']);
                // Update received date.
                $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
            } else {
                // No, add this user, create them.
                $users[$this->user]['ratings_received'] = 1;
                $users[$this->user]['rating']           = $this->_rating;
                $users[$this->user]['created']          = date('Y-m-d H:i:s');
                $users[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
            }//end if

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
                                                   'ratings_received'   => 1,
                                                   'rating'             => $this->_rating,
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

    }//end _logPunRating()


    /**
     * Retrieve the rating of the given user
     *
     * @param string $user the supplied username
     *
     * @return float
     */
    private function _retrieveRating($user = null)
    {

        if ($user === null) {
            // Verify user was supplied, otherwise use command user.
            $user = $this->user;
        }

        // Retrieve the database collection.
        $database = new DataSource($this->collectionName);
        // Retrieve the punbot document.
        $document = $database->retrieveDocument($this->teamId);

        // Does this team exist?
        if ($document !== null
            && property_exists($document, 'team_id') === true
            && $document->team_id === $this->teamId
            && property_exists($document, 'users') === true
        ) {
            // User exists, return their rating, round result.
            return round($document->users[$user]['rating'], 2);
        } else {
            // Team exists but user doesn't.
            return 0;
        }

    }//end _retrieveRating()


    /**
     * Retrieve the rating of the given user
     *
     * @param string $user provided user
     *
     * @return int
     */
    private function _retrieveRatingCount($user = null)
    {

        if ($user === null) {
            // Verify user was supplied, otherwise use command user.
            $user = $this->user;
        }

        // Retrieve the database collection.
        $database = new DataSource($this->collectionName);
        // Retrieve the punbot document.
        $document = $database->retrieveDocument($this->teamId);

        if (is_array($document) === true && array_key_exists($user, $document['users']) === true) {
            // User exists, return their rating, round result.
            return $document['users'][$user]['ratings_received'];
        } else {
            return 0;
        }

    }//end _retrieveRatingCount()


}//end class
