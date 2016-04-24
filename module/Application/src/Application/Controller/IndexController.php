<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;
use ZendService\Twitter\Twitter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManagerAware;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        /**
         * @author  Peter Griffin
         * @version 1.0
         *
         *  This function is the main entry point for the Index Controller, and it controlls all of the logic in our Single Page App. 
         * 
         *  To reduce reading complexity and to adhere to the guidelines of this test, I've moved parts of the logic into separate modules,
         *  particularly the RabbitInternet module under the vendors folder
         *
         *  This function does the following:
         *  1. Pulls configuration values from the global.php file (config/autoload/global.php) and applies them to the Twitter Map object
         *  2. Checks if the address parameter was set via a post request (i.e, checking if a User has supplied a city).
         *     If the city is blank, or has not been entered, the default city (configurable in the aforementioned file) is used instead.
         *
         *  3. Checks the cache (configurable in global.php) and if it has data in it, uses it. If not, attempts to communicate to Twitter and get
         *     geocoded tweet data
         *
         *  4. Parses this live data (or returns cached data) and sends it to the main view for output to the screen. 
         **/

    	$config = $this->getServiceLocator()->get('Config'); // Get configuration and pass to Twitter Class
        $tweetMap = new \RabbitInternet\TwitterMap\TwitterMap();
        $tweetMap->configure($config);


        $address = $this->getRequest()->getPost('address',$tweetMap->get_default_city());
        if(empty($address)||$address=='') {
            $address = $tweetMap->get_default_city();
        }
         
        /* Caching */
        $cache = $this->getServiceLocator()->get('cache');

        // Remove spaces and Commas for the cache key, but retain for the search function ($tweetMap->get_GeocodedTweets()).
        $key = str_replace(' ', '_', $address);
        $key = str_replace(',', '_', $key);
        $result = $cache->getItem($key, $data);
        var_dump($address);
        die();
        if (!$data) {
            if($tweetMap->verifyCredentials()) {
                // Pass Error Message to Front End
                // Alternative display message here. Up to specific use case.
                return new ViewModel(array('errorMsg' => $tweetMap->verifyCredentials(),'map_lat' => $tweetMap->get_map_lat(), 'map_long' => $tweetMap->get_map_long()));
            }

            $markers = $tweetMap->get_GeocodedTweets($address); // Get an Array of GeoCoded Tweets, formatted to be used as Google Maps Markers
            $cache->setItem($key, $markers);
            return new ViewModel(array('map_lat' => $tweetMap->get_map_lat(), 'map_long' => $tweetMap->get_map_long(), 'markers' => $markers));
        } else {
            return new ViewModel(array('map_lat' => $tweetMap->get_map_lat(), 'map_long' => $tweetMap->get_map_long(), 'markers' => $result, 'address' => $address));
        }
            
    }
}
