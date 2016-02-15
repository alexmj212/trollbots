<?php

/**
 * BotTest.php
 *
 * PHP version 5
 *
 * @category Test
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

require __DIR__.'/../includes/Bot.php';

/**
 * Class Bot Test
 *
 * @category BotTest
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class BotTest extends PHPUnit_Framework_TestCase
{


    /**
     * Make sure the response type is ephemeral
     *
     * @param string $provided switch for post visibility
     * @param bool   $expected response type set
     *
     * @dataProvider providerTestVerifyToken
     *
     * @return void
     */
    public function testVerifyToken($provided, $expected)
    {


        static::assertEquals(Bot::verifyToken('test', $provided), $expected);

    }//end testVerifyToken()


    /**
     * Provide data for testing token verification
     *
     * @return array
     */
    public function providerTestVerifyToken()
    {
        putenv('test_slack_token=123456abcdefg');
        return array(
                array(
                 '123456abcdefg',
                 true,
                ),
                array(
                 'abcdefg123456',
                 false,
                ),
                array(
                 '',
                 false,
                ),
                array(
                 123456,
                 false,
                ),
               );

    }//end providerTestVerifyToken()


}//end class
