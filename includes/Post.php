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
     * @array
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
     * Post constructor
     *
     * @param string $name         The name of the bot that will post
     * @param string $icon         Icon to represent the post
     * @param string $text         The text of the post
     * @param string $channel      The channel the post will appear
     * @param string $responseType The visibility of the post
     */
    public function __construct($name, $icon, $text, $channel, $responseType = null)
    {
        $this->_name    = $name;
        $this->_icon    = $icon;
        $this->_text    = $text;
        $this->_channel = $channel;

        if ($responseType !== null) {
            $this->setResponseType($responseType);
        }

    }//end __construct()


    /**
     * Function addAttachment()
     *
     * @param array $attachment Container for an additional attachment
     *
     * @return void
     */
    public function addAttachment($attachment)
    {

        $this->_attachments[] = $attachment;

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
     * Function toString()
     *
     * @return string
     */
    public function toString()
    {
        $post = array(
                 'text'          => $this->_text,
                 'attachments'   => $this->_attachments,
                 'response_type' => $this->_responseType,
                );

        return json_encode($post, JSON_PRETTY_PRINT);

    }//end toString()


}//end class
