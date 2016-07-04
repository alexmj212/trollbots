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

namespace TrollBots\Scripts;
use TrollBots\Lib\Payload;
use TrollBots\Lib\Responder;
use TrollBots\Lib\Post;
use TrollBots\Lib\Bot;

/**
 * Class ChannelPoliceBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 * @release  1
 */
class ChannelPoliceBot extends Bot
{


    /**
     * ChannelPoliceBot constructor.
     *
     * @return void
     */
    public function execute()
    {

        $this->name = 'Channel Police Bot';
        $this->icon = ':warning:';
        $this->user = $this->payload->getUserName();

        $response = '*'.$this->user.'* has requested the discussion be moved to the ';

        if (Payload::isChannel($this->payload->getText()) === true) {
            $response .= '*'.$this->payload->getText().'* channel!';
        } else {
            $response .= 'appropriate channel.';
        }

        $responder = new Responder(new Post($this->name, $this->icon, $response, $this->payload->getChannelName(), Post::RESPONSE_IN_CHANNEL));
        $responder->respond();

    }//end execute()


}//end class
