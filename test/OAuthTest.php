<?php

/**
 * OAuthTest.php
 *
 * PHP version 5
 *
 * @category Test
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

require __DIR__.'/../includes/OAuth_Slack.php';

/**
 * Class OAuth Test
 *
 * @category OAuthTest
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */
class OAuthTest extends PHPUnit_Framework_TestCase
{


    /**
     * Test the creation of the slack auth url
     *
     * @return void
     */
    public function testOAuthInitialization()
    {
        $oauth = new OAuth_Slack(array('code' => '000000'), 'Test Bot');
        static::assertEquals(
            $oauth->buildSlackURL(),
            'https://slack.com/api/oauth.access?client_id=123456&client_secret=654321&code=000000'
        );

    }//end testOAuthInitialization()


}
