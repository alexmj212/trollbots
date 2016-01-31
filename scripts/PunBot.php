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
            && $payload->isUserName($this->_user) === true
            // Self-rating check.
            && $this->_user !== $payload->getUserName()
        ) {
            // Log the Pun Rating.
            $this->_logPunRating();
            // Build the response string.
            $response  = '*'.$payload->getUserName().'* has rated *'.$this->_user.'\'s* pun '.$this->_rating.'/10. ';
            $response .= 'Their average is now '.$this->_retrieveRating($this->_user).'/10';
            // Store the Post.
            $post = new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), 1);
        } else if ($payload->getText() === 'total') {
            // Build the requested "total" response string.
            $response  = 'You\'ve been rated '.$this->_retrieveRatingCount($payload->getUserName()).' times ';
            $response .= 'for an average of '.$this->_retrieveRating($payload->getUserName()).'/10';
            // Store the Post.
            $post = new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), 0);
        } else {
            // Invalid command.
            $post = new Post($this->_name, $this->_icon, 'Invalid Command', $payload->getChannelName(), 0);
        }

        $responder = new Responder($post);
        $responder->respond();

    }//end __construct()


    /**
     * Log the Pun send by the user
     *
     * @return void
     */
    private function _logPunRating()
    {

        date_default_timezone_set('UTC');
        // Initialize datasource.
        $database = new DataSource();
        // Retrieve Punbot collection for querying.
        // TODO: Handle errors
        $collection = $database->getCollection('punbot');

        // Does this team exist?
        if ($document = $collection->findOne(array('team_id' => $this->_teamId)) === true) {
            // Yes this team exists.
            // TODO: Verify array keys.
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
                // Set first count.
                $users[$this->_user]['ratings_received'] = 1;
                // Set first rating.
                $users[$this->_user]['rating'] = $this->_rating;
                // Set created date.
                $users[$this->_user]['created'] = date('Y-m-d H:i:s');
                // Set received date.
                $users[$this->_user]['last_received_date'] = date('Y-m-d H:i:s');

            }//end if

            // TODO: Validate and handle error associated with data updates.
            // Save the new information.
            // Save the user document.
            $document['users'] = $users;
            // Publish update to datasource.
            $collection->update(array('team_id' => $this->_teamId), $document);
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
            // TODO: Validate and handle error associated with data inserts.
            $collection->insert($team);
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
        // Initialize datasource.
        $database = new DataSource();
        // Retrieve punbot collection.
        // TODO: Handle exception
        $collection = $database->getCollection('punbot');

        if ($user === null) {
            // Verify user was supplied, otherwise use command user.
            $user = $this->_user;
        }

        // TODO: Verify array keys.
        if ($document = $collection->findOne(array('team_id' => $this->_teamId)) === true) {
            if (array_key_exists($user, $document['users']) === true) {
                // User exists, return their rating, round result.
                return round($document['users'][$user]['rating'], 2);
            } else {
                // Team exists but user doesn't.
                return 0;
            }
        } else {
            // Team and User do not exist.
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
        // Initialize datasource.
        $database = new DataSource();
        // Retrieve punbot collection.
        // TODO: Handle exception
        $collection = $database->getCollection('punbot');

        if ($user === null) {
            // Verify user was supplied, otherwise use command user.
            $user = $this->_user;
        }

        // TODO: Verify array keys.
        if ($document = $collection->findOne(array('team_id' => $this->_teamId)) === true) {
            if (array_key_exists($this->user, $document['users']) === true) {
                // User exists, return their rating, round result.
                return $document['users'][$user]['ratings_received'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }

    }//end _retrieveRatingCount()


}//end class
