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
class TriggerBot extends Bot
{


    /**
     * TriggerBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {
        $this->name = 'Trigger Bot';
        $this->icon = ':bangbang:';
        $this->user = $payload->getUserName();

        $response = '*'.$payload->getUserName().'* has been triggered';

        if (Payload::isUserName($payload->getText()) === true) {
            $response .= ' *'.$payload->getText().'*!';
        } else if ($payload->getText() === 'warning') {
            $response = ':heavy_exclamation_mark::heavy_exclamation_mark::heavy_exclamation_mark: :warning: :rotating_light: :warning: TRIGGER WARNING :warning: :rotating_light: :warning::heavy_exclamation_mark::heavy_exclamation_mark::heavy_exclamation_mark:';
        } else {
            $response = Post::INVALID_COMMAND;
        }

        $responder = new Responder(new Post($this->name, $this->icon, $response, $payload->getChannelName(), true));
        $responder->respond();

    }//end __construct()


}//end class
