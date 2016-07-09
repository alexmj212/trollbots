<?php

/**
 * ShameBot.php
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
 * Class ShameBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 * @release  1
 */
class ShameBot extends Bot
{

    const SHAME      = 'SHAME :bell:';
    const SHAME_USER = '%s has shamed %s';


    /**
     * Shame Bot constructor.
     *
     * @return void
     */
    public function execute()
    {

        $this->name = 'Shame Bot';
        $this->icon = ':bell:';
        $this->user = $this->payload->getUserName();

        $shamedUser = $this->payload->getText();

        if (Payload::isUserName($shamedUser) === true) {
            $response = sprintf(ShameBot::SHAME_USER, $this->user, $shamedUser);

            $post = new Post($this->name, $this->icon, $response, $this->payload->getChannelName(), Post::RESPONSE_IN_CHANNEL);

            $responder = new Responder($post);
            $responder->respond();
            for ($i = 0; $i < 3; $i++) {
                $post = new Post($this->name, $this->icon, ShameBot::SHAME, $shamedUser, Post::RESPONSE_IN_CHANNEL, $this->payload->getResponseURL());

                $responder = new Responder($post);
                $responder->respond();
                sleep(3);
            }
        } else {
            $post = new Post($this->name, $this->icon, Post::INVALID_COMMAND, $shamedUser, Post::RESPONSE_IN_CHANNEL);

            $responder = new Responder($post);
            $responder->respond();
        }

    }//end execute()


}//end class
