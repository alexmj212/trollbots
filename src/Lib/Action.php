<?php

/**
 * Action.php
 *
 * PHP version 5
 *
 * @category Includes
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

namespace TrollBots\Lib;

/**
 * Class Action
 *
 * @category Action
 * @package  TrollBots
 * @author   Alex Johnson <alexmj212@gmail.com>
 * @license  http://opensource.org/licenses/GPL-3.0 GPL 3.0
 * @link     https://github.com/alexmj212/trollbots
 */

class Action
{

    const DEFAULT_STYLE = 'default';
    const PRIMARY_STYLE = 'primary';
    const DANGER_STYLE  = 'danger';

    /**
     * The name of the action that will appear in Slack
     *
     * @string
     */
    private $_name;

    /**
     * The text of the action that will appear in Slack
     *
     * @string
     */
    private $_text;

    /**
     * The style of the action, based on the provided class constants
     *
     * @string
     */
    private $_style = Action::DEFAULT_STYLE;

    /**
     * The type of action, button is the only action available right now
     *
     * @string
     */
    private $_type = 'button';

    /**
     * The value that uniquely identifies the actions
     *
     * @string
     */
    private $_value;


    /**
     * Post constructor
     *
     * @param string $name  The name of the action
     * @param string $text  The text of the action
     * @param string $style The style of the action
     * @param string $type  The type of action
     * @param string $value The value to identify the action
     */
    public function __construct($name, $text, $style = null, $type = null, $value = null)
    {
        $this->_name  = $name;
        $this->_text  = $text;
        $this->_style = $style;
        $this->_type  = 'button';
        $this->_value = $value;

    }//end __construct()


    /**
     * Returns array item for the action
     *
     * @return array
     */
    public function toArray()
    {
        return array(
                'name'  => $this->_name,
                'text'  => $this->_text,
                'style' => $this->_style,
                'type'  => $this->_type,
                'value' => $this->_value,
               );

    }//end toArray()


}//end class
