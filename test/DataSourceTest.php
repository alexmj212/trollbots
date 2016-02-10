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

require '../includes/DataSource.php';
require  __DIR__.'/../config.php';

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
     * @return void
     */
    public function testDataSourceConnection()
    {

        $datasource = new DataSource();

        static::assertTrue($datasource->connect());

    }//end testDataSourceConnection()


    /**
     * Ensure datasource is constructed and can connect
     *
     * @return void
     * @throws ErrorException
     */
    public function testDataSourceConnectionString()
    {

        global $conf;

        $mongo_username = null;
        $mongo_password = null;
        $mongo_domain   = null;
        $mongo_port     = null;
        $mongo_database = null;

        $datasource = new DataSource();

        if (array_key_exists('mongo_username', $conf['datasource']) === true) {
            $mongo_username = $conf['datasource']['mongo_username'];
        } else {
            throw new ErrorException('Missing Mongo Username');
        }

        if (array_key_exists('mongo_pw', $conf['datasource']) === true) {
            $mongo_password = $conf['datasource']['mongo_pw'];
        } else {
            throw new ErrorException('Missing Mongo Password');
        }

        if (array_key_exists('mongo_domain', $conf['datasource']) === true) {
            $mongo_domain = $conf['datasource']['mongo_domain'];
        } else {
            throw new ErrorException('Missing Mongo Domain');
        }

        if (array_key_exists('mongo_database_name', $conf['datasource']) === true) {
            $mongo_database = $conf['datasource']['mongo_database_name'];
        } else {
            throw new ErrorException('Missing Mongo Database Name');
        }

        if (array_key_exists('mongo_port', $conf['datasource']) === true) {
            $mongo_port = $conf['datasource']['mongo_port'];
        } else {
            throw new ErrorException('Missing Mongo Port');
        }

        static::assertEquals(
            'mongodb://'.$mongo_username.':'.$mongo_password.'@'.$mongo_domain.':'.$mongo_port.'/'.$mongo_database,
            $datasource->buildMongoConnectionString()
        );

    }//end testDataSourceConnectionString()


}//end class
