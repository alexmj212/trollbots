<?php

/**
 * ChannelPoliceBot.php
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
 * Class ChannelPoliceBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class ChannelPoliceBot extends Bot
{


    /**
     * ChannelPoliceBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {
        parent::__construct($payload);
        $this->name = 'Channel Police Bot';
        $this->icon = ':warning:';
        $this->user = $payload->getUserName();

        $response = '*'.$this->user.'* has requested the discussion be moved to the ';

        if (Payload::isChannel($payload->getText()) === true) {
            $response .= '*'.$payload->getText().'* channel!';
        } else {
            $response .= 'appropriate channel.';
        }

        $responder = new Responder(new Post($this->name, $this->icon, $response, $payload->getChannelName(), POST::RESPONSE_IN_CHANNEL));
        $responder->respond();

    }//end __construct()


}//end class
