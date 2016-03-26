<?php
/**
 * DKPBot.php
 *
 * PHP version 5
 *
 * @category Script
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

/**
 * Class DKPBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */
class DKPBot extends Bot
{

    const DKP_SELF_GRANT  = '*%s* has attempted to grant themselves DKP';
    const DKP_SELF_GRANT2 = 'but instead receives -%d DKP';
    const DKP_NEW_COUNT   = PHP_EOL.'*%s* now has %d DKP';
    const DKP_GRANT       = '*%s* has given *%s* %d DKP';
    const DKP_SCORE       = 'You have %s DKP';

    /**
     * The ID of the team
     *
     * @var int
     */
    private $_points;


    /**
     * DKPBot execute.
     *
     * @return void
     */
    public function execute()
    {

        $this->name           = 'DKP Bot';
        $this->icon           = ':dragon_face:';
        $this->collectionName = 'dkpbot';

        $post = new Post($this->name, $this->icon, '', $this->payload->getChannelName());

        $this->teamId = $this->payload->getTeamId();

        $userPoints = explode(' ', $this->payload->getText());
        if (count($userPoints) === 2) {
            $this->user    = $userPoints[0];
            $this->_points = (int) $userPoints[1];
        }

        if (is_numeric($this->_points) === true
            && $this->_points >= -10
            && $this->_points !== 0
            && $this->_points <= 10
            && Payload::isUserName($this->user) === true
        ) {
            if ($this->user === $this->payload->getUserName()) {
                $this->_points = (0 - abs($this->_points));
                $this->_logDKP();
                $response  = sprintf(DKPBot::DKP_SELF_GRANT, $this->payload->getUserName());
                $response .= sprintf(DKPBot::DKP_SELF_GRANT2, abs($this->_points));
                $response .= sprintf(DKPBot::DKP_NEW_COUNT, $this->user, $this->_retrieveDKP($this->user));
                $post->setText($response);
                $post->setResponseType(Post::RESPONSE_IN_CHANNEL);
            } else {
                $this->_logDKP();
                $response  = sprintf(DKPBot::DKP_GRANT, $this->payload->getUserName(), $this->user, $this->_points);
                $response .= sprintf(DKPBot::DKP_NEW_COUNT, $this->user, $this->_retrieveDKP($this->user));
                $post->setText($response);
                $post->setResponseType(Post::RESPONSE_IN_CHANNEL);
            }
        } else if ($this->payload->getText() === 'score') {
            $response = sprintf(DKPBot::DKP_SCORE, $this->_retrieveDKP($this->payload->getUserName()));
            $post->setText($response);
        } else if ($this->payload->getText() === 'rank') {
            $post->addAttachment($this->_ranking());
        } else {
            $post->setText(Post::INVALID_COMMAND);
        }//end if

        $responder = new Responder($post);
        $responder->respond();

    }//end execute()


    /**
     * Log the DKP sent by the user
     *
     * @return void
     * @throws ErrorException
     */
    private function _logDKP()
    {

        $userDoc = array();

        if ($this->userExists($this->user) === true) {
            // Yes this user exists.
            $userDoc[$this->user]['dkp'] += $this->_points;
            $userDoc[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
        } else {
            // No, add this user, start them at 500.
            $userDoc[$this->user]['dkp']     = (500 + $this->_points);
            $userDoc[$this->user]['created'] = date('Y-m-d H:i:s');
            $userDoc[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
        }

        $this->updateUser($this->teamId, $userDoc);

    }//end _logDKP()


    /**
     * Build the ranking table of all users
     *
     * @return string
     * @throws ErrorException
     */
    private function _ranking()
    {

        $document = $this->retrieveUserList();

        $document->users = Bot::sortUserList($document->users, 'dkp', SORT_DESC);

        $attachment = array(
                       'title' => 'DKP Leaderboard',
                       'text'  => '',
                      );

        foreach ($document->users as $user => $data) {
            $attachment['text'] .= $user.' - ';
            $attachment['text'] .= $data['dkp'].' DKP'.PHP_EOL;
        }

        $attachment['pretext'] = 'If you\'re not listed, you\'ve not received DKP';
        return $attachment;

    }//end _ranking()


    /**
     * Retrieve the DKP of the given user
     *
     * @param string $user the given user
     *
     * @return int
     * @throws ErrorException
     */
    private function _retrieveDKP($user = null)
    {
        if ($user === null) {
            // Verify user was supplied, otherwise use command user.
            $user = $this->user;
        }

        $document = $this->retrieveUserList();

        // Does this team exist?
        if (property_exists($document->users, $user) === true
        ) {
            return $document->users[$user]['dkp'];
        } else {
            return 500;
        }

    }//end _retrieveDKP()


}//end class
