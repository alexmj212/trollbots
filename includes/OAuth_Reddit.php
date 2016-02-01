<?php

/**
 * OAuth_Reddit.php
 *
 * PHP version 5
 *
 * @category Configuration
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

require  __DIR__.'/../config.php';

/**
 * Class OAuth_Reddit
 *
 * @category OAuth
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */
class OAuth_Reddit
{

    /**
     * Reddit API Access Token URL
     *
     * @var string
     */
    private $_reddit_token_url;

    /**
     * Reddit API Redirect URL
     *
     * @var string
     */
    private $_reddit_redirect_uri;

    /**
     * Reddit API Client ID
     *
     * @var string
     */
    private $_reddit_client_id;

    /**
     * Reddit API Client Secret
     *
     * @var string
     */
    private $_reddit_client_secret;

    /**
     * Reddit API username
     *
     * @var string
     */
    private $_reddit_username;

    /**
     * Reddit API password
     *
     * @var string
     */
    private $_reddit_password;

    /**
     * The access toekn for making requests
     *
     * @var string
     */
    private $_reddit_access_token;

    /**
     * The saved subreddit data
     *
     * @var mixed
     */
    private $_reddit_data = null;


    /**
     * OAuth_Reddit constructor.
     */
    public function __construct()
    {

        $ch = curl_init();

        $fields  = '&grant_type=password';
        $fields .= '&username='.$this->_reddit_username;
        $fields .= '&password='.$this->_reddit_password;

        curl_setopt($ch, CURLOPT_URL, $this->_reddit_token_url);
        curl_setopt($ch, CURLOPT_POST, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_USERPWD, $this->_reddit_client_id.':'.$this->_reddit_client_secret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));

        curl_close($ch);

        $this->_reddit_access_token = $response->access_token;

    }//end __construct()


    /**
     * Retrieve the access token from reddit request
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->_reddit_access_token;

    }//end getAccessToken()


    /**
     * Retrieve the Subreddit Data
     *
     * @return mixed
     */
    public function getSubredditData()
    {
        return $this->_reddit_data;

    }//end getSubredditData()


    /**
     * Retrieve the data from the requested subreddit
     *
     * @param string $subreddit the given subreddit name
     *
     * @return void
     */
    public function requestSubreddit($subreddit)
    {

        $endpoint = 'https://oauth.reddit.com/r/'.$subreddit.'/about/';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);

        $header = array();

        $header[] = 'Authorization: bearer '.$this->_reddit_access_token;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SlackPHPBot/0.1 by primeradical');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));

        $info = curl_getinfo($ch);
        if ($info['http_code'] === 200) {
            $this->_reddit_data = $response->data;
        }

        curl_close($ch);

    }//end requestSubreddit()


}//end class
