<?php

/**
 * DataSource.php
 *
 * PHP version 5
 *
 * @category Configuration
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

namespace TrollBots\Lib;
use MongoDB;
use MongoDB\Client;
use MongoDB\Exception;
use MongoDB\Driver\Exception\ConnectionException;
use ErrorException;

/**
 * Class DataSource
 *
 * @category DataSource
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
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
     * @var MongoDB\Client
     */
    private $_mongo_connection;

    /**
     * Mongo database instance class
     *
     * @var MongoDB\Database
     */
    private $_mongo_dbo;

    /**
     * Mongo database collection
     *
     * @var MongoDB\Collection
     */
    private $_mongo_collection;

    /**
     * Mongo database collection name
     *
     * @var string
     */
    private $_mongo_collectionName;


    /**
     * Grab configuration data
     *
     * @param string $collectionName the name of the collection
     * @param string $userName       connection user
     * @param string $password       connection pw
     * @param string $domain         connection domain
     * @param int    $port           connection port
     * @param string $database       connection database name
     *
     * @throws ErrorException
     * @throws MongoDB\Driver\Exception\ConnectionException
     * @throws MongoDB\Exception\InvalidArgumentException
     */
    public function __construct(
        $collectionName = null,
        $userName = null,
        $password = null,
        $domain = null,
        $port = null,
        $database = null
    ) {

        global $conf;

        try {
            if ($conf === null || is_array($conf) !== true || array_key_exists('datasource', $conf) !== true) {
                throw new ErrorException('Missing datasource config');
            }

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

            // Set the default port.
            $this->_mongo_port = 27017;
            if (array_key_exists('mongo_port', $conf['datasource']) === true) {
                $this->_mongo_port = $conf['datasource']['mongo_port'];
            }
        } catch (ErrorException $e) {
            echo 'Database Configuration Error: ', $e->getMessage(), '\n';
        }//end try

        if ($userName !== null) {
            $this->_mongo_username = $userName;
        }

        if ($password !== null) {
            $this->_mongo_password = $password;
        }

        if ($domain !== null) {
            $this->_mongo_domain = $domain;
        }

        if ($database !== null) {
            $this->_mongo_database = $database;
        }

        if ($port !== null) {
            $this->_mongo_port = $port;
        }

        // Set the collection name if provided.
        try {
            if ($collectionName !== null) {
                $this->_mongo_collectionName = $collectionName;
                $this->_mongo_collection     = $this->_retrieveCollection($collectionName);
            }
        } catch (\Exception $e) {
            echo 'Unable to retrieve collection with name '.$collectionName.' with error: '.$e->getMessage();
        }

    }//end __construct()


    /**
     * Attempt the connection to the mongo database
     *
     * @return boolean
     * @throws MongoDB\Driver\Exception\ConnectionException
     * @throws MongoDB\Exception\InvalidArgumentException
     */
    public function connect()
    {

        try {
            $this->_mongo_connection = new Client($this->buildMongoConnectionString());
            if ($this->_mongo_connection === false) {
                throw new ConnectionException('Unable to connect to database '.$this->_mongo_domain);
            }

            $this->_mongo_dbo = $this->_mongo_connection->selectDatabase($this->_mongo_database);
            if ($this->_mongo_dbo === false) {
                throw new ConnectionException('Unable to select database '.$this->_mongo_database);
            }
        } catch (ConnectionException $e) {
            echo 'Mongo Connection Exception: '.$e->getMessage().'\n';
            return false;
        }

        return true;

    }//end connect()


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
        $connectionString .= '/'.$this->_mongo_database;
        return $connectionString;

    }//end buildMongoConnectionString()


    /**
     * Return the database collection
     *
     * @return MongoDB\Collection
     * @throws MongoDB\Driver\Exception\ConnectionException
     * @throws MongoDB\Exception\InvalidArgumentException
     */
    public function getCollection()
    {

        if ($this->_mongo_collection !== null) {
            return $this->_mongo_collection;
        }

        try {
            $this->connect();
            $collection = $this->_mongo_dbo->selectCollection($this->_mongo_collectionName);
            if ($collection === null) {
                throw new ErrorException('Unable to retrieve collection');
            }
        } catch (ErrorException $e) {
            echo 'Log '.$this->_mongo_collectionName.' Error: ', $e->getMessage(), '\n';
            exit;
        }

        return $collection;

    }//end getCollection()


    /**
     * Retrive the database collection
     *
     * @param string $collectionName the name of the collection to retrieve
     *
     * @return MongoDB\Collection
     * @throws MongoDB\Driver\Exception\ConnectionException
     * @throws MongoDB\Exception\InvalidArgumentException
     */
    private function _retrieveCollection($collectionName = null)
    {

        try {
            $this->connect();
            $collection = $this->_mongo_dbo->selectCollection($collectionName);
            if ($collection === null) {
                throw new ErrorException('Unable to retrieve collection');
            }
        } catch (ErrorException $e) {
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
     * @return mixed
     */
    public function retrieveDocument($teamId)
    {

        try {
            return $this->_mongo_collection->findOne(array('team_id' => $teamId));
        } catch (\Exception $e){
            echo 'Unable to find team with ID: '.$teamId;
        }

    }//end retrieveDocument()


}//end class
