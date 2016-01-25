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
     * Echo the contents of the post to Slack
     *
     * @param Post $post The post that is sent to Slack
     *
     * @return void
     */
    public function respond($post)
    {

        // Set response header to json type.
        header('Content-Type: application/json');
        echo $post->toString();
        exit;

    }//end respond()


}//end class
