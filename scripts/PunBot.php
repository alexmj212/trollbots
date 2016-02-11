<?php

/**
 * PunBot.php
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
 * Class PunBot
 *
 * @category Bot
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

class PunBot
{

    /**
     * The name of the Bot
     *
     * @var string
     */
    private $_name = 'Pun Bot';

    /**
     * The icon to represent the bot
     *
     * @var string
     */
    private $_icon = ':heavy_dollar_sign:';

    /**
     * The user receiving or giving the Pun rating
     *
     * @var string
     */
    private $_user;

    /**
     * The Pun rating
     *
     * @var float
     */
    private $_rating;

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
    private $_collectionName = 'punbot';


    /**
     * PunBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        $post = null;

        $this->_teamId = $payload->getTeamId();

        $userRating = explode(' ', $payload->getText());
        if (count($userRating) === 2) {
            $this->_user   = $userRating[0];
            $this->_rating = $userRating[1];
        }

        if (is_numeric($this->_rating) === true
            // Pun value greater than 0.
            && $this->_rating >= 0
            // Pun value less than 10.
            &&  $this->_rating <= 10
            // Valid username check.
            // Self-rating check.
            && $this->_user !== $payload->getUserName()
        ) {
            // Log the Pun Rating.
            $this->_logPunRating();
            // Build the response string.
            $response  = '*'.$payload->getUserName().'* has rated *'.$this->_user.'\'s* pun '.$this->_rating.'/10. ';
            $response .= 'Their average is now '.$this->_retrieveRating($this->_user).'/10';
            // Store the Post.
            $post = new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), true);
        } else if ($payload->getText() === 'total') {
            // Build the requested "total" response string.
            $response  = 'You\'ve been rated '.$this->_retrieveRatingCount($payload->getUserName()).' times ';
            $response .= 'for an average of '.$this->_retrieveRating($payload->getUserName()).'/10';
            // Store the Post.
            $post = new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), false);
        } else {
            // Invalid command.
            $post = new Post($this->_name, $this->_icon, 'Invalid Command', $payload->getChannelName(), false);
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
                // Increment the ratings count.
                $users[$this->_user]['ratings_received'] += 1;
                // Recalculate average.
                $users[$this->_user]['rating'] += (($this->_rating - $users[$this->_user]['rating']) / $users[$this->_user]['ratings_received']);
                // Update received date.
                $users[$this->_user]['last_received_date'] = date('Y-m-d H:i:s');
            } else {
                // No, add this user, create them.
                $users[$this->_user]['ratings_received'] = 1;
                $users[$this->_user]['rating']           = $this->_rating;
                $users[$this->_user]['created']          = date('Y-m-d H:i:s');
                $users[$this->_user]['last_received_date'] = date('Y-m-d H:i:s');
            }//end if

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
                                                    'ratings_received'   => 1,
                                                    'rating'             => $this->_rating,
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
            $user = $this->_user;
        }

        // Retrieve the database collection.
        $database = new DataSource($this->_collectionName);
        // Retrieve the punbot document.
        $document = $database->retrieveDocument($this->_teamId);

        if (is_array($document) === true && array_key_exists($user, $document['users']) === true) {
            // User exists, return their rating, round result.
            return round($document['users'][$user]['rating'], 2);
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
            $user = $this->_user;
        }

        // Retrieve the database collection.
        $database = new DataSource($this->_collectionName);
        // Retrieve the punbot document.
        $document = $database->retrieveDocument($this->_teamId);

        if (is_array($document) === true && array_key_exists($user, $document['users']) === true) {
            // User exists, return their rating, round result.
            return $document['users'][$user]['ratings_received'];
        } else {
            return 0;
        }

    }//end _retrieveRatingCount()


}//end class
