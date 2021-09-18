# squeet

squeet (high-pitched voice required on pronunciation) is a simple tech demo that searches [Twitter](http://twitter.com/) in realtime and displays the results in a grid of squares. Each result contains the profile image of the twitter user and if you hover your mouse over that, the tweet text they posted.

It looks like this:

![squeet](docs/images/squeet.gif)

squeet isn't intended to be much practical use. It's just an experiment and a toy.

## Running squeet

squeet is a PHP/JS web app and runs in a browser. To get it going on your own machine/server you'll need to be familiar with Docker and Docker Compose. You'll also need to be registered with [Twitter's Developer Platform](https://developer.twitter.com) so you can generate some API keys for squeet to use.

Then you simply clone this repo, plug in the various environmental values into the docker-compose.yaml file and type the following at terminal:

```
docker-compose up -d
```

If you didn't want to go the Docker route and you're familiar with PHP, MySQL and Composer (package manager), you could copy and snoop around in the containers/app/src directory. Configure the includes/header.php and search.php files there manually and then run squeet on pretty much any PHP-compatible web server. You'll need to create the database manually too - see containers/app/deploy/start.php for the schema.

## Historic info

squeet was originally developed *many* years ago now. One of its core dependencies is the javascript library [Mootools](https://mootools.net) which is no longer in active development :-( The last version is included in this repo and has legacy code compatibility. squeet was updated more recently to keep it alive... it now uses new Twitter API libraries, Composer and Docker (for quick deployment).