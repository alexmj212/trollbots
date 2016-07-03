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
     * The title of the attachment
     *
     * @string
     */
    private $_title = '';

    /**
     * The text of the attachment
     *
     * @string
     */
    private $_text = '';

    /**
     * The pretext of the attachment
     *
     * @string
     */
    private $_pretext = '';

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
    private $_callbackId;

    /**
     * The color of the attachment indent
     *
     * @string
     */
    private $_color = '';

    /**
     * The text of the post that will appear in Slack
     *
     * @array
     */
    private $_actions = array();


    /**
     * Post constructor
     *
     * @param string $title      The name of the bot that will post
     * @param string $fallback   Icon to represent the post
     * @param string $callbackId The text of the post
     * @param string $text       The text that will appear in the attachment
     * @param string $pretext    the pretext of the attachment
     * @param string $color      The channel the post will appear
     */
    public function __construct($title, $fallback, $callbackId, $text = null, $pretext = null, $color = null)
    {
        $this->_title      = $title;
        $this->_fallback   = $fallback;
        $this->_callbackId = $callbackId;
        $this->_text       = $text;
        $this->_pretext    = $pretext;
        $this->_color      = $color;

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
     * Set the post title
     *
     * @param string $title the attachment title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->_title = $title;

    }//end setTitle()


    /**
     * Set the post text
     *
     * @param string $text the attachment text
     *
     * @return void
     */
    public function setText($text)
    {
        $this->_text = $text;

    }//end setText()


    /**
     * Set the post pretext
     *
     * @param string $pretext the attachment text
     *
     * @return void
     */
    public function setPretext($pretext)
    {
        $this->_pretext = $pretext;

    }//end setPretext()


    /**
     * Create attachemnt Array
     *
     * @return array
     */
    public function toArray()
    {
        $attachment = array(
                       'title'       => $this->_title,
                       'fallback'    => $this->_fallback,
                       'callback_id' => $this->_callbackId,
                       'color'       => $this->_color,
                       'actions'     => array(),
                      );

        foreach ($this->_actions as $action) {
            $attachment['actions'][] = $action->toArray();
        }

        return $attachment;

    }//end toArray()


}//end class
