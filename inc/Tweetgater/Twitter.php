<?php

set_include_path(realpath(dirname(__FILE__) . '/../'));

/**
 * Does all interaction with the twitter API for the controller layer
 * 
 * @author jfaustin
 *
 */
class Tweetgater_Twitter
{
    protected $_configFilePath = '';
        
	/**
	 * Sets up autoloader
	 */
	public function __construct()
	{
		require_once 'Zend/Loader/Autoloader.php';
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->setFallbackAutoloader(true);		
	}
	
	/**
	 * Gets the config file of the application
	 * 
	 * @return Zend_Config_Ini
	 */
    public static function getConfigFile($editable = false)
    {
        $options = array();
        
        if ($editable) {
            $options = array('skipExtends' => true, 'allowModifications' => true);
        }

        if (is_file(self::getConfigFilePath())) {
            return new Zend_Config(require self::getConfigFilePath(), $options);
        }
        
        throw new Exception('Config file not found.  Please run the installation file.');
    }	
    
    /**
     * Gets the file path to the config file
     */
    public static function getConfigFilePath()
    {
        $basePath = DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'Tweetgater' . DIRECTORY_SEPARATOR . 'Twitter.php';
        $configPath = DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
    
        return str_replace($basePath, '', __FILE__) . $configPath;
    }
    
    /**
     * Gets the base URL of the application
     */
    public static function getBaseUrl()
    {
        $config = self::getConfigFile();
        
        return (string)$config->site->url;
    }
	
	/**
	 * Gets the timeline for the user.  Uses cached version if error and it is the 
	 * first page, otherwise it will throw an exception.
	 * 
	 * @param $page = integer of the page of the timeline to request
	 * @return array
	 */
	public function getTimeline($page = 1)
	{
	    $twitter = $this->_initTwitter();
		$cache = $this->_initCache();

		$resultsPerPage = 40;
		
		$data = array();
    
	    try {
			$timeline = $twitter->status->friendsTimeline(array('page' => $page, 'count' => $resultsPerPage));		

			foreach ($timeline as $t) {
				$data[] = array(
					'id'                      => (string)$t->id,
					'user-profile_image_url'  => (string)$t->user->profile_image_url,
					'user-name'               => (string)$t->user->name,
					'user-screen_name'        => (string)$t->user->screen_name,
					'text'                    => $this->_processTweet((string)$t->text),
					'created_at'              => (string)$t->created_at,
					'elapsed_time'            => $this->_elapsedTime(strtotime($t->created_at)),
					'source'                  => (string)$t->source,
					'in_reply_to_screen_name' => (string)$t->in_reply_to_screen_name,
					'in_reply_to_status_id'   => (string)$t->in_reply_to_status_id,
				);		
			}    
			
			if ($page == 1) {
			    $cache->save($data, 'timeline');
			}
			
	    } catch (Exception $e) {
	    	if ($page != 1 || !$data = $cache->load('timeline')) {
	    		throw $e;
	    	}
	    }
	
		return $data;		
	}
	
	/**
	 * Gets the friends for the given twitter user.  Uses the cached version if error
	 * 
	 * @return array
	 */
	public function getFriends()
	{
	    $twitter = $this->_initTwitter();
		$cache = $this->_initCache();
		
		$data = array();
    
	    try {
			$friends = $twitter->user->friends();		
			
			foreach ($friends as $f) {   
        				
				$data[] = array(
					'profile_image_url' => (string)$f->profile_image_url,
					'name'              => (string)$f->name,
					'screen_name'       => (string)$f->screen_name,
					'description'       => (string)$f->description,
				);		
			}    

			$cache->save($data, 'friends');
			
	    } catch (Exception $e) {
	    	if (!$data = $cache->load('friends')) {
	    		throw $e;
	    	}
	    }
	
		return $data;				
	}
	
    /**
     * Search Twitter
     *
     * @param string $terms Search terms
     * @param int $page Page of the timeline to request
     * @return $data array of tweets found from search
     * @author Aaron Hill armahillo@gmail.com / amhill.net
     */
    public function search($terms, $page = 1)
    {
        $twitter = $this->_initTwitter();
        $cache = $this->_initCache();
        $resultsPerPage = 40;
        
        $data = array();
        try {
            $search = new Zend_Service_Twitter_Search();
            $timeline = $search->search($terms, array('page' => $page, 'since_id' => 1520639490, 'rpp' => $resultsPerPage));                         

            foreach ($timeline['results'] as $t) {
                $data[] = array(
                    'id'                      => (string)$t['id'],
                    'user-profile_image_url'  => (string)$t['profile_image_url'],
                    'user-name'               => (string)$t['from_user_name'],
                    'user-screen_name'        => (string)$t['from_user'],
                    'text'                    => $this->_processTweet((string)$t['text']),
                    'created_at'              => (string)$t['created_at'],
                    'elapsed_time'            => $this->_elapsedTime(strtotime($t['created_at'])),
                    'source'                  => html_entity_decode($t['source']),
                    'in_reply_to_screen_name' => '',
                    'in_reply_to_status_id'   => ''
                );                            
            }   

            if ($page == 1) {
                $cache->save($data, 'search');
            }
            
        } catch (Exception $e) {
            if ($page != 1 || !$data = $cache->load('search')) {
                throw $e;
            }
        }
        
        return $data;
    }	
    
    /**
     * Interacts with the TwitPic service to gather pics from the 
     * registered twitter account.
     * 
     * @param int $quantity max number of pics to return
     * @throws Exception on CURL error
     * @return $data array of twitpic photo IDs
     * @author Aaron Hill armahillo@gmail.com / amhill.net
     */
    public function getTwitPicImages($quantity = 10)
    {
        $twitter = $this->_initTwitter();
		$cache = $this->_initCache();
		
		$data = array();
		
	    try {
	        // Get the twitter account
			$me = $twitter->account->verifyCredentials();
			$user = (string)$me->name;

            // RegEx to extract the photos from the twitpic data
            $searchForPhotos = '<a href="/(\w+)">';
             
            $ch = curl_init('http://www.twitpic.com/photos/' . $user);             
            //return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            
            curl_close($ch);
             
            preg_match_all($searchForPhotos, $result, $data);
            array_shift($data);
            $data = array_slice($data[0], 0, $quantity);			
			
			$cache->save($data, 'twitpic');
			
	    } catch (Exception $e) {
	    	if (!$data = $cache->load('twitpic')) {
	    		throw $e;
	    	}
	    }
	
        return $data;
    }
	
    /**
     * Initializes the Zend_Service_Twitter object with the
     * desited OAuth specs
     * 
     * return Zend_Service_Twitter object
     */
	protected function _initTwitter()
	{
	    $oauthConfig = $this->getConfigFile();
        
        $accessToken = new Zend_Oauth_Token_Access();
        $accessToken->setToken($oauthConfig->oauth->token);
        $accessToken->setTokenSecret($oauthConfig->oauth->tokenSecret);
        
        $config = array(
            'siteUrl'         => 'http://twitter.com/oauth',
            'consumerKey'     => $oauthConfig->oauth->consumerKey,
            'consumerSecret'  => $oauthConfig->oauth->consumerSecret,
            'accessToken'     => $accessToken,
        );
                
        return new Zend_Service_Twitter($config);
	}
	
	/**
	 * Initializes the cache
	 * 
	 * @return Zend_Cache object
	 */
	protected function _initCache()
	{
	    $frontendOptions = array(
	        'lifetime'                => 21600, // cache lifetime of 6 hours
	        'automatic_serialization' => true
	    );
	
        $basePath = DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'Tweetgater' . DIRECTORY_SEPARATOR . 'Twitter.php';
        $cachePath = DIRECTORY_SEPARATOR . 'cache';      
        
        $backendOptions = array(
            'cache_dir' =>  str_replace($basePath, '', __FILE__) . $cachePath,
        );
            
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        $cache->setOption('caching', true);     

        return $cache;
	}
	
	/**
	 * replaces any links or hash tags in the tweet with a link
	 * 
	 * @param text to process
	 * @return text with links added
	 */
	protected function _processTweet($tweetText)
	{
	    $matches = array();
	    preg_match_all('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $tweetText, $matches);
	    
	    foreach ($matches[0] as $m) {
	        $tweetText = str_replace($m, '<a href="' . $m . '" target="_blank">' . $m . '</a>', $tweetText);
	    }
	    
	    $matches = array();
	    preg_match_all('/(#|@)[a-zA-Z0-9\-_\.+:=]{2,}/', $tweetText, $matches);
	
	    foreach ($matches[0] as $m) {
	        if (preg_match('/@/', $m)) {
	            $username = str_replace('@', '', $m);
	            $tweetText = str_replace($m, '@<a href="http://www.twitter.com/' . $username . '" target="_blank">' . $username . '</a>', $tweetText);
	        } else {
	            $tweetText = str_replace($m, '<a href="http://search.twitter.com/search?q=' . urlencode($m) . '" target="_blank">' . $m . '</a>', $tweetText);
	        }
	    }    
	    
	    return $tweetText;
	}	
	
	/**
	* Calculates the elapsed time since the passed in timestamp value.  The string
	* that is returned is like 4 days ago, 2 minutes ago, etc.
	*
	* @param int|string The timestamp from which to get the elapsed time
	*/
	protected function _elapsedTime($timestamp)
	{
	    if (empty($timestamp)) {
	        return '';
	    }
	    
	    $names = "day, hour, minute, seconds";
	
	    $n = explode ("," , $names);
	    
	    if (count($n) < 4) {
	        $n = array ("day", "hour", "minute", "seconds");
	    }
	
	    $difference = time() - intval($timestamp);
	
	    $days = floor($difference / (60 * 60 * 24));
	    $hours = floor($difference / (60 * 60));
	    $minutes = floor($difference / 60);
	
	    $s = "";
	    $val = 0;
	    
	    if ($minutes > 0) {
	        
	        $val = $minutes;
	        $s = $n[2];
	        
	        if ($hours > 0) {
	            $val = $hours;
	            $s = $n[1];
	
	            if ($days > 0) {
	                $val = $days;
	                $s = $n[0];
	            }
	        }
	        
	    } else {
	        return $difference . " " . $n[3] . " ago";
	    }
	
	    if ($s == $n[0]) {
	        $s = "day";
	        
	        if ($val > 1) {
	            $s .= "s";
	        }
	    } else if ($s == $n[1]) {
	        $s = "hour";
	    
	        if ($val > 1) {
	            $s .= "s";
	        }
	        
	    } else if ($s == $n[2]) {
	        $s = "minute";
	        
	        if ($val > 1) {
	            $s .= "s";
	        }
	    }
	
	    return "{$val} {$s} ago";
	}    	
}