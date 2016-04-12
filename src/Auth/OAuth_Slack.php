<?php

/**
 * OAuth_Slack.php
 *
 * PHP version 5
 *
 * @category Configuration
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

namespace TrollBots\Auth;
use ErrorException;

if (file_exists(__DIR__.'/../../config.php') === true) {
    include __DIR__.'/../../config.php';
}

/**
 * Class OAuth_Slack
 *
 * @category OAuth
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class OAuth_Slack
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
     * @param array  $data          data associated with the oauth request
     * @param string $botName       name of the bot requesting Oauth
     * @param string $client_id     slack client id
     * @param string $client_secret slack client secret
     *
     * @throws ErrorException
     */
    public function __construct($data, $botName, $client_id = null, $client_secret = null)
    {
        // Store the code provided by Slack.
        $this->_code = $data['code'];
        // Store the bot name.
        $this->_bot = $botName;
        // Set the client credentials based on the bot requesting access.
        $this->_setSlackClientDetails();

        $this->_client_id     = $client_id;
        $this->_client_secret = $client_secret;

    }//end __construct()


    /**
     * Set the OAuth details based on the bot name
     *
     * @return void
     * @throws ErrorException
     */
    private function _setSlackClientDetails()
    {
        // Pull the Slack API credentials.
        global $conf;
        try {
            if ($conf === null
                || is_array($conf) !== true
                || (array_key_exists('bots', $conf) !== true && array_key_exists($this->_bot, $conf['bots']) !== true)
            ) {
                throw new ErrorException('Missing '.$this->_bot.' config');
            }

            if (array_key_exists('slack_client_id', $conf['bots'][$this->_bot]) === true) {
                $this->_client_id = $conf['bots'][$this->_bot]['slack_client_id'];
            } else {
                throw new ErrorException('No Client ID for '.$this->_bot);
            }

            if (array_key_exists('slack_client_secret', $conf['bots'][$this->_bot]) === true) {
                $this->_client_secret = $conf['bots'][$this->_bot]['slack_client_secret'];
            } else {
                throw new ErrorException('No Client Secret for '.$this->_bot);
            }
        } catch (ErrorException $e) {
            echo 'OAuth Error: ', $e->getMessage(), '\n';
        }//end try

    }//end _setSlackClientDetails()


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
        header('Location: http://alexmj212.github.io/trollbots/?success='.$this->_bot);
        die();

    }//end requestSlackAuth()


    /**
     * Create Slack URL
     *
     * @return string
     */
    public function buildSlackURL()
    {

        // Build the access.token url for Slack.
        try {
                $url  = 'https://slack.com/api/oauth.access';
                $url .= '?client_id='.$this->_client_id;
                $url .= '&client_secret='.$this->_client_secret;
                $url .= '&code='.$this->_code;
                return $url;
        } catch (ErrorException $e){
            echo 'OAuth Error: ', $e->getMessage(), '\n';
            exit();
        }//end try

    }//end buildSlackURL()


}//end class
