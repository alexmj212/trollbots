<?php

/**
 * OAuth.php
 *
 * PHP version 5
 *
 * @category Configuration
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

require '/../config.php';

/**
 * Class OAuth
 *
 * @category OAuth
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

class OAuth
{

    /**
     * Slack Client ID placeholder
     *
     * @var string
     */
    private $_client_id;

    /**
     * Slack Client Secret placeholder
     *
     * @var string
     */
    private $_client_secret;

    /**
     * Slack transaction code
     *
     * @var
     */
    private $_code;

    /**
     * Requested bot name
     *
     * @var string
     */
    private $_bot;


    /**
     * OAuth constructor.
     *
     * @param array  $data    data associated with the oauth request
     * @param string $botName name of the bot requesting Oauth
     *
     * @throws ErrorException
     */
    public function __construct($data, $botName)
    {
        // Store the code provided by Slack.
        $this->_code = $data['code'];
        // Store the bot name.
        $this->_bot = $botName;
        // Set the client credentials based on the bot requesting access.
        $this->_setClientDetails();

    }//end __construct()


    /**
     * Set the OAuth details based on the bot name
     *
     * @return void
     * @throws ErrorException
     */
    private function _setClientDetails()
    {
        // Pull the Slack API credentials.
        global $conf;
        try {
            if (array_key_exists('client_id', $conf['bots'][$this->_bot]) === true) {
                $this->_client_id = $conf['bots'][$this->_bot]['client_id'];
            } else {
                throw new ErrorException('No Client ID for '.$this->_bot);
            }

            if (array_key_exists('client_secret', $conf['bots'][$this->_bot]) === true) {
                $this->_client_secret = $conf['bots'][$this->_bot]['client_secret'];
            } else {
                throw new ErrorException('No Client Secret for '.$this->_bot);
            }

            return;
        } catch (Exception $e) {
            echo 'OAuth Error: ', $e->getMessage(), '\n';
            exit();
        }//end try

    }//end _setClientDetails()


    /**
     * Request Slack Token
     *
     * @return void
     */
    public function requestSlackAuth()
    {
        // Initialize OAuth request.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->buildSlackURL());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // TODO: Add tests and error checking for curl exec.
        curl_exec($ch);
        curl_close($ch);
        // Redirect on successful request.
        header('Location: http://alexmj212.github.io/slackphpbot/?success='.$this->_bot);
        die();

    }//end requestSlackAuth()


    /**
     * Create Slack URL
     *
     * @return string
     */
    public function buildSlackURL()
    {
        // Pull the OAuth URL.
        global $conf;

        // Build the access.token url for Slack.
        try {
            if (array_key_exists('oauth.access', $conf['slack']) === true) {
                $url  = $conf['slack']['oauth.access'];
                $url .= '?client_id='.$this->_client_id;
                $url .= '&client_secret='.$this->_client_secret;
                $url .= '&code='.$this->_code;
                return $url;
            } else {
                throw new ErrorException('Unable to find oauth.access URL');
            }
        } catch (ErrorException $e){
            echo 'OAuth Error: ', $e->getMessage(), '\n';
            exit();
        }//end try

    }//end buildSlackURL()


}//end class
