<?php

/**
 * Bot.php
 *
 * PHP version 5
 *
 * @category Includes
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

/**
 * Class Bot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class Bot
{

    /**
     * The name of the Bot
     *
     * @var string
     */
    protected $name;

    /**
     * The icon to represent the bot
     *
     * @var string
     */
    protected $icon;

    /**
     * The user receiving or giving the Pun rating
     *
     * @var string
     */
    protected $user;

    /**
     * The ID of the team
     *
     * @var mixed
     */
    protected $teamId;

    /**
     * The name of the bots collection
     *
     * @var string
     */
    protected $collectionName;

    /**
     * The payload sent to the bot
     *
     * @var Payload
     */
    protected $payload;


    /**
     * Bot constructor.
     *
     * @param Payload $payload the sent payload
     */
    public function __construct($payload)
    {

        $this->payload = $payload;

        if (method_exists($this, 'execute') === true) {
            $this->execute();
        }

    }//end __construct()


    /**
     * Verify the request token
     *
     * @param string $name  the name of the bot
     * @param string $token the token sent by the request
     *
     * @return bool
     * @throws ErrorException
     */
    public static function verifyToken($name, $token)
    {

        $botToken = getenv($name.'_slack_token');

        if (strcmp($botToken, $token) !== 0) {
            return false;
        } else {
            return true;
        }

    }//end verifyToken()


    /**
     * Verify the request token
     *
     * @return string
     */
    public function getCollectionName()
    {

        return $this->collectionName;

    }//end getCollectionName()


    /**
     * Retrieve the list of Users from the bot's storage
     *
     * @return mixed
     * @throws ErrorException
     */
    public function retrieveUserList()
    {
        date_default_timezone_set('UTC');
        // Retrieve the database collection.
        $database = new DataSource($this->collectionName);
        // Retrieve the dkpbot document.
        $document = $database->retrieveDocument($this->teamId);

        // Does this team exist?
        if ($document !== null
            && property_exists($document, 'team_id') === true
            && $document->team_id === $this->teamId
            && property_exists($document, 'users') === true
        ) {
            return $document->users;
        } else {
            return null;
        }

    }//end retrieveUserList()


    /**
     * Verify user exists in document
     *
     * @param string $userName the name of the user to verify
     *
     * @return bool
     * @throws ErrorException
     */
    public function userExists($userName)
    {

        $userList = $this->retrieveUserList();
        return array_key_exists($userName, $userList);

    }//end userExists()


    /**
     * Sort user list by given field
     *
     * @param array  $users the given arrays of users
     * @param string $field the field that will be used for sorting
     * @param int    $order the order of sorting, true = asc, false = desc
     *
     * @return array
     */
    public static function sortUserList($users, $field, $order)
    {
        // Convert user array if needed.
        if (is_array($users) !== true) {
            $users = (array) $users;
        }

        $sortField = array();

        // Preserve array field for sorting.
        foreach ($users as $username => $user) {
            $sortField[] = $user[$field];
        }

        // Perform sort on field.
        array_multisort($sortField, $order, $users);

        return $users;

    }//end sortUserList()


    /**
     * Updates the specified user
     *
     * @param string $teamId  the id of the team
     * @param array  $userDoc the document associated with the user
     *
     * @return void
     * @throws ErrorException
     */
    public function updateUser($teamId, $userDoc)
    {
        $collection = new DataSource($this->collectionName);
        $collection = $collection->getCollection();
        try {
            $collection->updateOne(array('team_id' => $this->teamId), array('$set' => $userDoc));
        } catch (MongoCursorException $e){
            echo 'Unable to update user '.$this->user.' with team '.$this->teamId.': '.$e->getMessage();
        }

    }//end updateUser()


}//end class
