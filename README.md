# Troll Bots

[![Travis CI](https://travis-ci.org/alexmj212/trollbots.svg?branch=master)](https://travis-ci.org/alexmj212/trollbots)

A PHP implementation for Slack slash commands and incoming webhooks

## Dependencies & Installation

Dependencies are listed in [composer.json](https://github.com/alexmj212/slackphp/blob/master/composer.json)

`composer install` installs [SlimPHP](https://github.com/slimphp/Slim) from the package list in `composer.json`.

The request handling is a [SlimPHP](https://github.com/slimphp/Slim) application which manages end points specified in `index.php`. The datasource uses a mongo database for data persistance. I've configured one with [MongoLab](https://mongolab.com).

## Configuration

All endpoints must be specified per the SlimPHP specifications. Each end point can point to a class from the files in the `scripts/` directory. All files in the `scripts/` directory will be included when an endpoint is called.

All of the commands that are sent to the bot must be added as slash commands in your Slack configuration.

There are several base includes that all scripts will use to parse and send responses.
* Post
* Responder
* Payload
* DataSource
* Bot

All configuration is stored in `config.php` in the root directory to allow for storage of environment passwords and configuration information. Each of the above classes seeks the specific configuration information and verifies the apporpriate credentials are available.

Currently, there are two variations of OAuth, one which specifies the Slack OAuth for handling integration requests and the second is for the Subreddit Bot which handles access to the Reddit API.

## Functionality

## Bot
The Bot class contains the basic fields required for a bot and a few additional methods. These include:
* `verifyToken()`
  * Verify the payload token is the correct one provided by Slack for verification
* `sortUserList()`
  * Sort the user document based on the given field

### Payload
The ProcessPayload class will take the incoming slack command and return a class that is made available to the script. This class has several setters and getters that allow the user to retrieve and manipulate data associated with the payload. In addition, there are verification checks here to validate usernames ot channel names in the slack command arguments.
* `isUserName()`
  * Test to see if the payload text is a username (begins with '@' and is alpha numeric)
* `isChannel()`
  * Test to see if the payload text is a channel (begins with '#')

### Responder
The Responder class echos the script to respond to the incoming request. It simply echos the provided post in the slash command response. Details about how responses to slash commands are handled are detailed [here](https://api.slack.com/slash-commands)

### Post
This is the post object that is used to store information related to the response that will be sent to Slack. The Post contains several fields that are not required to send a response to a slash command. However, additional response mechanisms such as incoming webhooks may utilize these fields.
* `Name`
  * The name that the bot will use in the response posted to Slack
* `Icon`
  * The icon that the bot will use in the response posted to Slack (usually one of Slack's emoji codes)
* `Text`
  * What the bot will say in the response posted to Slack
* `Channel`
  * The channel that the bot will post to
* `Attachments`
  * [Attachments](https://api.slack.com/docs/attachments) are an additional type of post content that can display information other than straight text.
* `Response Type`
  * Whether the bot will respond to everyone in the channel or just the original user
    * `in_channel` - the bot posts a response visibile to everyone in the channel
    * `ephemeral` - default - the bot posts a response only the original user can see

## Examples
Several example response are included in the scripts directory which demonstrate the use of Troll Bots.
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
* Sarcasm
  * Make it obvious you weren't serious because someone thought you were
  
