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

    const PAY_RESPECTS_CALLBACK = 'pay_respects';


    /**
     * TriggerBot constructor.
     *
     * @return void
     */
    public function execute()
    {

        $this->name = 'Pay Respects Bot';
        $this->icon = ':bow:';
        $this->user = $this->payload->getUserName();

        if (get_class($this->payload) === 'ActionPayload') {
            $post = new Post($this->name, $this->icon, '', $this->payload->getChannel()['name'], Post::RESPONSE_IN_CHANNEL);
            $original = $this->payload->getOriginal();
            $response = $original['text'];
            str_replace('has', 'have', $original['text']);
            $response = '@'.$this->payload->getUser()['name'].', '.$response;
            $post->setText($response);
            $responder = new Responder($post);
            $responder->respond();
            exit();
        }

        $response = '*'.$this->payload->getUserName().'* has paid their respects';

        if ($this->payload->getText() !== null && $this->payload->getText() !== '') {
            $response .= ' to *'.$this->payload->getText().'*';
        }

        $post = new Post($this->name, $this->icon, $response, $this->payload->getChannelName(), Post::RESPONSE_IN_CHANNEL);

        $attachment = new Attachment('Pay Respect', 'Pay Respects', PayRespectsBot::PAY_RESPECTS_CALLBACK);

        $attachment->addAction(new Action('Pay Respects', 'F'));

        $post->addAttachment($attachment);

        $responder = new Responder($post);
        $responder->respond();

    }//end execute()


}//end class
