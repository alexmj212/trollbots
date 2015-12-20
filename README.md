# Slack PHP Bot

A PHP implementation for Slack incoming webhooks

##Components

The backend is a [SlimPHP](https://github.com/slimphp/Slim) application which manages end points specified in `index.php`.

The datasource uses a mongo database for data persistance. I've configured one with [MongoLab](https://mongolab.com).

## Dependencies

Dependencies are listed in [composer.json](https://github.com/alexmj212/slackphp/blob/master/composer.json)

## Installation

`composer install` installs [SlimPHP](https://github.com/slimphp/Slim) from the package list in `composer.json`.

## Configuration

All endpoints must be specified per the SlimPHP specifications. Each end point can point to a class from the files in the `scripts/` directory. All files in the `scripts/` directory will be included when an endpoint is called.

All of the commands that are sent to the bot must be added as slash commands in your Slack configuration.

There are two base includes that all scripts will use to parse and send responses.
* Responder
* ProcessPayload
* DataSource

A file called `dataSource-default.php` is provided, fill in the database connection and rename the file to `dataSource.php`

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
* Channel Police Bot
  * Allows users to indicate the current conversation is not appropriate for the current channel.
* Trigger Bot
  * Allows users to let everyone know they've been triggered
* Pun Bot
  * Rate the puns of users and average their ratings
* Tip Bot
  * Tip users with points that accumulate over time
* DKP Bot
  * Give (or take) DKP from users and maintain their total over time
