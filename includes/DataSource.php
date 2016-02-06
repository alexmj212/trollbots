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

require  __DIR__.'/../config.php';

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
     * Mongo database collection
     *
     * @var MongoCollection
     */
    private $_mongo_collection;


    /**
     * Grab configuration data
     *
     * @param string $collectionName the name of the collection
     */
    public function __construct($collectionName = null)
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

        // Set the collection name if provided.
        if ($collectionName !== null) {
            $this->_mongo_collection = $this->_retrieveCollection($collectionName);
        }

    }//end __construct()


    /**
     * Attempt the connection to the mongo database
     *
     * @return boolean
     * @throws Exception
     */
    private function _connect()
    {

        try {
            $this->_mongo_connection = new Mongo($this->buildMongoConnectionString());
            if ($this->_mongo_connection === false) {
                throw new MongoConnectionException('Unable to connect to database '.$this->_mongo_domain);
            }

            $this->_mongo_dbo = $this->_mongo_connection->selectDB($this->_mongo_database);
            if ($this->_mongo_dbo === false) {
                throw new MongoConnectionException('Unable to select database '.$this->_mongo_database);
            }
        } catch (MongoConnectionException $e) {
            echo 'Mongo Connection Exception: '.$e->getMessage().'\n';
            return false;
        }

        return true;

    }//end _connect()


    /**
     * Build connection string
     *
     * @return string
     */
    public function buildMongoConnectionString()
    {
        // TODO: Unit test this.
        $connectionString  = 'mongodb://'.$this->_mongo_username.':';
        $connectionString .= $this->_mongo_password.'@';
        $connectionString .= $this->_mongo_domain.':'.$this->_mongo_port;
        $connectionString .= '/'.$this->_mongo_database;
        return $connectionString;

    }//end buildMongoConnectionString()


    /**
     * Return the database collection
     *
     * @return MongoCollection
     */
    public function getCollection()
    {

        return $this->_mongo_collection;

    }//end getCollection()


    /**
     * Retrive the database collection
     *
     * @param string $collectionName the name of the collection to retrieve
     *
     * @return MongoCollection
     */
    private function _retrieveCollection($collectionName = null)
    {
        if ($collectionName === null) {
            $collectionName = $this->_mongo_collection;
        }

        try {
            $this->_connect();
            $collection = $this->_mongo_dbo->selectCollection($collectionName);
            if ($collection === null) {
                throw new ErrorException('Unable to retrieve collection');
            }
        } catch (Exception $e) {
            echo 'Log '.$collectionName.' Error: ', $e->getMessage(), '\n';
            exit;
        }

        return $collection;

    }//end _retrieveCollection()


    /**
     * Retrieve the database document
     *
     * @param string $teamId the Id of the team
     *
     * TODO: Unit test return type.
     *
     * @return array
     */
    public function retrieveDocument($teamId)
    {

        $document = null;

        try {
            $document = $this->_mongo_collection->findOne(array('team_id' => $teamId));
            if ($document === false) {
                throw new MongoConnectionException('Team '.$teamId.' does not exist or was not created.');
            }

            if ($document === null) {
                throw new MongoConnectionException('Unable to search for team '.$teamId);
            }

            return $document;
        } catch (MongoConnectionException $e){
            echo 'Mongo Connection Exception: '.$e->getMessage();
            exit();
        }

    }//end retrieveDocument()


}//end class
