<?php

/**
 * OAuthTest.php
 *
 * PHP version 5
 *
 * @category Test
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

use TrollBots\Auth\OAuth_Slack;

/**
 * Class OAuth Test
 *
 * @category OAuthTest
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class OAuthTest extends PHPUnit_Framework_TestCase
{


    /**
     * Test the creation of the slack auth url
     *
     * @covers OAuth_Slack::buildSlackURL
     *
     * @return void
     */
    public function testOAuthInitialization()
    {

        $oauth = new OAuth_Slack(array('code' => '000000'), 'Test Bot', 123456, 654321);
        static::assertEquals(
            $oauth->buildSlackURL(),
            'https://slack.com/api/oauth.access?client_id=123456&client_secret=654321&code=000000'
        );

    }//end testOAuthInitialization()


}
