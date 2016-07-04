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

namespace TrollBots\Scripts;
use TrollBots\Lib\Payload;
use TrollBots\Lib\Responder;
use TrollBots\Lib\Post;
use TrollBots\Lib\Bot;

/**
 * Class TriggerBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 * @release  1
 */
class TriggerBot extends Bot
{


    /**
     * TriggerBot constructor.
     *
     * @return void
     */
    public function execute()
    {
        $this->name = 'Trigger Bot';
        $this->icon = ':bangbang:';
        $this->user = $this->payload->getUserName();

        $response = '*'.$this->payload->getUserName().'* has been triggered';

        if (Payload::isUserName($this->payload->getText()) === true) {
            $response .= ' *'.$this->payload->getText().'*!';
        } else if ($this->payload->getText() === 'warning') {
            $response = ':heavy_exclamation_mark::heavy_exclamation_mark::heavy_exclamation_mark: :warning: :rotating_light: :warning: TRIGGER WARNING :warning: :rotating_light: :warning::heavy_exclamation_mark::heavy_exclamation_mark::heavy_exclamation_mark:';
        }

        $responder = new Responder(new Post($this->name, $this->icon, $response, $this->payload->getChannelName(), Post::RESPONSE_IN_CHANNEL));
        $responder->respond();

    }//end execute()


}//end class
