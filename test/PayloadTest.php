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
     * @param array $expected response type set
     *
     * @dataProvider providerTestPayloadProcessing
     *
     * @return void
     */
    public function testPayloadProcessing($original, $expected)
    {

        $payload = new Payload($original);

        static::assertEquals(substr($payload->getChannelName(), 0, 1), '#');
        static::assertEquals(substr($payload->getUserName(), 0, 1), '@');

    }//end testPayloadProcessing()


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
                    'token' => 'gIkuvaNzQIHg97ATvDxqgjtO',
                    'team_id' => 'T0001',
                    'team_domain' => 'example',
                    'channel_id' => 'C2147483705',
                    'channel_name' => 'test',
                    'user_id' => 'U2147483697',
                    'user_name' => 'Steve',
                    'command' => 'weather',
                    'text' => '94070',
                    'response_url' => 'https://hooks.slack.com/commands/1234/5678'
                ),
                array(
                    'token' => 'gIkuvaNzQIHg97ATvDxqgjtO',
                    'team_id' => 'T0001',
                    'team_domain' => 'testdomain',
                    'channel_id' => 'C2147483705',
                    'channel_name' => 'blah',
                    'user_id' => 'U2147483697',
                    'user_name' => 'Steveo',
                    'command' => 'testcommand',
                    'text' => 'argument',
                    'response_url' => 'https://hooks.slack.com/commands/1234/5678'
                ),
            ),
        );

    }//end providerTestPayloadProcessing()


}
