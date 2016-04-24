Rabbit Internet Test Application
================================

Introduction
------------
This application has been created to support an application for work.

This application has been created using Zend Framework 2 and requires the php composer to install dependencies.

It also comes with a prepacked library (RabbitInternet/TweetMaps) in the vendor folder. This directory contains all the logic pertaining to accessing twitter and geocoding tweets that relate to the selected city.

Note: Twitter users must have the geolocation feature for their tweets turned on, as this app makes them to their gps coordinates.

### Installation

Install the vendor dependencies:
```
php composer.phar update
```

Ensure the cache directories are readable by the PHP service. To allow all users access to a folder (not recommended), use the following:
```
chmod -R 777 data
```

Run the fixTwitter shell script to fix the 'zendService-twitter' package installed by composer
```
chmod +x fixTwitter.sh
./fixTwitter.sh
```
Now visit the test url <http://thecycletourist.com/rabbitApplication>. 

You should see the following:
![Example1](http://i.imgur.com/Q4np01j.jpg)


### Compatibility

Tested as working on the following:

* Firefox (PC)
* Chrome (PC and Android Tablet (S2) and Phone (A7))
* Safari (PC)


### Configuration Options
All of the configuration options are available in the config/autoload/global.php file as a series of named Arrays.

```
* Twitter: In the twitter array you can change the API keys used to access the tweets, as well as the http_client used to 
  access the tweets. I am using the Zend curl library.

* Defaults: These are parameters that Ihave added into the application (as opposed to the twitter array that is used by the zendservice-twitter library). You can change the search radius and number of tweets displayed here, as well as change the default location to display (Bangkok is the current default)

* Caching: There is a service factory created for caching, and caching is done via the filesystem. You can change this, as well as the cache expiry (ttl) here.
```

### LocalStorage

This application uses the localStorage of a users browser to track their history. This does not affect caching of the searched locations, as this is all in PHP, rather just the list of previously searched items. All modern browsers now accept data being written to LocalStorage. 




