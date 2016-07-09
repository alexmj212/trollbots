<?php

/**
 * Post.php
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
 * Class Post
 *
 * @category Post
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class Post
{

    const INVALID_COMMAND     = 'Invalid Command';
    const INVALID_TOKEN       = 'Invalid Token';
    const RESPONSE_IN_CHANNEL = 'in_channel';
    const RESPONSE_EPHEMERAL  = 'ephemeral';

    /**
     * Name that will appear on the Slack post
     *
     * @string
     */
    private $_name;

    /**
     * Icon that will appear on the Slack post
     *
     * @string
     */
    private $_icon;

    /**
     * The text of the post that will appear in Slack
     *
     * @string
     */
    private $_text;

    /**
     * The name of the channel that the post will appear
     *
     * @string
     */
    private $_channel;

    /**
     * Attachments that will appear on the Slack post
     *
     * @array<Attachment>
     */
    private $_attachments;

    /**
     * The visibility of the post
     * Will only ever be "ephemeral" or "in_channel"
     *
     * @string
     */
    private $_responseType = Post::RESPONSE_EPHEMERAL;

    /**
     * The url to post to
     *
     * @string
     */
    private $_responseURL;

    /**
     * In the case of an action, toggle the replace original
     *
     * @var boolean
     */
    private $_replaceOriginal;


    /**
     * Post constructor
     *
     * @param string $name         The name of the bot that will post
     * @param string $icon         Icon to represent the post
     * @param string $text         The text of the post
     * @param string $channel      The channel the post will appear
     * @param string $responseType The visibility of the post
     * @param string $responseURL  The URL to respond to for delayed messages
     */
    public function __construct($name, $icon, $text, $channel, $responseType = null, $responseURL = null)
    {
        $this->_name    = $name;
        $this->_icon    = $icon;
        $this->_text    = $text;
        $this->_channel = $channel;

        if ($responseType !== null) {
            $this->setResponseType($responseType);
        }

        if ($responseURL !== null) {
            $this->_responseURL = $responseURL;
        }

    }//end __construct()


    /**
     * Function addAttachment()
     *
     * @param Attachment $attachment Container for an additional attachment
     *
     * @return void
     */
    public function addAttachment($attachment)
    {

        $this->_attachments[] = $attachment->toArray();

    }//end addAttachment()


    /**
     * Get Response Type
     *
     * @return mixed
     */
    public function getResponseType()
    {

        return $this->_responseType;

    }//end getResponseType()


    /**
     * Set Response Type based on provided flag, else switch the response type
     *
     * @param String $responseType the flag to change to response type
     *
     * @return String
     */
    public function setResponseType($responseType)
    {

        // Set the response type if provided.
        if ($responseType === Post::RESPONSE_IN_CHANNEL) {
            $this->_responseType = Post::RESPONSE_IN_CHANNEL;
        } else if ($responseType === Post::RESPONSE_EPHEMERAL) {
            $this->_responseType = Post::RESPONSE_EPHEMERAL;
        }

        return $this->_responseType;

    }//end setResponseType()


    /**
     * Get Response URL
     *
     * @return mixed
     */
    public function getResponseURL()
    {

        return $this->_responseURL;

    }//end getResponseURL()


    /**
     * Switch Response Type
     *
     * @return String
     */
    public function switchResponseType()
    {

        // Switch the responses if not provided.
        if ($this->_responseType === Post::RESPONSE_IN_CHANNEL) {
            $this->setResponseType(Post::RESPONSE_EPHEMERAL);
        } else if ($this->_responseType === Post::RESPONSE_EPHEMERAL) {
            $this->setResponseType(Post::RESPONSE_IN_CHANNEL);
        }

        return $this->_responseType;

    }//end switchResponseType()


    /**
     * Return array of attachments
     *
     * @return array
     */
    public function getAttachments()
    {

        return array('attachments' => $this->_attachments);

    }//end getAttachments()


    /**
     * Set the post text
     *
     * @param string $text the post text
     *
     * @return void
     */
    public function setText($text)
    {
        $this->_text = $text;

    }//end setText()


    /**
     * Change the replace_original option
     *
     * @param boolean $replace the setting for replace original
     *
     * @return void
     */
    public function setReplaceOriginal($replace)
    {
        $this->_replaceOriginal = $replace;

    }//end setReplaceOriginal()


    /**
     * Function toArray()
     *
     * @return array
     */
    public function toArray()
    {

        $post = array(
                 'text'          => $this->_text,
                 'channel'       => $this->_channel,
                 'attachments'   => array(),
                 'response_type' => $this->_responseType,
                );
        if ($this->_attachments !== null) {
            foreach ($this->_attachments as $attachment) {
                $post['attachments'][] = $attachment;
            }
        }

        if ($this->_replaceOriginal !== null) {
            $post['replace_original'] = $this->_replaceOriginal;
        }

        return $post;

    }//end toArray()


    /**
     * Function toString()
     *
     * @return string
     */
    public function toString()
    {

        return json_encode($this->toArray(), JSON_PRETTY_PRINT);

    }//end toString()


}//end class
