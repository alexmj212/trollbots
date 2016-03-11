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

/**
 * Class PayRespectsBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
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
            $response .= ' to *'.$payload->getText();
        }

        $responder = new Responder(new Post($this->name, $this->icon, $response, $payload->getChannelName(), POST::RESPONSE_IN_CHANNEL));
        $responder->respond();

    }//end __construct()


}//end class
