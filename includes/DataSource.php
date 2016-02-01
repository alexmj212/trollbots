<?php

/**
 * DataSource.php
 *
 * PHP version 5
 *
 * @category Configuration
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */

require '/../config.php';

/**
 * Class DataSource
 *
 * @category DataSource
 * @package  SlackPHPbot
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/slackphpbot
 */
class DataSource
{
    /**
     * Mongo Database Username
     *
     * @var string
     */
    private $_mongo_username;

    /**
     * Mongo Database Password
     *
     * @var string
     */
    private $_mongo_password;

    /**
     * Mongo Database Domain Name or IP Address
     *
     * @var string
     */
    private $_mongo_domain;

    /**
     * Mongo Database Database Name
     *
     * @var string
     */
    private $_mongo_database;

    /**
     * Mongo Database Port
     *
     * @var int
     */
    private $_mongo_port;
    /**
     * Mongo connection placeholder
     *
     * @var Mongo
     */
    private $_mongo_connection;

    /**
     * Mongo database instance class
     *
     * @var MongoDB
     */
    private $_mongo_dbo;


    /**
     * Grab configuration data
     */
    public function __construct()
    {

        global $conf;

        try {
            if (array_key_exists('mongo_username', $conf['datasource']) === true) {
                $this->_mongo_username = $conf['datasource']['mongo_username'];
            } else {
                throw new ErrorException('Missing Mongo Username');
            }

            if (array_key_exists('mongo_pw', $conf['datasource']) === true) {
                $this->_mongo_password = $conf['datasource']['mongo_pw'];
            } else {
                throw new ErrorException('Missing Mongo Password');
            }

            if (array_key_exists('mongo_domain', $conf['datasource']) === true) {
                $this->_mongo_domain = $conf['datasource']['mongo_domain'];
            } else {
                throw new ErrorException('Missing Mongo Domain');
            }

            if (array_key_exists('mongo_database_name', $conf['datasource']) === true) {
                $this->_mongo_database = $conf['datasource']['mongo_database_name'];
            } else {
                throw new ErrorException('Missing Mongo Database Name');
            }

            if (array_key_exists('mongo_port', $conf['datasource']) === true) {
                $this->_mongo_port = $conf['datasource']['mongo_port'];
            } else {
                throw new ErrorException('Missing Mongo Port');
            }
        } catch (Exception $e) {
            echo 'Database Configuration Error: ', $e->getMessage(), '\n';
            exit();
        }//end try

    }//end __construct()


    /**
     * Attempt the connection to the mongo database
     *
     * @return boolean
     * @throws Exception
     */
    private function _connect()
    {

        if ($this->_mongo_connection = new Mongo($this->buildMongoConnectionString()) === false) {
            throw new MongoConnectionException('Unable to connect to database '.$this->_mongo_domain);
        }

        if ($this->_mongo_dbo = $this->_mongo_connection->selectDB($this->_mongo_database) === false) {
            throw new MongoConnectionException('Unable to select database '.$this->_mongo_database);
        }

        return true;

    }//end _connect()


    /**
     * Retrieve the specified collection
     *
     * @param string $collection the collection requested
     *
     * @return MongoCollection
     * @throws Exception
     */
    public function getCollection($collection)
    {
        try {
            $this->_connect();
            return $this->_mongo_dbo->selectCollection($collection);
        } catch (Exception $e) {
            echo 'Error Retrieving Collection: ', $e->getMessage(), '\n';
            exit();
        }

    }//end getCollection()


    /**
     * Build connection string
     *
     * @return string
     */
    public function buildMongoConnectionString()
    {
        $connectionString  = 'mongodb://'.$this->_mongo_username.':';
        $connectionString .= $this->_mongo_password.'@';
        $connectionString .= $this->_mongo_domain.':'.$this->_mongo_port;
        return $connectionString;

    }//end buildMongoConnectionString()


}//end class
