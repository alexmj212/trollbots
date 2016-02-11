<?php

/**
 * TriggerBot.php
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
 * Class TriggerBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class TriggerBot
{

    /**
     * The name of the Bot
     *
     * @var string
     */
    private $_name = 'Trigger Bot';

    /**
     * The icon to represent the bot
     *
     * @var string
     */
    private $_icon = ':bangbang:';


    /**
     * TriggerBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        $response = null;

        if ($payload->isUserName() === true) {
            $response = '*'.$payload->getUserName().'* has been triggered by *'.$payload->getText().'*!';
        } else {
            $response = '*'.$payload->getUserName().'* has been triggered!';
        }

        $responder = new Responder(new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), true));
        $responder->respond();

    }//end __construct()


}//end class
