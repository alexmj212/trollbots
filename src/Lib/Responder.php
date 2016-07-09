<?php

/**
 * Responder.php
 *
 * PHP version 5
 *
 * @category Includes
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

namespace TrollBots\Lib;

/**
 * Class Responder
 *
 * @category Responder
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class Responder
{

    /**
     * The post that will be used to respond
     *
     * @var Post
     */
    private $_post;


    /**
     * Set the post content
     *
     * @param Post $post The post that is sent to Slack
     */
    public function __construct(&$post)
    {
        $this->_post = &$post;

    }//end __construct()


    /**
     * Echo the contents of the post to Slack
     *
     * @return void
     */
    public function respond()
    {

        if ($this->_post->getResponseURL() !== null) {
            $this->respondCURL();
        } else {
            // Set response header to json type.
            header('Content-Type: application/json');
            echo $this->_post->toString();
            exit();
        }

    }//end respond()


    /**
     * Respond using the response URL for delayed responses
     *
     * @return void
     */
    public function respondCURL()
    {

        // Initialize Response request.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_post->getResponseURL());
        curl_setopt($ch, CURLOPT_POST, count($this->_post->toArray()));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_post->toString());
        curl_setopt($ch, CURLOPT_HTTPHEADER, 'Content-Type: application/json');
        curl_exec($ch);
        curl_close($ch);

    }//end respondCURL()


}//end class
