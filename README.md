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
chmod +x twitterFix.sh
./twitterFix.sh
```
Now visit the test url <http://thecycletourist.com/rabbitApplication>. 

You should see the following:
![Example1](http://i.imgur.com/Q4np01j.jpg)


### Compatibility

Tested as working on the following:

* Firefox (PC)
* Chrome (PC and Android Tablet (S2) and Phone (A7))
* Safari (PC)
