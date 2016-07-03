<?php

/**
 * Attachment.php
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
 * Class Attachment
 *
 * @category Attachment
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class Attachment
{

    /**
     * The text of the post that will appear in Slack
     *
     * @string
     */
    private $_title;

    /**
     * The fallback of the attachment text for mobile displays
     *
     * @string
     */
    private $_fallback;

    /**
     * The id of the collection of buttons in the attachment
     *
     * @string
     */
    private $_callback_id;

    /**
     * The color of the attachment indent
     *
     * @string
     */
    private $_color;

    /**
     * The text of the post that will appear in Slack
     *
     * @array
     */
    private $_actions = array();


    /**
     * Post constructor
     *
     * @param string $title       The name of the bot that will post
     * @param string $fallback    Icon to represent the post
     * @param string $callback_id The text of the post
     * @param string $color       The channel the post will appear
     */
    public function __construct($title, $fallback, $callback_id, $color = null)
    {
        $this->_title       = $title;
        $this->_fallback    = $fallback;
        $this->_callback_id = $callback_id;
        $this->_color       = $color;

    }//end __construct()


    /**
     * Return array of actions
     *
     * @return array
     */
    public function getActions()
    {
        return array('actions' => $this->_actions);

    }//end getActions()


    /**
     * Add action to array
     *
     * @param Action $action the action to add to the post
     *
     * @return void
     */
    public function addAction($action)
    {
        $this->_actions[] = $action;

    }//end addAction()


    /**
     * Set the post text
     *
     * @param string $title the attachment title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->_title = $title;

    }//end setTitle()


}//end class
