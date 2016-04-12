<?php

/**
 * PayRespectsBot.php
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
 * Class PayRespectsBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 * @release  1
 */
class PayRespectsBot extends Bot
{


    /**
     * TriggerBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {
        parent::__construct($payload);
        $this->name = 'Pay Respects Bot';
        $this->icon = ':bow:';
        $this->user = $payload->getUserName();

        $response = '*'.$payload->getUserName().'* has paid their respects';

        if (Payload::isUserName($payload->getText()) === true) {
            $response .= ' to *'.$payload->getText().'*';
        }

        $responder = new Responder(new Post($this->name, $this->icon, $response, $payload->getChannelName(), Post::RESPONSE_IN_CHANNEL));
        $responder->respond();

    }//end __construct()


}//end class
