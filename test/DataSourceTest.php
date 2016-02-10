<?php


/**
 * DataSourceTest.php
 *
 * PHP version 5
 *
 * @category Test
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

require __DIR__.'/../includes/DataSource.php';

/**
 * Class DataSource Test
 *
 * @category DataSourceTest
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */
class DataSourceTest extends PHPUnit_Framework_TestCase
{


    /**
     * Ensure datasource is constructed and can connect
     *
     * @param array $conf database configuration
     *
     * @dataProvider providerTestDatabase
     *
     * @return void
     */
    public function testDataSourceConnectionString($conf)
    {

        $datasource = new DataSource(
            $conf['mongo_username'],
            $conf['mongo_password'],
            $conf['mongo_domain'],
            $conf['mongo_port'],
            $conf['mongo_database']
        );

        static::assertEquals(
            'mongodb://'.$conf['mongo_username'].':'.$conf['mongo_password'].'@'.$conf['mongo_domain'].':'.$conf['mongo_port'].'/'.$conf['mongo_database'],
            $datasource->buildMongoConnectionString()
        );

    }//end testDataSourceConnectionString()


    /**
     * Provide data for testing Database Connection
     *
     * @return array
     */
    public function providerTestDatabase()
    {

        return array(
                array(
                 array(
                  'mongo_username' => 'test',
                  'mongo_password' => 'pw',
                  'mongo_domain'   => 'example.com',
                  'mongo_port'     => 12345,
                  'mongo_database' => 'testdb',
                 ),
                ),
               );

    }//end providerTestDatabase()


}//end class
