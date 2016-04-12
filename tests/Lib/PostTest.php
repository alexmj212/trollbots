<?php

/**
 * PostTest.php
 *
 * PHP version 5
 *
 * @category Test
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

use TrollBots\Lib\Post;

/**
 * Class Post Test
 *
 * @category PostTest
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class PostTest extends PHPUnit_Framework_TestCase
{


    /**
     * Make sure the response type is ephemeral
     *
     * @param bool   $original switch for post visibility
     * @param string $expected response type set
     *
     * @dataProvider providerTestResponseType
     *
     * @return void
     */
    public function testResponseType($original, $expected)
    {
        $post = new Post('Test', ':test:', 'test', '#channel', $original);
        static::assertEquals($post->getResponseType(), $expected);

    }//end testResponseType()


    /**
     * Provide data for testing Response Type
     *
     * @return array
     */
    public function providerTestResponseType()
    {
        return array(
                array(
                 Post::RESPONSE_EPHEMERAL,
                 'ephemeral',
                ),
                array(
                 Post::RESPONSE_IN_CHANNEL,
                 'in_channel',
                ),
               );

    }//end providerTestResponseType()


    /**
     * Ensure attachments are pushed and return properly
     *
     * @param array $original the initial attachment
     * @param array $expected the resulting attachment
     *
     * @dataProvider providerTestAttachments
     *
     * @return void
     */
    public function testAttachments($original, $expected)
    {

        $post = new Post('Test', ':test:', 'test', '#channel');
        foreach ($original as $attachment) {
            if (is_array($attachment) === true) {
                $post->addAttachment($attachment);
            } else {
                $post->addAttachment($original);
                break;
            }
        }

        static::assertJsonStringEqualsJsonString(
            json_encode($post->getAttachments()),
            json_encode($expected)
        );

    }//end testAttachments()


    /**
     * Provide data for testing Attachments
     *
     * @return array
     */
    public function providerTestAttachments()
    {
        return array(
                array(
                 // Provided result.
                 array(
                  'text'  => 'this is a test',
                  'title' => 'Test Title',
                 ),
                 // Expected result.
                 array(
                  'attachments' => array(
                                    array(
                                     'text'  => 'this is a test',
                                     'title' => 'Test Title',
                                    ),
                                   ),
                 ),
                ),
                array(
                 array(
                  // Provided result.
                  array(
                   'text'  => 'this is a test',
                   'title' => 'Test Title',
                  ),
                  array(
                   'text'  => 'this is a second test',
                   'title' => 'Test Title',
                  ),
                 ),
                 array(
                  'attachments' => array(
                                    array(
                                     'text'  => 'this is a test',
                                     'title' => 'Test Title',
                                    ),
                                    array(
                                     'text'  => 'this is a second test',
                                     'title' => 'Test Title',
                                    ),
                                   ),
                 ),
                ),
               );

    }//end providerTestAttachments()


}//end class
