<?php

/**
 * ActionPayload.php
 *
 * PHP version 5
 *
 * @category Includes
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

namespace TrollBots\Lib;
use ErrorException;

/**
 * Class ActionPayload
 *
 * @category ActionPayload
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class ActionPayload
{

    /**
     * Action that was submitted
     *
     * @var Action
     */
    private $_action;

    /**
     * Callback ID to identify action
     *
     * @var string
     */
    private $_callbackId;

    /**
     * Token to identify post
     *
     * @var string
     */
    private $_token;

    /**
     * The actions team information
     *
     * @var array
     */
    private $_team;

    /**
     * The channel where the action originated
     *
     * @var array
     */
    private $_channel;

    /**
     * The user who submitted the action
     *
     * @var array
     */
    private $_user;

    /**
     * Timestamp of the action
     *
     * @var double
     */
    private $_actionTs;

    /**
     * Timestamp of the message from which the action originated
     *
     * @var double
     */
    private $_messageTs;

    /**
     * The id of the attachment
     *
     * @var string
     */
    private $_attachmentId;

    /**
     * The text of the original message that created the action
     *
     * @var string
     */
    private $_original;

    /**
     * The response url to use for delayed messages
     *
     * @var string
     */
    private $_responseUrl;


    /**
     * Payload constructor.
     *
     * @param array $data the post data received
     *
     * @throws ErrorException
     */
    public function __construct($data)
    {
        $data = json_encode($data['payload']);

        try {
            if (array_key_exists('actions', $data) === true) {
                foreach ($data['actions'] as $action) {
                    // Only accept one action, whatever the last action is.
                    // TODO: Store multiple actions?
                    $this->_action = new Action($action['name'], '', null, null, $action['value']);
                }
            } else {
                throw new ErrorException('actions missing from action payload');
            }

            if (array_key_exists('callbackId', $data) === true) {
                $this->_callbackId = strtolower($data['callbackId']);
            } else {
                throw new ErrorException('callbackId missing from action payload');
            }

            if (array_key_exists('team', $data) === true) {
                $this->_team = $data['team'];
            } else {
                throw new ErrorException('team missing from action payload');
            }

            if (array_key_exists('channel', $data) === true) {
                $this->_channel = $data['channel'];
            } else {
                throw new ErrorException('channel missing from action payload');
            }

            if (array_key_exists('user', $data) === true) {
                $this->_user = $data['user'];
            } else {
                throw new ErrorException('user missing from action payload');
            }

            if (array_key_exists('actionTs', $data) === true) {
                $this->_actionTs = strtolower($data['actionTs']);
            } else {
                throw new ErrorException('actionTs missing from action payload');
            }

            if (array_key_exists('messageTs', $data) === true) {
                $this->_messageTs = strtolower($data['messageTs']);
            } else {
                throw new ErrorException('messageTs missing from action payload');
            }

            if (array_key_exists('attachmentId', $data) === true) {
                $this->_attachmentId = strtolower($data['attachmentId']);
            } else {
                throw new ErrorException('attachmentId missing from action payload');
            }

            if (array_key_exists('token', $data) === true) {
                $this->_token = $data['token'];
            } else {
                throw new ErrorException('token missing from action payload');
            }

            if (array_key_exists('original', $data) === true) {
                $this->_original = strtolower($data['original']);
            } else {
                throw new ErrorException('original missing from action payload');
            }

            if (array_key_exists('responseUrl', $data) === true) {
                $this->_responseUrl = strtolower($data['responseUrl']);
            } else {
                throw new ErrorException('responseUrl missing from action payload');
            }
        } catch (ErrorException $e){
            echo 'Action Payload Processing Error: ', $e->getMessage(), '\n';
            exit();
        }//end try

    }//end __construct()


    /**
     * Get the Team ID
     *
     * @return mixed
     */
    public function getTeam()
    {
        return $this->_team;

    }//end getTeam()


    /**
     * Get Channel Name
     *
     * @return string
     */
    public function getChannel()
    {
        return $this->_channel;

    }//end getChannel()


    /**
     * Get Username
     *
     * @return string
     */
    public function getUser()
    {
        return $this->_user;

    }//end getUser()


    /**
     * Get the token of the payload
     *
     * @return string
     */
    public function getToken()
    {
        return $this->_token;

    }//end getToken()


}//end class
