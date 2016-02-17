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
class SarcasmBot extends Bot
{


    /**
     * SarcasmBot constructor.
     *
     * @param Payload $payload the payload data
     */
    public function __construct($payload)
    {

        $this->name = 'Sarcasm Bot';
        $this->icon = ':upside_down_face:';
        $this->user = $payload->getUserName();

        $response = null;

        $post = new Post($this->name, $this->icon, 'blah', $payload->getChannelName(), true);

        switch(rand(0, 2)){
        case 0:
            $response  = '*'.$this->user.'* is being sarcastic.'.PHP_EOL;
            $response .= 'This means their previous statement isn\'t meant to be taken literally.';
            $post->setText($response);
            break;
        case 1:
            $response = 'Do you think that\'s what *'.$this->user.'* actually meant?';
            $post->setText($response);
            break;
        case 2:
            $attachment = array(
                           'pretext'    => 'According to Merriam-Webster',
                           'title'      => 'sarcasm',
                           'title_link' => 'http://www.merriam-webster.com/dictionary/sarcasm',
                           'fallback'   => 'http://www.merriam-webster.com/dictionary/sarcasm',
                          );

            $attachment['text']  = 'noun - sar·casm - \ˈsär-ˌka-zəm\\'.PHP_EOL;
            $attachment['text'] .= 'the use of words that mean the opposite of what you ';
            $attachment['text'] .= 'really want to say especially in order to insult someone, ';
            $attachment['text'] .= 'to show irritation, or to be funny';
            $post->addAttachment($attachment);
            break;
        }//end switch

        $responder = new Responder($post);
        $responder->respond();

    }//end __construct()


}//end class
