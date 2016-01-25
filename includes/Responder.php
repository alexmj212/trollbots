<?php

/**
 * Responder.php
 *
 * PHP version 5
 *
 * @category Includes
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

/**
 * Class Responder
 *
 * @category Responder
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
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
     * Echo the contents of the post to Slack
     *
     * @param Post $post The post that is sent to Slack
     *
     * @return void
     */


    public function __construct(&$post)
    {
        $this->_post = &$post;
    }

    public function respond()
    {

        // Set response header to json type.
        header('Content-Type: application/json');
        echo $this->_post->toString();
        exit;

    }//end respond()


}//end class
