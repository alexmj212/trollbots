<?php

/**
 * ChannelPoliceBot.php
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
 * Class ChannelPoliceBot
 *
 * @category Bot
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */
class ChannelPoliceBot
{

    /**
     * The name of the Bot
     *
     * @var string
     */
    private $_name = 'Channel Police Bot';

    /**
     * The icon to represent the bot
     *
     * @var string
     */
    private $_icon = ':warning:';


    /**
     * ChannelPoliceBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        $response = null;

        if ($payload->isChannel() === true) {
            // TODO: Verifiy validity of requested channel.
            $response = '*'.$payload->getUserName().'* has requested the discussion be moved to the *'.$payload->getText().'* channel!';
        } else {
            $response = '*'.$payload->getUserName().'* has requested the discussion be moved to the appropriate channel.';
        }

        $responder = new Responder(new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), true));
        $responder->respond();

    }//end __construct()


}//end class
