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

        $response = '*'.$this->payload->getUserName().'* has paid their respects';

        $post = new Post($this->name, $this->icon, $response, $this->payload->getChannelName(), Post::RESPONSE_IN_CHANNEL);
        $post->setReplaceOriginal(true);

        if (get_class($this->payload) === 'TrollBots\Lib\ActionPayload') {
            $post->setText('*'.$this->payload->getUserName().'*, '.$this->payload->getText());
        }

        // Check to make sure the user hasn't already paid their respects. Too much respect.
        if (strpos($this->payload->getText(), $this->payload->getUserName()) !== false) {
            $post->setText('Too much respect.');
            $post->setResponseType(Post::RESPONSE_EPHEMERAL);
            $post->setReplaceOriginal(false);
            $responder = new Responder($post);
            $responder->respond();
            die();
        }

        $attachment = new Attachment('Pay Respects', 'Pay Respects', PayRespectsBot::PAY_RESPECTS_CALLBACK);

        $attachment->addAction(new Action('Press F to Pay Respects', 'F', Action::ACTION_PRIMARY_STYLE));

        $post->addAttachment($attachment);

        $responder = new Responder($post);
        $responder->respond();

    }//end execute()


}//end class
