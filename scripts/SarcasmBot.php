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

/**
 * Class SarcasmBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class SarcasmBot
{

    /**
     * The name of the Bot
     *
     * @var string
     */
    private $_name = 'Sarcasm Bot';

    /**
     * The icon to represent the bot
     *
     * @var string
     */
    private $_icon = ':upside_down_face::';


    /**
     * SarcasmBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        $response  = '*'.$payload->getUserName().'* would like you to know that they are being ';
        $response .= 'deliberately sarcastic and that their statement isn\'t meant to be taken literally.';

        $responder = new Responder(new Post($this->_name, $this->_icon, $response, $payload->getChannelName(), true));
        $responder->respond();

    }//end __construct()


}//end class
