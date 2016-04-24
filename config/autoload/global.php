<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'twitter' => array('access_token' => array(
                                'token'  => '2328788546-NYgGUjtO7QriMk1rimRwqbhraWStmlEYoZo9xLM',
                                'secret' => 'F0OVBd37Mr8dnJ2wmWopfob3jnRyYfe2pYJ9bEo2bInP7',
                            ),
                            'oauth_options' => array(
                                'consumerKey' => '6WejwQpq8ykDHHJLwqHpXvHjS',
                                'consumerSecret' => 'SoBqg9gU4xyAPYv6GUqlQHcz3t0VWQxBYiFpL2wzGUlhBAhRDj',
                            ),
                            'http_client_options' => array(
                                'adapter' => 'Zend\Http\Client\Adapter\Curl',
                                'curloptions' => array(
                                    CURLOPT_SSL_VERIFYHOST => false,
                                    CURLOPT_SSL_VERIFYPEER => false,
                               ),
                            ),
            ),
    'defaults' => array('twitter' => array(
                                        'radius' => '50km',
                                        'count' => 20),
                        'map' => array(
                                    'default_city' => 'Bangkok, Thailand',
                                    'default_city_lat' => '13.7563',
                                    'default_city_long' => '100.5018')
                       ),
    'service_manager' => array(
	'factories' => array(
		'cache' => function () {
			return \Zend\Cache\StorageFactory::factory(array(
				'adapter' => array(
					'name' => 'filesystem',
					'options' => array(
					'cache_dir' => 'data/cache',
					'ttl' => 3600, // Set for 1hr
					),
				),
				'plugins' => array(
					'exception_handler' => array(
						'throw_exceptions' => false
					),
					'Serializer', // dont forget this one as on my example im caching an array
				),
			));
		},
	),			
),
    
);
