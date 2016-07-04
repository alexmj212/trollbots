<?php

/**
 * SarcasmBot.php
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
 * Class SarcasmBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 * @release  1
 */
class SarcasmBot extends Bot
{


    /**
     * SarcasmBot constructor.
     *
     * @return void
     */
    public function execute()
    {
        $this->name = 'Sarcasm Bot';
        $this->icon = ':upside_down_face:';
        $this->user = $this->payload->getUserName();

        $response = null;

        $post = new Post($this->name, $this->icon, '', $this->payload->getChannelName(), Post::RESPONSE_IN_CHANNEL);

        $response = 'That was sarcasm.'.PHP_EOL;
        $post->setText($response);

        $responder = new Responder($post);
        $responder->respond();

    }//end execute()


}//end class
