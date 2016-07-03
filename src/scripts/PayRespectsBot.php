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
use TrollBots\Lib\Action;
use TrollBots\Lib\Attachment;
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

    const PAY_RESPECTS = 'pay_respects';


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

        $post = new Post($this->name, $this->icon, $response, $payload->getChannelName(), Post::RESPONSE_IN_CHANNEL);

        $attachment = new Attachment('Join in the Respects', 'Pay Respects', PayRespectsBot::PAY_RESPECTS);

        $attachment->addAction(new Action('Pay Respects', 'f'));

        $post->addAttachment($attachment);

        $responder = new Responder($post);
        $responder->respond();

    }//end __construct()


}//end class
