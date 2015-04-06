# TipBot for Slack

A mechanism to keep track of tips in a Slack chat.

`/tip @username` to send someone a tip.

`/tip total` to view your personal total.

##Components

The backend is a [SlimPHP](https://github.com/slimphp/Slim) application which manages the `/tip` end point and file manipulation. The front end is generated with [yo angular generator](https://github.com/yeoman/generator-angular). It reads from the data file `tips.json` and displays the information in a table.

## Dependencies

Dependencies are listed in [composer.json](https://github.com/alexmj212/tipbot/blob/master/composer.json), [package.json](https://github.com/alexmj212/tipbot/blob/master/package.json), and [bower.json](https://github.com/alexmj212/tipbot/blob/master/bower.json)

## Installation

`php composer.phar install` installs [SlimPHP](https://github.com/slimphp/Slim).

`npm install` to install node dependencies.

`bower install` to install frontend dependencies.

## Build & development

Run `grunt` for building and `grunt serve` for preview.

## Testing

Running `grunt test` will run the unit tests with karma.

##TODO

*Remove SlimPHP dependency and go full javascript
