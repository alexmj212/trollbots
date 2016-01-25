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
     * TriggerBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {
        $this->_teamId = $payload->getTeamId();

        if ($payload->isUserName() === true) {
            $this->_user = $payload->getText();
            if ($payload->getUserName() === $this->_user) {
                $responder = new Responder(new Post($this->_name, $this->_icon, 'You can\'t tip yourself!', $payload->getChannelName(), 0));
            } else {
                $this->_logTip();
                $response = '*'.$payload->getUserName().'* has tipped *'.$this->_user.'*'.' bringing their total to '.$this->_retrieveTips($this->_user).' tips';
                $responder = new Responder(new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), 1));
            }
        } else if ($payload->getText() === 'total') {
            $this->_user = $payload->getUserName();
            $total = $this->_retrieveTips();
            $response = 'You\'ve been tipped '.$total.' time(s)';
            $responder = new Responder(new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), 0));
        } else {
            $responder = new Responder(new Post($this->_name, $this->_icon, 'Invalid command', $payload->getChannelName(), 0));
        }

    }//end __construct()


    /**
     * Log the new tip.
     *
     * @return void
     */
    private function _logTip()
    {

        date_default_timezone_set('UTC');
        $collection = $this->_retrieveTipCollection('tipbot');

        // Does this team exist?
        if ($document = $collection->findOne(array('team_id' => $this->_teamId)) === true) {
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
            $collection->update(array('team_id' => $this->_teamId), $document);
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
            $collection->insert($team);
        }//end if

    }//end _logTip()


    /**
     * Retrieve the total number of tips the given user has received
     *
     * @param string $userName the name of the user
     *
     * @return int
     */
    private function _retrieveTips()
    {

        $collection = $this->_retrieveTipCollection('tipbot');

        if ($document = $collection->findOne(array('team_id' => $this->_teamId)) === true) {
            if (array_key_exists($this->_user, $document['users']) === true) {
                return (int) $document['users'][$this->_user]['received'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }

    }//end _retrieveTips()


    /**
     * Retrive the tip database collection
     *
     * @param string $collectionName the name of the collection to retrieve
     *
     * @return MongoCollection
     */
    private function _retrieveTipCollection($collectionName)
    {
        try {
            $database   = new DataSource();
            $collection = $database->getCollection('$collectionName');
            if ($collection === null) {
                throw new ErrorException('Unable to retrieve tip collection');
            }
        } catch (Exception $e) {
            echo 'Log Tip Error: ', $e->getMessage(), '\n';
            exit;
        }

        return $collection;

    }//end _retrieveTipCollection()


}//end class
