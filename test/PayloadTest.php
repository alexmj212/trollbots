<?php

/**
 * PayloadTest.php
 *
 * PHP version 5
 *
 * @category Test
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

require __DIR__.'/../includes/Payload.php';

/**
 * Class Payload Test
 *
 * @category PayloadTest
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class PayloadTest extends PHPUnit_Framework_TestCase
{


    /**
     * Ensure payload is processed and stored appropriately
     *
     * @param array $original switch for post visibility
     *
     * @dataProvider providerTestPayloadProcessing
     *
     * @return void
     */
    public function testPayloadProcessing($original)
    {

        $payload = new Payload($original);

        static::assertStringStartsWith('#', $payload->getChannelName());
        static::assertStringStartsWith('@', $payload->getUserName());

    }//end testPayloadProcessing()


    /**
     * Test the username validation
     *
     * @param string  $original the username
     * @param boolean $expected the expected check result
     *
     * @dataProvider providerTestPayloadUserNameCheck
     *
     * @return void
     */
    public function testPayloadUserNameCheck($original, $expected)
    {

        static::assertEquals(Payload::isUserName($original), $expected);

    }//end testPayloadUserNameCheck()


    /**
     * Test the channel validation
     *
     * @param string  $original the channel
     * @param boolean $expected the expected check result
     *
     * @dataProvider providerTestPayloadChannelCheck
     *
     * @return void
     */
    public function testPayloadChannelCheck($original, $expected)
    {

        static::assertEquals(Payload::isChannel($original), $expected);

    }//end testPayloadChannelCheck()


    /**
     * Provide data for testing Payload processing
     *
     * @return array
     */
    public function providerTestPayloadProcessing()
    {

        return array(
                array(
                 array(
                  'token'        => 'gIkuvaNzQIHg97ATvDxqgjtO',
                  'team_id'      => 'T0001',
                  'team_domain'  => 'example',
                  'channel_id'   => 'C2147483705',
                  'channel_name' => 'test',
                  'user_id'      => 'U2147483697',
                  'user_name'    => 'Steve',
                  'command'      => 'weather',
                  'text'         => '94070',
                  'response_url' => 'https://hooks.slack.com/commands/1234/5678',
                 ),
                 array(
                  'token'        => 'gIkuvaNzQIHg97ATvDxqgjtO',
                  'team_id'      => 'T0001',
                  'team_domain'  => 'testdomain',
                  'channel_id'   => 'C2147483705',
                  'channel_name' => 'blah',
                  'user_id'      => 'U2147483697',
                  'user_name'    => 'Steveo',
                  'command'      => 'testcommand',
                  'text'         => 'argument',
                  'response_url' => 'https://hooks.slack.com/commands/1234/5678',
                 ),
                ),
               );

    }//end providerTestPayloadProcessing()


    /**
     * Provide data for testing Payload username check
     *
     * @return array
     */
    public function providerTestPayloadUserNameCheck()
    {
        return array(
                array(
                 '@username',
                 true,
                ),
                array(
                 'username',
                 false,
                ),
                array(
                 'user@name',
                 false,
                ),
                array(
                 'username@',
                 false,
                ),
               );

    }//end providerTestPayloadUserNameCheck()


    /**
     * Provide data for testing Payload channel check
     *
     * @return array
     */
    public function providerTestPayloadChannelCheck()
    {
        return array(
                array(
                 '#channel',
                 true,
                ),
                array(
                 'channel',
                 false,
                ),
                array(
                 'chan#nel',
                 false,
                ),
                array(
                 'channel#',
                 false,
                ),
               );

    }//end providerTestPayloadChannelCheck()


}//end class
