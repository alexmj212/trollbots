# Slack PHP Bot

A PHP implementation for Slack incoming webhooks

##Components

The backend is a [SlimPHP](https://github.com/slimphp/Slim) application which manages end points `specified in index.php`.

## Dependencies

Dependencies are listed in [composer.json](https://github.com/alexmj212/slackphp/blob/master/composer.json)

## Installation

`php composer.phar install` installs [SlimPHP](https://github.com/slimphp/Slim).

## Configuration

All endpoints must be specified per the SlimPHP specifications. Each end points can point to class from the files in the scripts directory. All files in the scripts directory will be included when an endpoint is called.

All of the commands that are sent to the bot must be added as slash commands in the Slack configuration.

There are two base includes that all scripts will use to parse and send responses.
* Responder
* ProcessPayload 

Because the Responder class must make a connection with Slack to post a message, it reads from the `config.php` file to retrieve the appropriate credentials.
You'll need to create a php file called config.php in the `includes` directory in order for the response to get the webhook url.
```
<?php

$webhookURL = <your slack webhook url>

?>
```

## Functionality

### Process Payload
The ProcessPayload class will take the incoming slack command and return a class that is made available to the script. This class has several functions that allow the user to retrieve and manipulate data associated with the payload.
* `setResponseText($responseText)`
  * Set the text of the response text that will be posted to Slack
* `getResponseText()`
  * Retrieve the response text
* `getChannelName()`
  * Get the name of the channel from which the command originated
* `getUserName()`
  * Get the username of the user who initated the command
* `getPayloadText()`
  * Retrive the specific command that was send in the payload
* `isUserName()`
  * Test to see if the payload text is a username (begins with '@' and is alpha numeric)
* `isChannel()`
  * Test to see if the payload text is a channel (begins with '#')

### Responder
The Responder class allows the script to respond to the incoming request. It take fives separate arguments that allow you to customize the response.
* `botName`
  * The name that the bot will use in the response posted to Slack
* `botIcon`
  * The icon that the bot will use in the response posted to Slack (usually one of Slack's emoji codes)
* `botText`
  * What the bot will say in the response posted to Slack
* `botChannel`
  * The channel that the bot will post to
* `botVisibility`
  * A true/false for whether the bot will respond to everyone in the channel or just the original user
    * True - the bot posts a response visibile to everyone in the channel
    * False - the bot posts a response only the original use can see

## Examples
Several example response are included in the scripts directory which demonstrate the use of Slack PHP Bot.
