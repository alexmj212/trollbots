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

namespace TrollBots\Scripts;

use TrollBots\Lib\Action;
use TrollBots\Lib\Attachment;
use TrollBots\Lib\Bot;
use TrollBots\Lib\Payload;
use TrollBots\Lib\Post;
use TrollBots\Lib\Responder;

/**
 * Class DKPBot
 *
 * @category Bot
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 * @release  2
 */
class DKPBot extends Bot
{

    const DKP_SELF_GRANT  = '*%s* has attempted to grant themselves DKP';
    const DKP_SELF_GRANT2 = 'but instead receives -%d DKP';
    const DKP_NEW_COUNT   = PHP_EOL.'*%s* now has %d DKP';
    const DKP_GRANT       = '*%s* has given *%s* %d DKP';
    const DKP_SCORE       = 'You have %d DKP';
    const DKP_TOO_SOON    = 'Please wait before giving *%s* DKP';
    const DKP_VOTE_START  = '*%s* has started a vote to take %d DKP from *%s*. 3 votes are needed.';
    const DKP_VOTE_COUNT  = 'Current Count: %d/3';
    const DKP_VOTE        = PHP_EOL.'Votes from: *%s*';
    const DKP_CALLBACK    = 'dkp_bot';

    /**
     * The points to to be saved
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
        $post->setReplaceOriginal(true);

        if (get_class($this->payload) === 'TrollBots\Lib\ActionPayload') {
            $count = array();

            $count_line = explode(PHP_EOL, $this->payload->getText());

            preg_match('/(\d)\/\d/', $count_line[1], $count);
            $count[1]++;

            $response  = $count_line[0];
            $response .= sprintf(DKPBot::DKP_VOTE_COUNT, $count[1]);
            $post->setText($response);
            $responder = new Responder($post);
            $responder->respond();
            exit();
        }



        $this->teamId = $this->payload->getTeamId();

        switch ($this->payload->getText()) {
        case 'rank':
            $post->addAttachment($this->_ranking());
            break;
        case 'score':
            $response = sprintf(DKPBot::DKP_SCORE, $this->_retrieveDKP($this->payload->getUserName()));
            $post->setText($response);
            break;
        default:
            $userPoints = explode(' ', $this->payload->getText());
            if (count($userPoints) === 2) {
                $this->user    = $userPoints[0];
                $this->_points = (int) $userPoints[1];
            }

            if (Payload::isUserName($this->user) === true) {
                // Valid username.
                if ($this->user === $this->payload->getUserName()) {
                    // User tried to gift themselves.
                    $this->_points = (0 - abs($this->_points));
                    $this->_logDKP();
                    $response  = sprintf(DKPBot::DKP_SELF_GRANT, $this->payload->getUserName());
                    $response .= sprintf(DKPBot::DKP_SELF_GRANT2, abs($this->_points));
                    $response .= sprintf(DKPBot::DKP_NEW_COUNT, $this->user, $this->_retrieveDKP($this->user));
                    $post->setText($response);
                    $post->setResponseType(Post::RESPONSE_IN_CHANNEL);
                /*} else if ($this->_checkLastReceived($this->user) !== true) {
                    // Too soon to grant the user DKP.
                    $response = sprintf(DKPBot::DKP_TOO_SOON, $this->user);
                    $post->setText($response);
                    $post->setResponseType(Post::RESPONSE_EPHEMERAL);*/
                } else if (is_numeric($this->_points) === true) {
                    // Valid number.
                    if ($this->_points > 0 && $this->_points <= 10) {
                        // Between 0 and 10.
                        $this->_logDKP();
                        $response  = sprintf(DKPBot::DKP_GRANT, $this->payload->getUserName(), $this->user, $this->_points);
                        $response .= sprintf(DKPBot::DKP_NEW_COUNT, $this->user, $this->_retrieveDKP($this->user));
                        $post->setText($response);
                        $post->setResponseType(Post::RESPONSE_IN_CHANNEL);

                    } else if ($this->_points < 0 && $this->_points >= -50) {
                        // -50 DKP!!!
                        $response  = sprintf(DKPBot::DKP_VOTE_START.PHP_EOL, $this->payload->getUserName(), $this->_points, $this->user);
                        $response .= sprintf(DKPBot::DKP_VOTE_COUNT, 0);

                        $attachmentTitle = sprintf('Vote to take %d DKP from %s', abs($this->_points), $this->user);

                        $attachment = new Attachment($attachmentTitle, $attachmentTitle, DKPBot::DKP_CALLBACK);

                        $actionButton = sprintf('Take %d DKP', $this->_points);
                        $attachment->addAction(new Action($attachmentTitle, $actionButton, Action::ACTION_PRIMARY_STYLE));
                        $post->addAttachment($attachment);
                        $post->setText($response);
                        $post->setResponseType(Post::RESPONSE_IN_CHANNEL);
                    } else {
                        $post->setText(Post::INVALID_COMMAND);
                    }
                }
            } else {
                $post->setText(Post::INVALID_COMMAND);
            }//end if
        }//end switch

        $responder = new Responder($post);
        $responder->respond();

    }//end execute()


    /**
     * Ensure user can receive DKP
     *
     * @param string $user the given user
     *
     * @return bool
     */
    private function _checkLastReceived($user)
    {
        try {
            $users = Bot::retrieveUserList();
            if (property_exists($users, $user) === true) {
                $now = strtotime('now');
                $last_received_date = strtotime($users[$user]['last_received_date']);
                // $last_received_user = $users[$user]['last_received_user'];
                // Time delay to prevent too much DKP (in seconds).
                return !(($now - $last_received_date) < 30);
            }

            return false;
        } catch (\Exception $e) {
            echo 'Unable to check last received for user '.$user.': '.$e->getMessage();
            exit();
        }

    }//end _checkLastReceived()


    /**
     * Log the DKP sent by the user
     *
     * @return void
     */
    private function _logDKP()
    {
        try {
            $userDoc = Bot::retrieveUserList();

            if (Bot::userExists($this->user) === true) {
                // Yes this user exists.
                $userDoc[$this->user]['dkp'] += $this->_points;
                $userDoc[$this->user]['last_received_user'] = $this->payload->getUserName();
                $userDoc[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
            } else {
                // No, add this user, start them at 500.
                $userDoc[$this->user]['dkp']     = (500 + $this->_points);
                $userDoc[$this->user]['created'] = date('Y-m-d H:i:s');
                $userDoc[$this->user]['last_received_user'] = $this->payload->getUserName();
                $userDoc[$this->user]['last_received_date'] = date('Y-m-d H:i:s');
            }

            Bot::updateUser($this->teamId, $userDoc);
        } catch (\Exception $e) {
            echo 'Unable to log DKP for user '.$this->user.': '.$e->getMessage();
            exit();
        }//end try

    }//end _logDKP()


    /**
     * Build the ranking table of all users
     *
     * @return Attachment
     */
    private function _ranking()
    {
        try {
            $users = $this->retrieveUserList();
            $users = Bot::sortUserList($users, 'dkp', SORT_DESC);

            $attachment = new Attachment('DKP Leaderboard', 'DKP Leaderboard', 'leaderboard');

            $attachmentText = '';

            foreach ($users as $user => $data) {
                $attachmentText .= $user.' - ';
                $attachmentText .= $data['dkp'].' DKP'.PHP_EOL;
            }

            $attachment->setText($attachmentText);
            $attachment->setPretext('If you\'re not listed, you\'ve not received DKP');

            return $attachment;
        } catch (\Exception $e) {
            echo 'Unable to generate ranking: '.$e->getMessage();
            exit();
        }//end try

    }//end _ranking()


    /**
     * Retrieve the DKP of the given user
     *
     * @param string $user the given user
     *
     * @return int
     */
    private function _retrieveDKP($user = null)
    {
        if ($user === null) {
            // Verify user was supplied, otherwise use command user.
            $user = $this->user;
        }

        try {
            $users = $this->retrieveUserList();
            // Does this team exist?
            if (property_exists($users, $user) === true) {
                return $users[$user]['dkp'];
            } else {
                return 500;
            }
        } catch (\Exception $e) {
            echo 'Unable to retrieve DKP for user '.$user.': '.$e->getMessage();
            exit();
        }

    }//end _retrieveDKP()


}//end class
