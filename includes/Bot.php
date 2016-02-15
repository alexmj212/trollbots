<?php

/**
 * Bot.php
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
 * Class Bot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class Bot
{

    /**
     * The name of the Bot
     *
     * @var string
     */
    protected $name;

    /**
     * The icon to represent the bot
     *
     * @var string
     */
    protected $icon;

    /**
     * The user receiving or giving the Pun rating
     *
     * @var string
     */
    protected $user;

    /**
     * The ID of the team
     *
     * @var mixed
     */
    protected $teamId;

    /**
     * The name of the bots collection
     *
     * @var string
     */
    protected $collectionName;


    /**
     * Verify the request token
     *
     * @param string $name  the name of the bot
     * @param string $token the token sent by the request
     *
     * @return bool
     * @throws ErrorException
     */
    public static function verifyToken($name, $token)
    {

        $botToken = getenv($name.'_slack_token');

        if (strcmp($botToken, $token) !== 0) {
            return false;
        } else {
            return true;
        }

    }//end verifyToken()


    /**
     * Verify the request token
     *
     * @return string
     */
    public function getCollectionName()
    {

        return $this->collectionName;

    }//end getCollectionName()


}//end class
