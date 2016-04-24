<?php
// vendor/Lib/Service1/Custom.php
 
namespace RabbitInternet\TwitterMap;
use ZendService\Twitter\Twitter;
 
class TwitterMap
{
    /**
     * TweetMaps class. This class Abstracts the logic to connect to twitter,
     * retrieve the required tweets and assign them to an Array to send to the
     * front end for processing.
     */
    
    var $lat, $long;

    function configure($config) {
        $this->twitter = new Twitter($config['twitter']);
        $this->twitter_radius = $config['defaults']['twitter']['radius'];
        $this->twitter_count = $config['defaults']['twitter']['count'];
        $this->default_city = $config['defaults']['map']['default_city'];
        $this->lat = $config['defaults']['map']['default_city_lat'];
        $this->long = $config['defaults']['map']['default_city_long'];
    }
    
    function get_default_city() {
        return $this->default_city;
    }
    
    function get_twitter_count() {
        return $this->twitter_count;
    }
    
    function get_map_lat() {
        return $this->lat;
    }
    
    function get_map_long() {
        return $this->long;
    }
    
    function set_twitter_count($twitter_count) {
        $this->twitter_count = $twitter_count;
    }
    
    function verifyCredentials() {
        $response = $this->twitter->account->verifyCredentials();
		if (!$response->isSuccess()) {
            if($response->errors[0]->code != 88) {
                die('Something is wrong with my credentials!');
            } else {
                $errMsg = "You have exceeded the Rate Limit for Geocode searching. Please try again in 5 minutes";
                return $errMsg;
            }
        }
        
    }
    
    function geocodeAddress($address) {
        $urlencodedAddress = urlencode($address);
        $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $urlencodedAddress . "&sensor=false";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $geoloc = json_decode(curl_exec($ch), true);
        
        return $geoloc;   
    }
    
    function get_GeocodedTweets($address) {
    /* This function is the main meat of the TweetMaps class. It processes
       The address provided to it, connects to Twitter, retrieves the geoCoded tweets
       (Based on configuration options) and returns them */
        
        $geoloc = $this->geocodeAddress($address);
        
        $this->lat = $geoloc['results'][0]['geometry']['location']['lat'];
        $this->long = $geoloc['results'][0]['geometry']['location']['lng'];

        /* 
           Let's make some assumptions about the city name.
           Firstly, let's ignore spaces, as many cities have spaces in their names
           Instead, let's break the name up if a user has used a comma, for example 'Melbourne, Australia'
           The geocoding can using the entire address, but we just want to search for 'Melbourne'
        */
        if (strpos($address, ',') !== false) {
            $city_name = explode(',', $address)[0];
        } else {
            $city_name = $address;
        };
        print $this->lat.','.$this->long.','.$this->twitter_radius;
        $response = $this->twitter->search->tweets($city_name,array('count'=>$this->twitter_count,'geocode'=>$this->lat.','.$this->long.','.$this->twitter_radius+',')); 
        // Third comma added to geocode parameter above as the ZendService\Twitter library is broken and throws an error if you just use lat,long,raduis.
    
        $markers = array();
        foreach ($response->toValue()->statuses as $tweet) {
            if(isset($tweet->user->profile_image_url)) {
                $tweet_profile_image = $tweet->user->profile_image_url;
            } else {
                $tweet_profile_image = '';
            }
            
            $tweet_user_name= $tweet->user->screen_name;

            if(isset($tweet->coordinates->coordinates)) { 
                $coords = $tweet->coordinates->coordinates;
                $tweet_long = $coords[0];
                $tweet_lat = $coords[1];
                //Get lat, long and image, and push to array
                $tempMarker = array($tweet_lat, $tweet_long, $tweet_profile_image,$tweet->text,$tweet_user_name);
                array_push($markers,$tempMarker);
            }
		}
	
        return $markers;
    }

}